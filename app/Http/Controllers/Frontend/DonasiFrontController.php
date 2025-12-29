<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonasiTransaksiRequest;
use App\Models\Donasi;
use App\Models\TransaksiDonasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DonasiFrontController extends Controller
{
    public function index(): View
    {
        $donasis = Donasi::withCount('transaksiDonasis')->latest()->paginate(9);

        $totalRaised = Donasi::sum('dana_terkumpul');
        $totalTarget = Donasi::sum('target_dana');
        $activeCampaigns = Donasi::count();
        $totalDonors = TransaksiDonasi::distinct('user_id')->count('user_id');
        $recentDonations = TransaksiDonasi::with(['donasi', 'user'])
            ->latest()
            ->take(4)
            ->get();

        return view('front.donasis.index', compact(
            'donasis',
            'totalRaised',
            'totalTarget',
            'activeCampaigns',
            'totalDonors',
            'recentDonations'
        ));
    }

    public function show(Donasi $donasi): View
    {
        $donasi->load('transaksiDonasis');

        return view('front.donasis.show', compact('donasi'));
    }

    public function donate(DonasiTransaksiRequest $request, Donasi $donasi): RedirectResponse
    {
        $this->authorize('create', TransaksiDonasi::class);

        $amount = (int) $request->validated('jumlah');
        $user = $request->user();

        $transaksi = TransaksiDonasi::create([
            'user_id' => $user->id,
            'donasi_id' => $donasi->id,
            'jumlah' => $amount,
            'status_pembayaran' => 'pending',
            'gateway' => 'xendit',
        ]);

        $secretKey = config('services.xendit.secret_key');

        if (! $secretKey) {
            return redirect()->route('donasi.riwayat')
                ->with('success', 'Donasi tercatat. Konfigurasi Xendit belum diisi, hubungi admin untuk menyelesaikan pembayaran.');
        }

        $externalId = 'donasi-'.$transaksi->id.'-'.Str::random(6);
        $baseUrl = rtrim(config('services.xendit.base_url', 'https://api.xendit.co'), '/');

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->asJson()
                ->post($baseUrl.'/v2/invoices', [
                    'external_id' => $externalId,
                    'amount' => $amount,
                    'currency' => 'IDR',
                    'description' => 'Donasi '.$donasi->judul,
                    'payer_email' => $user->email,
                    'customer' => [
                        'given_names' => $user->name,
                        'email' => $user->email,
                    ],
                    'success_redirect_url' => config('services.xendit.success_redirect'),
                    'failure_redirect_url' => config('services.xendit.failure_redirect'),
                ]);

            if ($response->failed() || ! $response->json('invoice_url')) {
                throw new \RuntimeException('Xendit API error: '.$response->body());
            }

            $payload = $response->json();
            $transaksi->update([
                'xendit_invoice_id' => $payload['id'] ?? null,
                'xendit_external_id' => $payload['external_id'] ?? $externalId,
                'payment_url' => $payload['invoice_url'] ?? null,
                'status_pembayaran' => 'pending',
            ]);

            return redirect()->away($payload['invoice_url'])
                ->with('success', 'Mengalihkan ke pembayaran Xendit...');
        } catch (\Throwable $e) {
            Log::error('Gagal membuat invoice Xendit', [
                'error' => $e->getMessage(),
                'donasi_id' => $donasi->id,
                'transaksi_id' => $transaksi->id,
            ]);

            $transaksi->update(['status_pembayaran' => 'gagal']);

            return redirect()->route('donasi.show', $donasi)
                ->withErrors(['jumlah' => 'Gagal membuat tagihan Xendit. Silakan coba lagi atau hubungi admin.'])
                ->withInput();
        }
    }

    public function riwayat(Request $request): View
    {
        $transaksis = TransaksiDonasi::with('donasi')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('front.donasis.riwayat', compact('transaksis'));
    }
}
