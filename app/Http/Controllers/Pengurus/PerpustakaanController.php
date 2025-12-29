<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerpustakaanController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Perpustakaan::class);

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

        $perpustakaans = $perpustakaanQuery->latest()->paginate(8)->withQueryString();

        $now = now();
        $totalTitles = Perpustakaan::count();
        $newThisMonth = Perpustakaan::whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count();
        $totalCopies = Perpustakaan::sum('stok_total');
        $borrowedCopies = Perpustakaan::select('stok_total', 'stok_tersedia')
            ->get()
            ->sum(fn ($book) => max($book->stok_total - $book->stok_tersedia, 0));

        $stats = [
            'titles' => $totalTitles,
            'new_titles' => $newThisMonth,
            'copies' => $totalCopies,
            'borrowed' => $borrowedCopies,
            'available' => max($totalCopies - $borrowedCopies, 0),
        ];

        $kategoriOptions = Perpustakaan::query()
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values()
            ->all();

        return view('admin.pengurus-perpustakaan', [
            'perpustakaans' => $perpustakaans,
            'filters' => $filters,
            'stats' => $stats,
            'kategoriOptions' => $kategoriOptions,
        ]);
    }
}
