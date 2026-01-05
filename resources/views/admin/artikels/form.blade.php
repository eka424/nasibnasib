@csrf
@if(isset($artikel) && $artikel->exists)
    @method('PUT')
@endif

@php
    $thumbNow = old('thumbnail_url') ?: ($artikel->thumbnail ?? '');

    $isUrl = is_string($thumbNow) && preg_match('~^https?://~i', $thumbNow);

    $preview = null;
    if (!empty($thumbNow)) {
        if ($isUrl) {
            $preview = $thumbNow;
        } else {
            // kalau path local, tampilkan via /storage
            $path = ltrim($thumbNow, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }
            $preview = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }
    }
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="title" value="{{ old('title', $artikel->title ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $artikel->slug ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
        @error('slug') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Konten</label>
        <textarea name="content" rows="6" class="mt-1 w-full rounded border-gray-300" required>{{ old('content', $artikel->content ?? '') }}</textarea>
        @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- ✅ Thumbnail URL --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Thumbnail URL (Google Drive / Link gambar)</label>
        <input
            type="url"
            name="thumbnail_url"
            value="{{ old('thumbnail_url') }}"
            placeholder="https://drive.google.com/file/d/ID/view?usp=sharing"
            class="mt-1 w-full rounded border-gray-300"
        >
        <p class="text-xs text-gray-500 mt-1">
            Kalau diisi, ini akan dipakai (kecuali kamu upload file di bawah — upload file menang).
        </p>
        @error('thumbnail_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Upload tetap ada --}}
    <div>
        <label class="block text-sm font-medium text-gray-700">Upload Thumbnail (opsional)</label>
        <input type="file" name="thumbnail" class="mt-1 w-full rounded border-gray-300">
        @error('thumbnail') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

        @if(isset($artikel) && $artikel->thumbnail)
            <p class="text-sm text-gray-500 mt-2">Saat ini tersimpan: <span class="font-mono">{{ $artikel->thumbnail }}</span></p>

            @if($preview)
                <img src="{{ $preview }}" alt="Preview thumbnail" class="mt-2 w-full max-w-md rounded border" loading="lazy"
                     onerror="this.style.display='none'">
            @endif
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded border-gray-300" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $artikel->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Thumbnail URL (Google Drive / Link gambar)</label>
    <input
        type="text"
        name="thumbnail_url"
        value="{{ old('thumbnail_url') }}"
        class="mt-1 w-full rounded border-gray-300"
        placeholder="https://drive.google.com/file/d/XXXX/view?usp=sharing"
    >

    <p class="text-xs text-gray-500 mt-1">
        Kalau diisi, ini akan dipakai (kecuali kamu upload file di bawah — upload file menang).
    </p>

    @if(isset($artikel) && $artikel->thumbnail)
        <p class="text-xs text-gray-500 mt-1">Saat ini tersimpan: {{ $artikel->thumbnail }}</p>
    @endif
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Upload Thumbnail (opsional)</label>
    <input type="file" name="thumbnail" class="mt-1 w-full rounded border-gray-300">
</div>
