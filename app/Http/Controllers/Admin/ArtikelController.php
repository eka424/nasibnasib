<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArtikelRequest;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Artikel::class, 'artikel');
    }

    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $statusFilter = $request->string('status')->toString();
        $authorFilter = $request->integer('author');

        $artikels = Artikel::with('user')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->when($authorFilter, fn ($query) => $query->where('user_id', $authorFilter))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalArtikel = Artikel::count();
        $publishedArtikel = Artikel::where('status', 'published')->count();
        $draftArtikel = Artikel::where('status', 'draft')->count();
        $authorCount = Artikel::distinct('user_id')->count('user_id');

        $authors = User::whereIn('id', Artikel::select('user_id'))
            ->orderBy('name')
            ->get();

        return view('admin.artikels.index', [
            'artikels' => $artikels,
            'totalArtikel' => $totalArtikel,
            'publishedArtikel' => $publishedArtikel,
            'draftArtikel' => $draftArtikel,
            'authorCount' => $authorCount,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'authorFilter' => $authorFilter,
            'authors' => $authors,
        ]);
    }

    public function create(): View
    {
        return view('admin.artikels.create');
    }

    public function store(ArtikelRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['thumbnail'] = $this->storeThumbnail($request);

        Artikel::create($data);

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function show(Artikel $artikel): View
    {
        return view('admin.artikels.show', compact('artikel'));
    }

    public function edit(Artikel $artikel): View
    {
        return view('admin.artikels.edit', compact('artikel'));
    }

    public function update(ArtikelRequest $request, Artikel $artikel): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($artikel->thumbnail);
            $data['thumbnail'] = $this->storeThumbnail($request);
        }

        $artikel->update($data);

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Artikel $artikel): RedirectResponse
    {
        $this->deleteThumbnail($artikel->thumbnail);
        $artikel->delete();

        return redirect()->route('admin.artikels.index')->with('success', 'Artikel dihapus.');
    }

    protected function storeThumbnail(ArtikelRequest $request): ?string
    {
        return $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('thumbnails', 'public')
            : null;
    }

    protected function deleteThumbnail(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
