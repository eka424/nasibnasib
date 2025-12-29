<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TransaksiDonasiRequest;
use App\Http\Requests\TransaksiDonasiStatusRequest;
use App\Models\Donasi;
use App\Models\TransaksiDonasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiDonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(TransaksiDonasi::class, 'transaksi_donasi');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status', 'donasi_id']);

        $query = TransaksiDonasi::with(['user', 'donasi'])->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('donasi', function ($donasiQuery) use ($search) {
                    $donasiQuery->where('judul', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $filters['status'] ?? null) {
            $query->where('status_pembayaran', $status);
        }

        if ($donasiId = $filters['donasi_id'] ?? null) {
            $query->where('donasi_id', $donasiId);
        }

        $transaksis = $query->paginate(12)->withQueryString();
        $donasis = Donasi::orderBy('judul')->get(['id', 'judul']);

        $now = now();
        $currentRange = [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        $previousRange = [$now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth()];

        $currentAmount = TransaksiDonasi::where('status_pembayaran', 'berhasil')
            ->whereBetween('created_at', $currentRange)
            ->sum('jumlah');

        $previousAmount = TransaksiDonasi::where('status_pembayaran', 'berhasil')
            ->whereBetween('created_at', $previousRange)
            ->sum('jumlah');

        $pendingCount = TransaksiDonasi::where('status_pembayaran', 'pending')->count();
        $successCount = TransaksiDonasi::where('status_pembayaran', 'berhasil')->count();
        $uniqueDonors = TransaksiDonasi::distinct('user_id')->count('user_id');

        $stats = [
            'total' => [
                'value' => $currentAmount,
                'growth' => $this->growthPercentage($currentAmount, $previousAmount),
            ],
            'pending' => $pendingCount,
            'success' => $successCount,
            'unique' => $uniqueDonors,
        ];

        $statusOptions = [
            'pending' => 'Pending',
            'berhasil' => 'Berhasil',
            'gagal' => 'Gagal',
        ];

        return view('admin.transaksi-donasis.index', compact(
            'transaksis',
            'donasis',
            'stats',
            'filters',
            'statusOptions'
        ));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        $donasis = Donasi::orderBy('judul')->get();

        return view('admin.transaksi-donasis.create', compact('users', 'donasis'));
    }

    public function store(TransaksiDonasiRequest $request): RedirectResponse
    {
        $transaksi = TransaksiDonasi::create($request->validated());
        $transaksi->donasi->syncDanaTerkumpul();

        return redirect()->route('admin.transaksi-donasis.index')->with('success', 'Transaksi donasi dicatat.');
    }

    public function show(TransaksiDonasi $transaksi_donasi): View
    {
        return view('admin.transaksi-donasis.show', ['transaksi' => $transaksi_donasi]);
    }

    public function edit(TransaksiDonasi $transaksi_donasi): View
    {
        return view('admin.transaksi-donasis.edit', ['transaksi' => $transaksi_donasi]);
    }

    public function update(TransaksiDonasiStatusRequest $request, TransaksiDonasi $transaksi_donasi): RedirectResponse
    {
        $transaksi_donasi->update($request->validated());
        $transaksi_donasi->donasi->syncDanaTerkumpul();

        return redirect()->route('admin.transaksi-donasis.index')->with('success', 'Status transaksi diperbarui.');
    }

    public function destroy(TransaksiDonasi $transaksi_donasi): RedirectResponse
    {
        $donasi = $transaksi_donasi->donasi;
        $transaksi_donasi->delete();
        $donasi->syncDanaTerkumpul();

        return redirect()->route('admin.transaksi-donasis.index')->with('success', 'Transaksi donasi dihapus.');
    }

    protected function growthPercentage(float|int $current, float|int $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }
}
