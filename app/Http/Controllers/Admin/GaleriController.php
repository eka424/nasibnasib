<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GaleriRequest;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Galeri::class, 'galeri');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'tipe', 'sort']);

        $galeriQuery = Galeri::query();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $galeriQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($type = $filters['tipe'] ?? null) {
            $galeriQuery->where('tipe', $type);
        }

        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'oldest') {
            $galeriQuery->orderBy('created_at', 'asc');
        } elseif ($sort === 'title') {
            $galeriQuery->orderBy('judul');
        } else {
            $galeriQuery->latest();
        }

        $galeris = $galeriQuery->paginate(12)->withQueryString();

        $now = now();
        $stats = [
            'total' => [
                'label' => 'Total Media',
                'value' => Galeri::count(),
                'description' => '+' . Galeri::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count() . ' media bulan ini',
                'icon' => 'library',
            ],
            'images' => [
                'label' => 'Gambar',
                'value' => Galeri::where('tipe', 'image')->count(),
                'description' => 'Total media gambar',
                'icon' => 'image',
            ],
            'videos' => [
                'label' => 'Video',
                'value' => Galeri::where('tipe', 'video')->count(),
                'description' => 'Total media video',
                'icon' => 'video',
            ],
            'recent' => [
                'label' => 'Unggahan Minggu Ini',
                'value' => Galeri::whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])->count(),
                'description' => 'Media ditambahkan minggu ini',
                'icon' => 'eye',
            ],
        ];

        $typeOptions = [
            '' => 'Semua Tipe',
            'image' => 'Gambar',
            'video' => 'Video',
        ];

        $sortOptions = [
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
            'title' => 'Judul A-Z',
        ];

        return view('admin.galeris.index', compact('galeris', 'filters', 'stats', 'typeOptions', 'sortOptions'));
    }

    public function create(): View
    {
        return view('admin.galeris.create');
    }

    public function store(GaleriRequest $request): RedirectResponse
    {
        Galeri::create($this->prepareData($request));

        return redirect()->route('admin.galeris.index')->with('success', 'Galeri ditambahkan.');
    }

    public function show(Galeri $galeri): View
    {
        return view('admin.galeris.show', compact('galeri'));
    }

    public function edit(Galeri $galeri): View
    {
        return view('admin.galeris.edit', compact('galeri'));
    }

    public function update(GaleriRequest $request, Galeri $galeri): RedirectResponse
    {
        if ($request->hasFile('attachment')) {
            $this->deleteAsset($galeri->url_file);
        }

        $galeri->update($this->prepareData($request, $galeri));

        return redirect()->route('admin.galeris.index')->with('success', 'Galeri diperbarui.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $this->deleteAsset($galeri->url_file);
        $galeri->delete();

        return redirect()->route('admin.galeris.index')->with('success', 'Galeri dihapus.');
    }

    protected function prepareData(GaleriRequest $request, ?Galeri $galeri = null): array
    {
        $data = $request->safe()->except('attachment');

        if ($request->hasFile('attachment')) {
            $data['url_file'] = $request->file('attachment')->store('galeri', 'public');
        } elseif (! $request->filled('url_file') && $galeri) {
            $data['url_file'] = $galeri->url_file;
        }

        return $data;
    }

    protected function deleteAsset(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
