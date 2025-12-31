<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KidsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KidsContentController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $type = $request->get('type'); // video|story|quote|null

        $query = KidsContent::query();

        if ($type) {
            $query->where('type', $type);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('youtube_url', 'like', "%{$q}%")
                    ->orWhere('youtube_id', 'like', "%{$q}%")
                    ->orWhere('quote_text', 'like', "%{$q}%")
                    ->orWhere('quote_source', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('sort_order')->latest()->paginate(10)->withQueryString();

        return view('admin.kids.index', compact('items', 'q', 'type'));
    }

    public function create(Request $request)
    {
        // biar create bisa dipanggil per tipe: /admin/ramah-anak/create?type=story
        $type = $request->get('type', 'video');
        $mode = 'create';
        $item = new KidsContent();

        return view('admin.kids.form', compact('type', 'mode', 'item'));
    }

    public function store(Request $request)
    {
        $type = $request->input('type');

        $rules = [
            'type' => 'required|in:video,story,quote',
            'title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
        ];

        if ($type === 'video') {
            $rules['youtube_url'] = 'required|string|max:255';
        } elseif ($type === 'story') {
            $rules['pdf'] = 'required|file|mimes:pdf|max:10240'; // 10MB
        } elseif ($type === 'quote') {
            $rules['quote_text'] = 'required|string';
            $rules['quote_source'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $data = $this->mapPayload($request, $type, null);

        KidsContent::create($data);

        return redirect()->route('admin.kids.index', ['type' => $type])
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    public function edit(KidsContent $kids)
    {
        $type = $kids->type;
        $mode = 'edit';
        $item = $kids;

        return view('admin.kids.form', compact('type', 'mode', 'item'));
    }

    public function update(Request $request, KidsContent $kids)
    {
        $type = $kids->type;

        $rules = [
            'title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
        ];

        if ($type === 'video') {
            $rules['youtube_url'] = 'required|string|max:255';
        } elseif ($type === 'story') {
            // edit: pdf optional
            $rules['pdf'] = 'nullable|file|mimes:pdf|max:10240';
        } elseif ($type === 'quote') {
            $rules['quote_text'] = 'required|string';
            $rules['quote_source'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $data = $this->mapPayload($request, $type, $kids);

        $kids->update($data);

        return redirect()->route('admin.kids.index', ['type' => $type])
            ->with('success', 'Konten berhasil diupdate.');
    }

    public function destroy(KidsContent $kids)
    {
        // hapus pdf kalau ada
        if ($kids->pdf_path) {
            Storage::disk('public')->delete($kids->pdf_path);
        }

        $kids->delete();

        return back()->with('success', 'Konten berhasil dihapus.');
    }

    /**
     * Map request payload -> DB columns aman (no mismatch).
     */
    private function mapPayload(Request $request, string $type, ?KidsContent $existing): array
    {
        $title = (string) $request->input('title');
        $slug = $existing?->slug ?: Str::slug($title);

        // pastikan slug unique
        $base = $slug;
        $i = 2;
        while (
            KidsContent::where('slug', $slug)
                ->when($existing, fn($q) => $q->where('id', '!=', $existing->id))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        $data = [
            'type' => $type,
            'title' => $title,
            'slug' => $slug,
            'sort_order' => (int) ($request->input('sort_order', 0) ?? 0),
            'thumbnail' => $request->input('thumbnail'),
            'is_published' => (bool) $request->boolean('is_published'),
        ];

        // reset field type lain biar rapi
        $data['youtube_id'] = null;
        $data['youtube_url'] = null;
        $data['pdf_path'] = $existing?->pdf_path;
        $data['quote_text'] = null;
        $data['quote_source'] = null;

        if ($type === 'video') {
            $ytInput = trim((string) $request->input('youtube_url'));
            $ytId = $this->extractYoutubeId($ytInput);

            $data['youtube_url'] = $ytInput;
            $data['youtube_id'] = $ytId;
        }

        if ($type === 'story') {
            if ($request->hasFile('pdf')) {
                // kalau update & ada pdf lama -> hapus
                if ($existing?->pdf_path) {
                    Storage::disk('public')->delete($existing->pdf_path);
                }

                $path = $request->file('pdf')->store('kids/pdf', 'public');
                $data['pdf_path'] = $path;
            }
        }

        if ($type === 'quote') {
            $data['quote_text'] = $request->input('quote_text');
            $data['quote_source'] = $request->input('quote_source');
        }

        return $data;
    }

    /**
     * Terima input: url youtube / youtu.be / embed / atau ID langsung.
     */
    private function extractYoutubeId(string $input): ?string
    {
        if ($input === '') return null;

        // ID langsung
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }

        // watch?v=
        if (preg_match('/v=([a-zA-Z0-9_-]{11})/', $input, $m)) {
            return $m[1];
        }

        // youtu.be/
        if (preg_match('~youtu\.be/([a-zA-Z0-9_-]{11})~', $input, $m)) {
            return $m[1];
        }

        // embed/
        if (preg_match('~youtube\.com/embed/([a-zA-Z0-9_-]{11})~', $input, $m)) {
            return $m[1];
        }

        return null;
    }
}
