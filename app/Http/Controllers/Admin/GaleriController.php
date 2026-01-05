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

        if (($kategori = $filters['kategori'] ?? null) !== null && $kategori !== '') {
            $galeriQuery->where('kategori', $kategori);
        }

        if (($seksi = $filters['seksi'] ?? null) !== null && $seksi !== '') {
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

        return redirect()
            ->route('admin.galeris.index')
            ->with('success', 'Galeri berhasil disimpan ✅');
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
        // kalau sebelumnya masih ada file lokal lama, biar bersih
        if ($galeri->url_file && ! str_starts_with($galeri->url_file, 'http')) {
            $this->deleteAsset($galeri->url_file);
        }

        $galeri->update($this->prepareData($request, $galeri));

        return redirect()
            ->route('admin.galeris.index')
            ->with('success', 'Galeri berhasil diperbarui ✅');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $this->deleteAsset($galeri->url_file);
        $galeri->delete();

        return redirect()
            ->route('admin.galeris.index')
            ->with('success', 'Galeri berhasil dihapus ✅');
    }

    protected function prepareData(GaleriRequest $request, ?Galeri $galeri = null): array
    {
        // ambil data yang memang kita simpan
        $data = $request->safe()->only([
            'judul', 'deskripsi', 'tipe', 'kategori', 'seksi', 'url_file',
        ]);

        $data['seksi'] = $request->filled('seksi') ? $request->input('seksi') : null;

        // ✅ PAKSA url_file terset + dinormalisasi
        $rawUrl = trim((string) $request->input('url_file'));

        if (($data['tipe'] ?? null) === 'image') {
            $data['url_file'] = $this->normalizeDriveImageThumb($rawUrl);
        } else {
            // video
            $data['url_file'] = $this->normalizeVideoUrl($rawUrl);
        }

        return $data;
    }

    protected function normalizeVideoUrl(string $url): string
    {
        // youtube -> embed
        $yt = $this->normalizeYoutubeEmbed($url);
        if ($yt) return $yt;

        // drive -> preview
        $driveId = $this->extractDriveId($url);
        if ($driveId) {
            return "https://drive.google.com/file/d/{$driveId}/preview";
        }

        // fallback (misal link mp4 langsung)
        return $url;
    }

    protected function normalizeYoutubeEmbed(string $url): ?string
    {
        $url = trim($url);

        // youtu.be/{id}
        if (preg_match('~youtu\.be/([a-zA-Z0-9_-]{6,})~', $url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        // youtube.com/watch?v={id}
        if (preg_match('~youtube\.com/watch\?v=([a-zA-Z0-9_-]{6,})~', $url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        // youtube.com/shorts/{id}
        if (preg_match('~youtube\.com/shorts/([a-zA-Z0-9_-]{6,})~', $url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}";
        }

        // already embed
        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        return null;
    }

    protected function normalizeDriveImageThumb(string $url): string
    {
        $url = trim($url);

        // kalau sudah thumbnail
        if (str_contains($url, 'drive.google.com/thumbnail')) return $url;

        $id = $this->extractDriveId($url);
        if ($id) {
            return "https://drive.google.com/thumbnail?id={$id}&sz=w1200";
        }

        // fallback
        return $url;
    }

    protected function extractDriveId(string $url): ?string
    {
        // file/d/{id}/...
        if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) {
            return $m[1];
        }

        // open?id={id}
        if (preg_match('~drive\.google\.com/open\?id=([^&]+)~', $url, $m)) {
            return $m[1];
        }

        // ...?id={id}
        if (preg_match('~[?&]id=([^&]+)~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    protected function deleteAsset(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
