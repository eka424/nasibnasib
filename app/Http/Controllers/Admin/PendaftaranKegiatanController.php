<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PendaftaranKegiatanStatusRequest;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranKegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(PendaftaranKegiatan::class, 'pendaftaran_kegiatan');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status', 'kegiatan_id', 'waktu']);
        $now = now();

        $pendaftaranQuery = PendaftaranKegiatan::with(['user', 'kegiatan'])->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $pendaftaranQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                    $kegiatanQuery->where('nama_kegiatan', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $filters['status'] ?? null) {
            $pendaftaranQuery->where('status', $status);
        }

        if ($kegiatanId = (int) ($filters['kegiatan_id'] ?? 0)) {
            $pendaftaranQuery->where('kegiatan_id', $kegiatanId);
        }

        if ($time = $filters['waktu'] ?? null) {
            $pendaftaranQuery->where(function ($query) use ($time, $now) {
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

        $pendaftaranKegiatans = $pendaftaranQuery->paginate(10)->withQueryString();
        $kegiatans = Kegiatan::orderBy('nama_kegiatan')->get(['id', 'nama_kegiatan']);

        $counts = PendaftaranKegiatan::selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as approved")
            ->selectRaw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as rejected")
            ->first();

        $currentMonthRegistrations = PendaftaranKegiatan::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count();
        $uniqueParticipants = PendaftaranKegiatan::distinct('user_id')->count('user_id');

        $stats = [
            'total' => [
                'value' => $counts?->total ?? 0,
                'description' => '+' . number_format($currentMonthRegistrations) . ' pendaftaran bulan ini',
            ],
            'pending' => [
                'value' => $counts?->pending ?? 0,
                'description' => 'Menunggu persetujuan',
            ],
            'approved' => [
                'value' => $counts?->approved ?? 0,
                'description' => 'Berhasil disetujui',
            ],
            'unique' => [
                'value' => $uniqueParticipants,
                'description' => 'Total peserta unik',
            ],
        ];

        $statusOptions = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return view('admin.pendaftaran-kegiatans.index', compact(
            'pendaftaranKegiatans',
            'kegiatans',
            'stats',
            'filters',
            'statusOptions'
        ));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.pendaftaran-kegiatans.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('admin.pendaftaran-kegiatans.index');
    }

    public function show(PendaftaranKegiatan $pendaftaran_kegiatan): View
    {
        return view('admin.pendaftaran-kegiatans.show', ['pendaftaran' => $pendaftaran_kegiatan]);
    }

    public function edit(PendaftaranKegiatan $pendaftaran_kegiatan): View
    {
        return view('admin.pendaftaran-kegiatans.edit', ['pendaftaran' => $pendaftaran_kegiatan]);
    }

    public function update(PendaftaranKegiatanStatusRequest $request, PendaftaranKegiatan $pendaftaran_kegiatan): RedirectResponse
    {
        $pendaftaran_kegiatan->update($request->validated());

        return redirect()->route('admin.pendaftaran-kegiatans.index')->with('success', 'Status pendaftaran diperbarui.');
    }

    public function destroy(PendaftaranKegiatan $pendaftaran_kegiatan): RedirectResponse
    {
        $pendaftaran_kegiatan->delete();

        return redirect()->route('admin.pendaftaran-kegiatans.index')->with('success', 'Pendaftaran dihapus.');
    }
}
