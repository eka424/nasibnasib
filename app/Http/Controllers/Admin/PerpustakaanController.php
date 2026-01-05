<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerpustakaanRequest;
use App\Models\Perpustakaan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PerpustakaanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
        $this->authorizeResource(Perpustakaan::class, 'perpustakaan');
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'status', 'kategori']);

        $perpustakaanQuery = Perpustakaan::query();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $perpustakaanQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($kategori = $filters['kategori'] ?? null) {
            $perpustakaanQuery->where('kategori', $kategori);
        }

        if ($status = $filters['status'] ?? null) {
            if ($status === 'available') {
                $perpustakaanQuery->where('stok_tersedia', '>', 0);
            } elseif ($status === 'borrowed') {
                $perpustakaanQuery->where('stok_tersedia', '<=', 0);
            }
        }

        $perpustakaans = $perpustakaanQuery->latest()->paginate(10)->withQueryString();

        $now = now();
        $totalTitles = Perpustakaan::count();
        $newThisMonth = Perpustakaan::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();

        $totalCopies = Perpustakaan::sum('stok_total');
        $borrowedCopies = Perpustakaan::select('stok_total', 'stok_tersedia')
            ->get()
            ->sum(fn ($book) => max($book->stok_total - $book->stok_tersedia, 0));

        $memberCount = User::role('jamaah')->count();
        $newMembers = User::role('jamaah')
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();

        $stats = [
            'titles' => [
                'label' => 'Total Judul Buku',
                'value' => $totalTitles,
                'description' => '+' . $newThisMonth . ' judul baru bulan ini',
                'icon' => 'library',
            ],
            'copies' => [
                'label' => 'Total Eksemplar',
                'value' => $totalCopies,
                'description' => 'Total semua kopi buku',
                'icon' => 'clipboard',
            ],
            'borrowed' => [
                'label' => 'Buku Dipinjam',
                'value' => $borrowedCopies,
                'description' => 'Sedang dalam peminjaman',
                'icon' => 'hourglass',
            ],
            'members' => [
                'label' => 'Anggota Terdaftar',
                'value' => $memberCount,
                'description' => '+ ' . $newMembers . ' anggota baru',
                'icon' => 'users',
            ],
        ];

        $kategoriOptions = Perpustakaan::query()
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values()
            ->all();

        $statusOptions = [
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
        ];

        return view('admin.perpustakaans.index', [
            'perpustakaans' => $perpustakaans,
            'filters' => $filters,
            'stats' => $stats,
            'kategoriOptions' => $kategoriOptions,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function create(): View
    {
        return view('admin.perpustakaans.create');
    }

    public function store(PerpustakaanRequest $request): RedirectResponse
    {
        Perpustakaan::create($this->prepareData($request));

        return redirect()
            ->route('admin.perpustakaans.index')
            ->with('success', 'Item perpustakaan disimpan.');
    }

    public function show(Perpustakaan $perpustakaan): View
    {
        return view('admin.perpustakaans.show', compact('perpustakaan'));
    }

    public function edit(Perpustakaan $perpustakaan): View
    {
        return view('admin.perpustakaans.edit', compact('perpustakaan'));
    }

    public function update(PerpustakaanRequest $request, Perpustakaan $perpustakaan): RedirectResponse
    {
        $perpustakaan->update($this->prepareData($request, $perpustakaan));

        return redirect()
            ->route('admin.perpustakaans.index')
            ->with('success', 'Item perpustakaan diperbarui.');
    }

    public function destroy(Perpustakaan $perpustakaan): RedirectResponse
    {
        // kalau data lama masih lokal, tetap bersihin
        $this->deleteFileIfLocal($perpustakaan->file_ebook);
        $this->deleteFileIfLocal($perpustakaan->cover);

        $perpustakaan->delete();

        return redirect()
            ->route('admin.perpustakaans.index')
            ->with('success', 'Item perpustakaan dihapus.');
    }

    protected function prepareData(PerpustakaanRequest $request, ?Perpustakaan $perpustakaan = null): array
    {
        $data = $request->safe()->except(['file_ebook', 'cover', 'file_url', 'cover_url']);

        $data['stok_total'] = max(0, (int) ($data['stok_total'] ?? ($perpustakaan->stok_total ?? 0)));
        $data['stok_tersedia'] = max(
            0,
            min(
                (int) ($data['stok_tersedia'] ?? ($perpustakaan->stok_tersedia ?? $data['stok_total'])),
                $data['stok_total']
            )
        );

        /**
         * ===== Ebook (PDF/Link) =====
         * Prioritas:
         * 1) file upload
         * 2) file_url (drive -> /preview)
         * 3) fallback value lama
         */
        if ($request->hasFile('file_ebook')) {
            if ($perpustakaan) {
                $this->deleteFileIfLocal($perpustakaan->file_ebook);
            }
            $data['file_ebook'] = $request->file('file_ebook')->store('ebooks', 'public');
        } elseif ($request->filled('file_url')) {
            $data['file_ebook'] = $this->normalizeDrivePdfPreview((string) $request->string('file_url'));
        } elseif ($perpustakaan) {
            $data['file_ebook'] = $perpustakaan->file_ebook;
        }

        /**
         * ===== Cover =====
         * Prioritas:
         * 1) upload
         * 2) cover_url (drive -> thumbnail)
         * 3) fallback value lama
         */
        if ($request->hasFile('cover')) {
            if ($perpustakaan) {
                $this->deleteFileIfLocal($perpustakaan->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        } elseif ($request->filled('cover_url')) {
            $data['cover'] = $this->normalizeDriveImageThumb((string) $request->string('cover_url'));
        } elseif ($perpustakaan) {
            $data['cover'] = $perpustakaan->cover;
        }

        return $data;
    }

    /**
     * Google Drive PDF:
     * - file/d/{id}/view -> file/d/{id}/preview (buat iframe)
     */
    protected function normalizeDrivePdfPreview(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') return null;

        // sudah preview
        if (str_contains($url, 'drive.google.com') && str_contains($url, '/preview')) {
            return $url;
        }

        $id = $this->extractDriveId($url);
        if ($id) {
            return "https://drive.google.com/file/d/{$id}/preview";
        }

        // bukan drive -> biarin
        return $url;
    }

    /**
     * Google Drive Image:
     * - lebih stabil pakai thumbnail endpoint
     *   https://drive.google.com/thumbnail?id=FILE_ID&sz=w1200
     */
    protected function normalizeDriveImageThumb(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') return null;

        // sudah thumbnail
        if (str_contains($url, 'drive.google.com/thumbnail')) {
            return $url;
        }

        $id = $this->extractDriveId($url);
        if ($id) {
            return "https://drive.google.com/thumbnail?id={$id}&sz=w1200";
        }

        // bukan drive -> biarin
        return $url;
    }

    /**
     * Ambil file id dari banyak format URL Google Drive
     */
    protected function extractDriveId(string $url): ?string
    {
        // file/d/{id}
        if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) {
            return $m[1];
        }

        // open?id={id}
        if (preg_match('~drive\.google\.com/open\?id=([^&]+)~', $url, $m)) {
            return $m[1];
        }

        // ...?id={id}
        if (str_contains($url, 'drive.google.com') && preg_match('~[?&]id=([^&]+)~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    protected function deleteFileIfLocal(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
