<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PerpustakaanIntegratedController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'books'); // books|quran|hadits
        $q   = $request->get('q', '');

        // 1) BOOKS (DB)
        $books = Perpustakaan::query()
            ->when($q && $tab === 'books', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('judul', 'like', "%{$q}%")
                      ->orWhere('penulis', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%")
                      ->orWhere('kategori', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // 2) QURAN (EQuran API v2)
        $surahs = [];
        $quranError = null;

        if ($tab === 'quran') {
            try {
                $resp = Http::timeout(10)->get('https://equran.id/api/v2/surat');

                if ($resp->failed()) {
                    $quranError = 'HTTP ' . $resp->status();
                } else {
                    $json = $resp->json();
                    // Struktur equran: { code, message, data: [...] }
                    $surahs = $json['data'] ?? [];
                }
            } catch (\Throwable $e) {
                $quranError = $e->getMessage();
            }
        }

        // 3) HADITS (pakai api.qalbun.my.id biar gampang & konsisten)
        $narrators = [];
        $hadithError = null;

        if ($tab === 'hadits') {
            try {
                // Qalbun: /api/hadits (list kitab)
                $resp = Http::timeout(10)->get('https://api.qalbun.my.id/api/hadits');

                if ($resp->failed()) {
                    $hadithError = 'HTTP ' . $resp->status();
                } else {
                    $json = $resp->json();
                    // Normalisasi: ambil array dari 'data' kalau ada, kalau enggak ambil root
                    $narrators = $json['data'] ?? $json ?? [];
                }
            } catch (\Throwable $e) {
                $hadithError = $e->getMessage();
            }
        }

        return view('perpustakaan.integrated', compact(
            'tab',
            'q',
            'books',
            'surahs',
            'quranError',
            'narrators',
            'hadithError'
        ));
    }
}
