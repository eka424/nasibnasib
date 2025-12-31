<?php

namespace App\Http\Controllers;

use App\Models\KidsContent;
use Illuminate\Http\Request;

class KidsLibraryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $base = KidsContent::query()
            ->where('is_published', true);

        if ($q) {
            $base->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('youtube_url', 'like', "%{$q}%")
                  ->orWhere('youtube_id', 'like', "%{$q}%")
                  ->orWhere('quote_text', 'like', "%{$q}%")
                  ->orWhere('quote_source', 'like', "%{$q}%");
            });
        }

        $videos = (clone $base)->where('type', 'video')->orderBy('sort_order')->latest()->get();
        $dongeng = (clone $base)->where('type', 'story')->orderBy('sort_order')->latest()->get(); // ✅ story (PDF)
        $kata = (clone $base)->where('type', 'quote')->orderBy('sort_order')->latest()->get();   // ✅ quote (text)

        $stats = [
            'video' => (clone $base)->where('type', 'video')->count(),
            'dongeng' => (clone $base)->where('type', 'story')->count(),
            'kata' => (clone $base)->where('type', 'quote')->count(),
        ];

        return view('perpustakaan.ramah-anak', compact('videos', 'dongeng', 'kata', 'stats', 'q'));
    }
}
