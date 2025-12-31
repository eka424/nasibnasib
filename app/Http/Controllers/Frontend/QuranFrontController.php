<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuranFrontController extends Controller
{
    public function index(Request $request)
    {
        // optional: kalau kamu butuh halaman /alquran list surah
        try {
            $resp = Http::timeout(15)->get('https://equran.id/api/v2/surat');
            $surahs = $resp->successful() ? ($resp->json()['data'] ?? []) : [];
            $error = $resp->successful() ? null : 'HTTP ' . $resp->status();
        } catch (\Throwable $e) {
            $surahs = [];
            $error = $e->getMessage();
        }

        return view('alquran.index', compact('surahs', 'error'));
    }

    public function show(int $surah)
    {
        // detail surah + ayat
        try {
            $resp = Http::timeout(20)->get("https://equran.id/api/v2/surat/{$surah}");

            if ($resp->failed()) {
                return view('alquran.show', [
                    'error' => 'HTTP ' . $resp->status(),
                    'surah' => $surah,
                    'data'  => null,
                ]);
            }

            $json = $resp->json();
            $data = $json['data'] ?? null;

            return view('alquran.show', [
                'error' => null,
                'surah' => $surah,
                'data'  => $data,
            ]);
        } catch (\Throwable $e) {
            return view('alquran.show', [
                'error' => $e->getMessage(),
                'surah' => $surah,
                'data'  => null,
            ]);
        }
    }
}
