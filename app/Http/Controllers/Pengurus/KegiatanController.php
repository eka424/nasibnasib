<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Kegiatan::class);

        $filters = $request->only(['q', 'status', 'time']);
        $now = now();

        $kegiatansQuery = Kegiatan::withCount('pendaftarans')
            ->latest('tanggal_mulai');

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $kegiatansQuery->where(function ($query) use ($search) {
                $query->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($status = $filters['status'] ?? null) {
            $kegiatansQuery->where(function ($query) use ($status, $now) {
                if ($status === 'upcoming') {
                    $query->where('tanggal_mulai', '>', $now);
                } elseif ($status === 'ongoing') {
                    $query->where('tanggal_mulai', '<=', $now)
                        ->where(function ($inner) use ($now) {
                            $inner->whereNull('tanggal_selesai')
                                ->orWhere('tanggal_selesai', '>=', $now);
                        });
                } elseif ($status === 'completed') {
                    $query->whereNotNull('tanggal_selesai')
                        ->where('tanggal_selesai', '<', $now);
                }
            });
        }

        if ($time = $filters['time'] ?? null) {
            $kegiatansQuery->where(function ($query) use ($time, $now) {
                if ($time === 'today') {
                    $query->whereDate('tanggal_mulai', $now->toDateString());
                } elseif ($time === 'this_week') {
                    $query->whereBetween('tanggal_mulai', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                } elseif ($time === 'this_month') {
                    $query->whereYear('tanggal_mulai', $now->year)
                        ->whereMonth('tanggal_mulai', $now->month);
                } elseif ($time === 'past') {
                    $query->where('tanggal_mulai', '<', $now);
                }
            });
        }

        $kegiatans = $kegiatansQuery->paginate(8)->withQueryString();

        $stats = [
            'total' => Kegiatan::count(),
            'upcoming' => Kegiatan::where('tanggal_mulai', '>', $now)->count(),
            'ongoing' => Kegiatan::where('tanggal_mulai', '<=', $now)
                ->where(function ($query) use ($now) {
                    $query->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', $now);
                })->count(),
            'completed' => Kegiatan::whereNotNull('tanggal_selesai')
                ->where('tanggal_selesai', '<', $now)
                ->count(),
            'participants' => PendaftaranKegiatan::count(),
        ];

        return view('admin.pengurus-kegiatan', [
            'kegiatans' => $kegiatans,
            'stats' => $stats,
            'filters' => $filters,
        ]);
    }
}
