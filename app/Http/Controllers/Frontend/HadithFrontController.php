<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HadithFrontController extends Controller
{
    // BASE API (CDN JSON)
    private string $base = 'https://cdn.jsdelivr.net/gh/fawazahmed0/hadith-api@1';

    // list kitab (books)
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        // list editions
        $res = Http::timeout(20)->get($this->base . '/editions.min.json');
        abort_unless($res->ok(), 502, 'Gagal mengambil daftar kitab hadits.');

        $editions = $res->json() ?? [];
        // bentuknya biasanya: { "eng-bukhari": "...", "ara-muslim": "...", ... }
        $books = collect($editions)
            ->map(function ($label, $key) {
                return ['book' => $key, 'label' => $label];
            })
            ->values();

        if ($q !== '') {
            $qq = mb_strtolower($q);
            $books = $books->filter(fn ($b) =>
                str_contains(mb_strtolower($b['book']), $qq) ||
                str_contains(mb_strtolower((string)$b['label']), $qq)
            )->values();
        }

        return view('hadits.index', [
            'books' => $books,
            'q' => $q,
        ]);
    }

    // klik kitab -> list nomor (paginate by manual range)
    public function book(Request $request, string $book)
    {
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 50;

        // kita butuh total hadits (ambil metadata)
        $metaRes = Http::timeout(20)->get($this->base . '/editions/' . $book . '/info.json');
        abort_unless($metaRes->ok(), 502, 'Kitab tidak ditemukan / metadata gagal.');

        $meta = $metaRes->json() ?? [];
        $total = (int) ($meta['hadiths'] ?? $meta['total_hadith'] ?? 0);

        // fallback kalau metadata tidak ada total: kasih 300 dulu biar tetap jalan
        if ($total <= 0) $total = 300;

        $start = (($page - 1) * $perPage) + 1;
        $end = min($total, $start + $perPage - 1);

        $numbers = range($start, $end);

        return view('hadits.book', compact('book', 'meta', 'numbers', 'page', 'perPage', 'total'));
    }

    // detail hadits by nomor
    public function show(Request $request, string $book, int $no)
    {
        // endpoint detail 1 hadits:
        // /editions/{book}/{no}.json
        $res = Http::timeout(20)->get($this->base . '/editions/' . $book . '/' . $no . '.json');
        abort_unless($res->ok(), 502, 'Hadits tidak ditemukan.');

        $data = $res->json() ?? [];

        return view('hadits.show', [
            'book' => $book,
            'no' => $no,
            'data' => $data,
        ]);
    }
}
