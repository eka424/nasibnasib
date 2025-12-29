<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonasiRequest;
use App\Models\Donasi;
use App\Models\TransaksiDonasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Donasi::class, 'donasi');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status', 'donasi_id', 'waktu']);
        $now = now();

        $transactionsQuery = TransaksiDonasi::with(['user', 'donasi'])->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $transactionsQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('donasi', function ($donasiQuery) use ($search) {
                    $donasiQuery->where('judul', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $filters['status'] ?? null) {
            $transactionsQuery->where('status_pembayaran', $status);
        }

        if ($donasiId = (int) ($filters['donasi_id'] ?? 0)) {
            $transactionsQuery->where('donasi_id', $donasiId);
        }

        if ($time = $filters['waktu'] ?? null) {
            $transactionsQuery->where(function ($query) use ($time, $now) {
                if ($time === 'today') {
                    $query->whereDate('created_at', $now->toDateString());
                } elseif ($time === 'this_week') {
                    $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                } elseif ($time === 'this_month') {
                    $query->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $now->month);
                } elseif ($time === 'past') {
                    $query->where('created_at', '<', $now->copy()->startOfMonth());
                }
            });
        }

        $transactions = $transactionsQuery
            ->paginate(10, ['*'], 'transaksi_page')
            ->withQueryString();

        $campaignOptions = Donasi::orderBy('judul')->get(['id', 'judul']);
        $campaigns = Donasi::latest()
            ->paginate(6, ['*'], 'kampanye_page')
            ->withQueryString();

        $totalSuccess = TransaksiDonasi::where('status_pembayaran', 'berhasil')->sum('jumlah');
        $pendingAmount = TransaksiDonasi::where('status_pembayaran', 'pending')->sum('jumlah');
        $completedAmount = $totalSuccess;
        $uniqueDonors = TransaksiDonasi::distinct('user_id')->count('user_id');
        $currentMonthSuccess = TransaksiDonasi::where('status_pembayaran', 'berhasil')
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('jumlah');

        $stats = [
            'total' => [
                'value' => $totalSuccess,
                'format' => 'currency',
                'description' => '+' . $this->formatCurrency($currentMonthSuccess) . ' bulan ini',
            ],
            'pending' => [
                'value' => $pendingAmount,
                'format' => 'currency',
                'description' => 'Menunggu konfirmasi',
            ],
            'completed' => [
                'value' => $completedAmount,
                'format' => 'currency',
                'description' => 'Berhasil disalurkan',
            ],
            'unique' => [
                'value' => $uniqueDonors,
                'format' => 'number',
                'description' => 'Total individu donatur',
            ],
        ];

        $statusOptions = [
            'pending' => 'Pending',
            'berhasil' => 'Selesai',
            'gagal' => 'Dibatalkan',
        ];

        return view('admin.donasis.index', [
            'transactions' => $transactions,
            'campaigns' => $campaigns,
            'campaignOptions' => $campaignOptions,
            'stats' => $stats,
            'filters' => $filters,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function create(): View
    {
        return view('admin.donasis.create');
    }

    public function store(DonasiRequest $request): RedirectResponse
    {
        Donasi::create($request->validated());

        return redirect()->route('admin.donasis.index')->with('success', 'Donasi baru dibuat.');
    }

    public function show(Donasi $donasi): View
    {
        $donasi->load(['transaksiDonasis.user']);

        return view('admin.donasis.show', compact('donasi'));
    }

    public function edit(Donasi $donasi): View
    {
        return view('admin.donasis.edit', compact('donasi'));
    }

    public function update(DonasiRequest $request, Donasi $donasi): RedirectResponse
    {
        $donasi->update($request->validated());

        return redirect()->route('admin.donasis.index')->with('success', 'Donasi diperbarui.');
    }

    public function destroy(Donasi $donasi): RedirectResponse
    {
        $donasi->delete();

        return redirect()->route('admin.donasis.index')->with('success', 'Donasi dihapus.');
    }

    public function recalc(Donasi $donasi): RedirectResponse
    {
        $this->authorize('update', $donasi);
        $donasi->syncDanaTerkumpul();

        return back()->with('success', 'Dana terkumpul disinkronkan ulang.');
    }

    protected function formatCurrency(int $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
