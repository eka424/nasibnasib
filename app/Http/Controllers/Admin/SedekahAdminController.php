<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SedekahCampaign;
use App\Models\SedekahTransaction;
use Illuminate\Http\Request;

class SedekahAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $startMonth = now()->startOfMonth();
        $endMonth   = now()->endOfMonth();

        // =========================
        // STATS (untuk cards)
        // =========================
        $totalThisMonth = (int) SedekahTransaction::query()
            ->where('status', 'success')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->sum('jumlah');

        $sumSuccess = (int) SedekahTransaction::query()
            ->where('status', 'success')
            ->sum('jumlah');

        $pendingCount = (int) SedekahTransaction::query()
            ->where('status', 'pending')
            ->count();

        $successCount = (int) SedekahTransaction::query()
            ->where('status', 'success')
            ->count();

        $failedCount = (int) SedekahTransaction::query()
            ->whereIn('status', ['failed', 'expire', 'expired', 'cancel', 'deny'])
            ->count();

        $activeCampaigns = (int) SedekahCampaign::query()
            ->where('is_active', true)
            ->count();

        $stats = [
            'pending' => $pendingCount,
            'success' => $successCount,
            'failed'  => $failedCount,
            'sum_success' => $sumSuccess,
            'this_month' => $totalThisMonth,
            'active_campaigns' => $activeCampaigns,
        ];

        // =========================
        // DROPDOWN PROGRAM
        // =========================
        $campaigns = SedekahCampaign::query()
            ->orderBy('judul')
            ->get();

        // =========================
        // TABLE TRANSAKSI (latest 10)
        // sesuai filter GET ?status=&campaign=
        // =========================
        $q = SedekahTransaction::query()
            ->with(['user', 'campaign'])
            ->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        if ($request->filled('campaign')) {
            $q->where('campaign_id', $request->string('campaign'));
        }

        $transaksis = $q->take(10)->get();$transaksis = $q->paginate(10)->withQueryString();
        // =========================
        // TOP CAMPAIGNS
        // =========================
        $topCampaigns = SedekahCampaign::query()
            ->orderByDesc('dana_terkumpul')
            ->take(5)
            ->get();

        return view('admin.sedekah.dashboard', compact(
            'stats',
            'campaigns',
            'transaksis',
            'topCampaigns'
        ));
    }

    public function transactions(Request $request)
    {
        $q = SedekahTransaction::query()
            ->with(['user', 'campaign'])
            ->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        // optional filter campaign biar konsisten
        if ($request->filled('campaign')) {
            $q->where('campaign_id', $request->string('campaign'));
        }

        if ($request->filled('order_id')) {
            $q->where('order_id', 'like', '%' . $request->string('order_id') . '%');
        }

        $transaksis = $q->paginate(20)->withQueryString();

        return view('admin.sedekah.transactions', compact('transaksis'));
    }

    public function campaigns()
    {
        $campaigns = SedekahCampaign::query()
            ->latest()
            ->paginate(20);

        return view('admin.sedekah.campaigns', compact('campaigns'));
    }

    public function campaignStore(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:160'],
            'deskripsi' => ['nullable', 'string'],
            'target_dana' => ['required', 'integer', 'min:0'],
            'poster' => ['nullable', 'string', 'max:255'],
            'tanggal_selesai' => ['nullable', 'date'],
            'is_active' => ['required', 'boolean'],
        ]);

        SedekahCampaign::create($data + ['dana_terkumpul' => 0]);

        return back()->with('success', 'Campaign sedekah berhasil ditambahkan.');
    }

    public function campaignUpdate(Request $request, SedekahCampaign $campaign)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:160'],
            'deskripsi' => ['nullable', 'string'],
            'target_dana' => ['required', 'integer', 'min:0'],
            'poster' => ['nullable', 'string', 'max:255'],
            'tanggal_selesai' => ['nullable', 'date'],
            'is_active' => ['required', 'boolean'],
        ]);

        $campaign->update($data);

        return back()->with('success', 'Campaign sedekah berhasil diperbarui.');
    }
}
