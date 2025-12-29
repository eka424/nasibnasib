<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Http\Requests\PertanyaanJawabRequest;
use App\Models\PertanyaanUstadz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class PertanyaanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:ustadz']);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $kategori = $request->string('kategori')->toString();
        $userId = $request->user()->id;

        $pertanyaans = PertanyaanUstadz::with('penanya')
            ->where('ustadz_id', $userId)
            ->where('status', 'menunggu')
            ->when($search, fn ($q) => $q->where(function ($sub) use ($search) {
                $sub->where('pertanyaan', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            }))
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalDitugaskan = PertanyaanUstadz::where('ustadz_id', $userId)->count();
        $totalMenunggu = PertanyaanUstadz::where('ustadz_id', $userId)->where('status', 'menunggu')->count();
        $totalDijawab = PertanyaanUstadz::where('ustadz_id', $userId)->where('status', 'dijawab')->count();

        $avgResponseHours = PertanyaanUstadz::where('ustadz_id', $userId)
            ->where('status', 'dijawab')
            ->whereNotNull('jawaban')
            ->get()
            ->avg(function (PertanyaanUstadz $p): ?float {
                if (! $p->created_at || ! $p->updated_at) {
                    return null;
                }

                return $p->updated_at->diffInMinutes($p->created_at) / 60;
            });

        $stats = [
            'total' => $totalDitugaskan,
            'waiting' => $totalMenunggu,
            'answered' => $totalDijawab,
            'avg_hours' => $avgResponseHours ? round($avgResponseHours, 1) : null,
        ];

        $categoryOptions = [
            'aqidah',
            'ibadah',
            'fiqih',
            'muamalah',
            'keluarga',
            'akhlak',
        ];

        return view('ustadz.pertanyaan.index', compact('pertanyaans', 'stats', 'search', 'kategori', 'categoryOptions'));
    }

    public function riwayat(Request $request): View
    {
        $pertanyaans = PertanyaanUstadz::with('penanya')
            ->where('ustadz_id', $request->user()->id)
            ->where('status', 'dijawab')
            ->latest()
            ->paginate(10);

        return view('ustadz.pertanyaan.riwayat', compact('pertanyaans'));
    }

    public function edit(PertanyaanUstadz $pertanyaan): View
    {
        $this->authorize('update', $pertanyaan);

        return view('ustadz.pertanyaan.edit', compact('pertanyaan'));
    }

    public function update(PertanyaanJawabRequest $request, PertanyaanUstadz $pertanyaan): RedirectResponse
    {
        $this->authorize('update', $pertanyaan);

        $pertanyaan->update([
            'jawaban' => $request->validated('jawaban'),
            'status' => 'dijawab',
        ]);

        return redirect()->route('ustadz.pertanyaan.index')->with('success', 'Jawaban tersimpan.');
    }
}
