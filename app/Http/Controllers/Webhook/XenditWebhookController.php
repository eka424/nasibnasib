<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\TransaksiDonasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $callbackToken = config('services.xendit.callback_token');

        if ($callbackToken && $request->header('x-callback-token') !== $callbackToken) {
            abort(403, 'Invalid callback token');
        }

        $externalId = $request->input('external_id');
        $invoiceId = $request->input('id');
        $status = strtoupper((string) $request->input('status', ''));

        $transaksi = TransaksiDonasi::where('xendit_external_id', $externalId)
            ->orWhere('xendit_invoice_id', $invoiceId)
            ->first();

        if (! $transaksi) {
            Log::warning('Xendit webhook: transaksi tidak ditemukan', [
                'external_id' => $externalId,
                'invoice_id' => $invoiceId,
            ]);

            return response()->noContent(404);
        }

        $mappedStatus = match ($status) {
            'PAID', 'SETTLED' => 'berhasil',
            'EXPIRED', 'FAILED', 'VOIDED' => 'gagal',
            default => 'pending',
        };

        $transaksi->forceFill([
            'xendit_invoice_id' => $invoiceId ?: $transaksi->xendit_invoice_id,
            'xendit_external_id' => $externalId ?: $transaksi->xendit_external_id,
            'payment_url' => $request->input('invoice_url', $transaksi->payment_url),
            'status_pembayaran' => $mappedStatus,
            'dibayar_pada' => $request->input('paid_at') ? Carbon::parse($request->input('paid_at')) : $transaksi->dibayar_pada,
        ])->save();

        if ($mappedStatus === 'berhasil') {
            $transaksi->donasi->syncDanaTerkumpul();
        }

        return response()->noContent();
    }
}
