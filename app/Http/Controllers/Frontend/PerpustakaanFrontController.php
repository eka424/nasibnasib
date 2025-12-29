<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PerpustakaanFrontController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $viewMode = $request->input('view', 'grid');
        $activeTab = $request->input('tab', 'all');

        $query = Perpustakaan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('kategori', $category);
        }

        if ($activeTab === 'featured' && Schema::hasColumn('perpustakaans', 'is_featured')) {
            $query->where('is_featured', true);
        }

        $perpustakaans = $query->latest()->paginate(12)->withQueryString();

        $categories = Perpustakaan::select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->get();

        $totalBooks = Perpustakaan::count();
        $totalCategories = $categories->count();
        $totalViews = Schema::hasColumn('perpustakaans', 'view_count')
            ? Perpustakaan::sum('view_count')
            : 0;
        $totalDownloads = Schema::hasColumn('perpustakaans', 'download_count')
            ? Perpustakaan::sum('download_count')
            : 0;

        return view('front.perpustakaan.index', compact(
            'perpustakaans',
            'categories',
            'search',
            'category',
            'viewMode',
            'activeTab',
            'totalBooks',
            'totalCategories',
            'totalViews',
            'totalDownloads'
        ));
    }

    public function show(Perpustakaan $perpustakaan): View
    {
        return view('front.perpustakaan.show', compact('perpustakaan'));
    }
}
