<?php

namespace App\Http\Controllers;

use App\Models\SedekahCampaign;
use App\Models\SedekahTransaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Snap;

class SedekahMasjidController extends Controller
{
  public function index()
  {
    $campaigns = SedekahCampaign::query()
      ->where('is_active', true)
      ->latest()
      ->paginate(9);

    $totalRaised = SedekahCampaign::sum('dana_terkumpul');
    $totalTarget = SedekahCampaign::sum('target_dana');
    $overallProgress = $totalTarget > 0 ? min(100, max(0, ($totalRaised / $totalTarget) * 100)) : 0;

    $recent = SedekahTransaction::query()
      ->whereIn('status', ['success'])
      ->latest()
      ->take(8)
      ->get();

    return view('sedekah-masjid.donasimasjid', compact(
      'campaigns','totalRaised','totalTarget','overallProgress','recent'
    ));
  }

  public function createTransaction(Request $request)
  {
    $data = $request->validate([
      'sedekah_campaign_id' => ['nullable','exists:sedekah_campaigns,id'],
      'jumlah' => ['required','integer','min:10000'],
      'nama' => ['nullable','string','max:120'],
      'email' => ['nullable','email','max:160'],
      'whatsapp' => ['nullable','string','max:30'],
      'pesan' => ['nullable','string','max:500'],
    ]);

    $orderId = 'SM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

    $trx = SedekahTransaction::create([
      'user_id' => auth()->id(),
      'sedekah_campaign_id' => $data['sedekah_campaign_id'] ?? null,
      'order_id' => $orderId,
      'jumlah' => (int) $data['jumlah'],
      'nama' => $data['nama'] ?? (auth()->user()->name ?? null),
      'email' => $data['email'] ?? (auth()->user()->email ?? null),
      'whatsapp' => $data['whatsapp'] ?? null,
      'pesan' => $data['pesan'] ?? null,
      'status' => 'pending',
    ]);

    MidtransService::init();

    $itemName = $trx->campaign?->judul ? ('Sedekah Masjid - ' . $trx->campaign->judul) : 'Sedekah Masjid (Umum)';

    $params = [
      'transaction_details' => [
        'order_id' => $trx->order_id,
        'gross_amount' => $trx->jumlah,
      ],
      'item_details' => [
        [
          'id' => $trx->campaign?->id ? 'CAMP-' . $trx->campaign->id : 'UMUM',
          'price' => $trx->jumlah,
          'quantity' => 1,
          'name' => $itemName,
        ]
      ],
      'customer_details' => [
        'first_name' => $trx->nama ?: 'Hamba Allah',
        'email' => $trx->email ?: 'no-reply@example.com',
        'phone' => $trx->whatsapp ?: '',
      ],
      'callbacks' => [
        'finish' => route('sedekah.finish', ['order_id' => $trx->order_id]),
      ],
    ];

    $snapToken = Snap::getSnapToken($params);
    $trx->update(['snap_token' => $snapToken]);

    return redirect()->route('sedekah.pay', ['order_id' => $trx->order_id]);
  }

  public function payPage(string $order_id)
  {
    $trx = SedekahTransaction::where('order_id', $order_id)->firstOrFail();
    return view('sedekah-masjid.pay', compact('trx'));
  }

  public function finish(string $order_id)
  {
    $trx = SedekahTransaction::where('order_id', $order_id)->firstOrFail();
    return view('sedekah-masjid.finish', compact('trx'));
  }

  public function riwayat()
  {
    $transaksis = SedekahTransaction::query()
      ->where('user_id', auth()->id())
      ->latest()
      ->paginate(12);

    return view('sedekah-masjid.riwayat', compact('transaksis'));
  }
}
