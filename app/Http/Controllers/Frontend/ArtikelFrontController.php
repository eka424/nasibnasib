<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Donasi;
use App\Models\Kegiatan;
use App\Services\PrayerTimesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtikelFrontController extends Controller
{
    public function home(PrayerTimesService $prayer): View
    {
        $artikels = Artikel::where('status', 'published')->latest()->take(3)->get();
        $kegiatans = Kegiatan::orderBy('tanggal_mulai')->take(3)->get();
        $donasis = Donasi::latest()->take(3)->get();

        $stats = [
            [
                'label' => 'Kajian & Kegiatan',
                'value' => Kegiatan::count() . '+',
                'icon' => '📅',
            ],
            [
                'label' => 'Artikel Terbit',
                'value' => Artikel::where('status', 'published')->count() . '+',
                'icon' => '📰',
            ],
            [
                'label' => 'Jamaah Terdaftar',
                'value' => number_format(\App\Models\User::where('role', 'jamaah')->count()),
                'icon' => '👥',
            ],
            [
                'label' => 'Program Donasi',
                'value' => Donasi::count() . '+',
                'icon' => '🤝',
            ],
        ];

        // ✅ WITA (Bali)
        $tz = 'Asia/Makassar';
        $todayLabel = Carbon::now($tz)->locale('id')->translatedFormat('l, d F Y');

        // ✅ Ambil jadwal sholat dari API untuk KAB. GIANYAR (1704)
        $result = $prayer->getDaily('1704', Carbon::now($tz));
        $jadwalSholat = ($result['ok'] ?? false) ? ($result['times'] ?? []) : [];

        return view('front.home', compact(
            'artikels',
            'kegiatans',
            'donasis',
            'stats',
            'todayLabel',
            'jadwalSholat'
        ));
    }

    public function index(Request $request): View
    {
        $artikelsQuery = Artikel::where('status', 'published');

        if ($search = $request->input('q')) {
            $artikelsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $artikels = $artikelsQuery->latest()->paginate(9)->withQueryString();

        return view('front.artikels.index', compact('artikels'));
    }

    public function show(Artikel $artikel): View
    {
        abort_if($artikel->status !== 'published', 404);

        return view('front.artikels.show', compact('artikel'));
    }
}
