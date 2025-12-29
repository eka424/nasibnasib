<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\TanyaUstadzRequest;
use App\Mail\PertanyaanBaruMail;
use App\Models\PertanyaanUstadz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TanyaUstadzFrontController extends Controller
{
    public function index(): View
    {
        $pertanyaans = PertanyaanUstadz::where('status', 'dijawab')
            ->latest()
            ->paginate(10);

        $categories = $this->categories();

        return view('front.tanya-ustadz.index', compact('pertanyaans', 'categories'));
    }

    public function my(Request $request): View
    {
        $pertanyaans = PertanyaanUstadz::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('front.tanya-ustadz.my', compact('pertanyaans'));
    }

    public function store(TanyaUstadzRequest $request): RedirectResponse
    {
        $this->authorize('create', PertanyaanUstadz::class);

        $pertanyaan = PertanyaanUstadz::create([
            'user_id' => $request->user()->id,
            'kategori' => $request->validated('kategori'),
            'pertanyaan' => $request->validated('pertanyaan'),
            'status' => 'menunggu',
        ]);

        $this->notifyAdmins($pertanyaan);

        return back()->with('success', 'Pertanyaan berhasil dikirim.');
    }

    protected function notifyAdmins(PertanyaanUstadz $pertanyaan): void
    {
        $targetEmail = config('mail.tanya_ustadz_notify');

        if (! $targetEmail) {
            return;
        }

        Mail::to($targetEmail)->queue(new PertanyaanBaruMail($pertanyaan));
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
