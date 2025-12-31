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
    // Peta seksi (samakan dengan frontend)
    private array $sectionsMap = [
        'idarah' => [
            'SEKSI DOKUMENTASI, PERPUSTAKAAN DAN PENERBITAN',
            'SEKSI HUMAS, INFORMASI DAN KOMUNIKASI',
            'SEKSI PERIBADATAN',
            'Lainnya',
        ],
        'imarah' => [
            'SEKSI PENDIDIKAN, PELATIHAN DAN KADERISASI',
            'SEKSI PEMBERDAYAAN PEREMPUAN DAN SOSIAL',
            'SEKSI KESEHATAN',
            'SEKSI PEREKONOMIAN',
            'Lainnya',
        ],
        'riayah' => [
            'SEKSI PEMBANGUNAN, PEMELIHARAAN DAN PERAWATAN',
            'SEKSI KEAMANAN, KETERTIBAN DAN KEBERSIHAN',
            'Lainnya',
        ],
    ];

    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Galeri::class, 'galeri');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'tipe', 'sort', 'kategori', 'seksi']);

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

        // filter baru (optional)
        if ($kategori = $filters['kategori'] ?? null) {
            $galeriQuery->where('kategori', $kategori);
        }
        if ($seksi = $filters['seksi'] ?? null) {
            $galeriQuery->where('seksi', $seksi);
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

        $deptOptions = [
            '' => 'Semua Kategori',
            'idarah' => 'Idarah',
            'imarah' => 'Imarah',
            'riayah' => 'Riayah',
        ];

        // untuk filter seksi di index (optional)
        $sectionsMap = $this->sectionsMap;

        return view('admin.galeris.index', compact(
            'galeris',
            'filters',
            'stats',
            'typeOptions',
            'sortOptions',
            'deptOptions',
            'sectionsMap'
        ));
    }

    public function create(): View
    {
        $sectionsMap = $this->sectionsMap;
        $deptOptions = [
            'idarah' => 'Idarah',
            'imarah' => 'Imarah',
            'riayah' => 'Riayah',
        ];

        return view('admin.galeris.create', compact('sectionsMap', 'deptOptions'));
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
        $sectionsMap = $this->sectionsMap;
        $deptOptions = [
            'idarah' => 'Idarah',
            'imarah' => 'Imarah',
            'riayah' => 'Riayah',
        ];

        return view('admin.galeris.edit', compact('galeri', 'sectionsMap', 'deptOptions'));
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

        // Normalisasi seksi: kalau kosong, set null (atau bisa default "Lainnya")
        $data['seksi'] = $request->filled('seksi') ? $request->input('seksi') : null;

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
