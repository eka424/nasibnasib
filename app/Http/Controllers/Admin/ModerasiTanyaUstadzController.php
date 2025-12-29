<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PertanyaanAssignRequest;
use App\Models\PertanyaanUstadz;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerasiTanyaUstadzController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin|pengurus']);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PertanyaanUstadz::class);

        $filters = $request->only(['q', 'status', 'kategori', 'ustadz']);

        $query = PertanyaanUstadz::with(['penanya', 'ustadz'])->latest();

        if ($search = trim((string) ($filters['q'] ?? ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                    ->orWhereHas('penanya', function ($penanya) use ($search) {
                        $penanya->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $filters['status'] ?? null) {
            $query->where('status', $status);
        }

        if ($kategori = $filters['kategori'] ?? null) {
            $query->where('kategori', $kategori);
        }

        if ($ustadzId = $filters['ustadz'] ?? null) {
            $query->where('ustadz_id', $ustadzId === 'unassigned' ? null : $ustadzId);
        }

        $pertanyaans = $query->paginate(10)->withQueryString();
        $ustadzs = User::where('role', 'ustadz')->orderBy('name')->get();

        $totalQuestions = PertanyaanUstadz::count();
        $pendingQuestions = PertanyaanUstadz::where('status', 'menunggu')->count();
        $answeredQuestions = PertanyaanUstadz::where('status', 'dijawab')->count();
        $uniqueQuestioners = PertanyaanUstadz::distinct('user_id')->count('user_id');
        $newThisMonth = PertanyaanUstadz::where('created_at', '>=', now()->startOfMonth())->count();

        $stats = [
            [
                'label' => 'Total Pertanyaan',
                'value' => $totalQuestions,
                'description' => '+' . $newThisMonth . ' pertanyaan baru',
                'icon' => 'question',
            ],
            [
                'label' => 'Belum Dijawab',
                'value' => $pendingQuestions,
                'description' => 'Menunggu respons ustadz',
                'icon' => 'hourglass',
            ],
            [
                'label' => 'Sudah Dijawab',
                'value' => $answeredQuestions,
                'description' => 'Telah selesai dijawab',
                'icon' => 'check-circle',
            ],
            [
                'label' => 'Penanya Unik',
                'value' => $uniqueQuestioners,
                'description' => 'Total jamaah yang bertanya',
                'icon' => 'users',
            ],
        ];

        $kategoriOptions = $this->categories();
        $statusOptions = [
            'menunggu' => 'Belum Dijawab',
            'dijawab' => 'Sudah Dijawab',
        ];

        return view('admin.moderasi-pertanyaan.index', [
            'pertanyaans' => $pertanyaans,
            'ustadzs' => $ustadzs,
            'stats' => $stats,
            'filters' => $filters,
            'kategoriOptions' => $kategoriOptions,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function assign(PertanyaanAssignRequest $request, PertanyaanUstadz $pertanyaan): RedirectResponse
    {
        $this->authorize('update', $pertanyaan);

        $pertanyaan->update([
            'ustadz_id' => $request->validated('ustadz_id'),
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditugaskan.');
    }

    public function destroy(PertanyaanUstadz $pertanyaan): RedirectResponse
    {
        $this->authorize('delete', $pertanyaan);
        $pertanyaan->delete();

        return back()->with('success', 'Pertanyaan dihapus.');
    }

    protected function categories(): array
    {
        return [
            'aqidah' => 'Aqidah',
            'fiqih' => 'Fiqih',
            'akhlak' => 'Akhlak',
            'muamalah' => 'Muamalah',
            'keluarga' => 'Keluarga',
            'umum' => 'Umum',
        ];
    }
}
