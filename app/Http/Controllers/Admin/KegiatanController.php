<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanRequest;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Kegiatan::class, 'kegiatan');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status', 'time']);
        $now = now();

        $kegiatansQuery = Kegiatan::query()
            ->withCount('pendaftarans')
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

        return view('admin.kegiatans.index', compact('kegiatans', 'stats', 'filters'));
    }

    public function create(): View
    {
        return view('admin.kegiatans.create');
    }

    public function store(KegiatanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['poster'] = $this->normalizePosterUrl($data['poster'] ?? null);

        Kegiatan::create($data);

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan dibuat.');
    }

    public function show(Kegiatan $kegiatan): View
    {
        $kegiatan->load('pendaftarans.user');

        return view('admin.kegiatans.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatans.edit', compact('kegiatan'));
    }

    public function update(KegiatanRequest $request, Kegiatan $kegiatan): RedirectResponse
    {
        $data = $request->validated();
        $data['poster'] = $this->normalizePosterUrl($data['poster'] ?? null);

        $kegiatan->update($data);

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatans.index')->with('success', 'Kegiatan dihapus.');
    }

    protected function normalizePosterUrl(?string $url): ?string
{
    $url = trim((string) $url);
    if ($url === '') return null;

    // Ambil fileId dari berbagai format link Drive
    $fileId = null;

    if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) $fileId = $m[1];
    if (preg_match('~drive\.google\.com/open\?id=([^&]+)~', $url, $m)) $fileId = $m[1];
    if (!$fileId && preg_match('~[?&]id=([^&]+)~', $url, $m)) $fileId = $m[1];

    // Kalau user sudah input uc?....id=...
    if (!$fileId && str_contains($url, 'drive.google.com/uc?') && preg_match('~[?&]id=([^&]+)~', $url, $m)) {
        $fileId = $m[1];
    }

    // Kalau ketemu fileId → pakai thumbnail (paling stabil buat img)
    if ($fileId) {
        return "https://drive.google.com/thumbnail?id={$fileId}&sz=w1200";
    }

    // selain drive, biarin
    return $url;
}
}
