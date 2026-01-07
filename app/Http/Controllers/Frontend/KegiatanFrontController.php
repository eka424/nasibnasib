<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanDaftarRequest;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ INI YANG BENAR
use Illuminate\View\View;
use Carbon\Carbon;

class KegiatanFrontController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $kegiatansQuery = Kegiatan::withCount('pendaftarans')
            ->orderBy('tanggal_mulai');

        if ($search) {
            $kegiatansQuery->where(function ($query) use ($search) {
                $query->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $kegiatans = $kegiatansQuery->paginate(9)->withQueryString();

        return view('front.kegiatans.index', compact('kegiatans', 'search'));
    }

    public function show(Kegiatan $kegiatan): View
{
    $kegiatan->loadCount('pendaftarans');

    $user = request()->user();
    $sudahDaftar = false;

    if ($user) {
        $sudahDaftar = $kegiatan->pendaftarans()
            ->where('user_id', $user->id)
            ->exists();
    }

    return view('front.kegiatans.show', compact('kegiatan', 'sudahDaftar'));
}



public function daftar(KegiatanDaftarRequest $request, Kegiatan $kegiatan): RedirectResponse
{
    $user = $request->user();
    $this->authorize('create', PendaftaranKegiatan::class);

    $result = DB::transaction(function () use ($kegiatan, $user) {
        // kunci row kegiatan biar request barengan gak tembus kuota
        $kegiatanLocked = Kegiatan::query()
            ->whereKey($kegiatan->id)
            ->lockForUpdate()
            ->firstOrFail();

        // kalau sudah daftar
        $existing = PendaftaranKegiatan::query()
            ->where('user_id', $user->id)
            ->where('kegiatan_id', $kegiatanLocked->id)
            ->first();

        if ($existing) {
            return ['status' => 'already'];
        }

        // cek kuota
        $terdaftar = PendaftaranKegiatan::query()
            ->where('kegiatan_id', $kegiatanLocked->id)
            ->count();

        if ($kegiatanLocked->kuota && $terdaftar >= $kegiatanLocked->kuota) {
            return ['status' => 'full'];
        }

        PendaftaranKegiatan::create([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatanLocked->id,
        ]);

        return ['status' => 'new'];
    });

    if ($result['status'] === 'full') {
        return back()->with('kegiatan_flash', [
            'type' => 'warning',
            'message' => 'Maaf, kuota pendaftaran sudah penuh. Pendaftaran ditutup.',
            'gcal' => null,
        ]);
    }

    $message = $result['status'] === 'new'
        ? "Pendaftaran berhasil. Anda sudah terdaftar, jangan lupa datang pada tanggal {$kegiatan->tanggal_mulai_label}."
        : "Alhamdulillah - Anda sudah terdaftar. Jangan lupa datang pada tanggal {$kegiatan->tanggal_mulai_label}.";

    return back()->with('kegiatan_flash', [
        'type' => 'success',
        'message' => $message,
        'gcal' => $kegiatan->google_calendar_url,
    ]);
}


public function calendar(Request $request)
{
    $tz = 'Asia/Makassar';

    $month = $request->query('month'); // 2026-01
    $base = $month
        ? Carbon::createFromFormat('Y-m', $month, $tz)->startOfMonth()
        : Carbon::now($tz)->startOfMonth();

    // grid kalender: mulai Senin, sampai Minggu
    $start = $base->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
    $end   = $base->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY)->endOfDay();

    // AMBIL semua kegiatan yang tanggal_mulai-nya masuk range grid
    $items = Kegiatan::query()
        ->whereBetween('tanggal_mulai', [$start, $end])
        ->orderBy('tanggal_mulai')
        ->get();

    // GROUP by tanggal (Y-m-d) pakai timezone WITA
    $byDate = $items
        ->groupBy(fn ($k) => $k->tanggal_mulai
            ? $k->tanggal_mulai->copy()->timezone($tz)->toDateString()
            : null
        )
        ->filter(); // buang key null kalau ada

    // bikin array tanggal untuk grid
    $days = [];
    $cursor = $start->copy();
    while ($cursor->lte($end)) {
        $days[] = $cursor->copy();
        $cursor->addDay();
    }

    return view('frontend.kegiatan.calendar', [
        'base'   => $base,
        'days'   => $days,
        'byDate' => $byDate,
        'events' => $items, // ini buat list bawah biar sama persis
        'tz'     => $tz,
    ]);
}

    public function riwayat(Request $request): View
    {
        $pendaftaranKegiatans = PendaftaranKegiatan::with('kegiatan')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('front.kegiatans.riwayat', compact('pendaftaranKegiatans'));
    }
}
