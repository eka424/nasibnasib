<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanDaftarRequest;
use App\Models\Kegiatan;
use App\Models\PendaftaranKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $kegiatan->load('pendaftarans');

        return view('front.kegiatans.show', compact('kegiatan'));
    }

    public function daftar(KegiatanDaftarRequest $request, Kegiatan $kegiatan): RedirectResponse
    {
        $user = $request->user();
        $this->authorize('create', PendaftaranKegiatan::class);

        PendaftaranKegiatan::firstOrCreate([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan->id,
        ]);

        return back()->with('success', 'Pendaftaran kegiatan berhasil, silakan menunggu konfirmasi.');
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
