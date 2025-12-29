<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Artikel::class);

        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();
        $user = $request->user();

        $baseQuery = Artikel::with('user')
            ->when(!$user->isAdmin(), fn ($q) => $q->where('user_id', $user->id));

        $totalArtikel = (clone $baseQuery)->count();
        $publishedArtikel = (clone $baseQuery)->where('status', 'published')->count();
        $draftArtikel = (clone $baseQuery)->where('status', 'draft')->count();
        $latestArtikel = (clone $baseQuery)->latest()->first();

        $artikels = (clone $baseQuery)
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.pengurus-artikel', [
            'artikels' => $artikels,
            'totalArtikel' => $totalArtikel,
            'publishedArtikel' => $publishedArtikel,
            'draftArtikel' => $draftArtikel,
            'latestArtikel' => $latestArtikel,
            'search' => $search,
            'statusFilter' => $status,
        ]);
    }
}
