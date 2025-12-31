<?php

namespace App\Http\Controllers;

use App\Models\SedekahCampaign;
use App\Models\SedekahTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransWebhookController extends Controller
{
  public function handle(Request $request)
  {
    // Midtrans notif biasanya JSON
    $payload = $request->all();

    $orderId = (string) ($payload['order_id'] ?? '');
    $statusCode = (string) ($payload['status_code'] ?? '');
    $grossAmount = (string) ($payload['gross_amount'] ?? '');
    $signatureKey = (string) ($payload['signature_key'] ?? '');

    // Verify signature: SHA512(order_id + status_code + gross_amount + server_key)
    $serverKey = (string) config('midtrans.server_key');
    $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

    if (!hash_equals($expected, $signatureKey)) {
      return response()->json(['message' => 'Invalid signature'], 403);
    }

    $trxStatus = (string) ($payload['transaction_status'] ?? '');
    $fraud = (string) ($payload['fraud_status'] ?? '');
    $paymentType = (string) ($payload['payment_type'] ?? null);
    $transactionId = (string) ($payload['transaction_id'] ?? null);

    $trx = SedekahTransaction::where('order_id', $orderId)->first();
    if (!$trx) {
      return response()->json(['message' => 'Order not found'], 404);
    }

    // Map status
    $newStatus = $trx->status;

    if ($trxStatus === 'capture') {
      // kartu kredit
      $newStatus = ($fraud === 'challenge') ? 'challenge' : 'success';
    } elseif ($trxStatus === 'settlement') {
      $newStatus = 'success';
    } elseif ($trxStatus === 'pending') {
      $newStatus = 'pending';
    } elseif ($trxStatus === 'deny') {
      $newStatus = 'failed';
    } elseif ($trxStatus === 'expire') {
      $newStatus = 'expired';
    } elseif ($trxStatus === 'cancel') {
      $newStatus = 'cancel';
    } else {
      $newStatus = $trxStatus ?: $trx->status;
    }

    DB::transaction(function () use ($trx, $payload, $newStatus, $paymentType, $transactionId) {
      $wasSuccess = $trx->status === 'success';

      $trx->update([
        'status' => $newStatus,
        'payment_type' => $paymentType,
        'transaction_id' => $transactionId,
        'fraud_status' => $payload['fraud_status'] ?? null,
        'raw_notif' => $payload,
      ]);

      // jika baru berubah jadi success, tambahkan ke campaign dana_terkumpul
      if (!$wasSuccess && $newStatus === 'success' && $trx->sedekah_campaign_id) {
        SedekahCampaign::where('id', $trx->sedekah_campaign_id)
          ->increment('dana_terkumpul', (int) $trx->jumlah);
      }
    });

    return response()->json(['message' => 'OK']);
  }
}
