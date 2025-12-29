@csrf
@if(isset($galeri) && $galeri->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $galeri->judul ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('deskripsi', $galeri->deskripsi ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tipe</label>
        <select name="tipe" class="mt-1 w-full rounded border-gray-300">
            @foreach (['image' => 'Gambar', 'video' => 'Video'] as $value => $label)
                <option value="{{ $value }}" @selected(old('tipe', $galeri->tipe ?? 'image') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">File</label>
        <input type="file" name="attachment" class="mt-1 w-full rounded border-gray-300">
        @if(isset($galeri) && $galeri->url_file && !str_starts_with($galeri->url_file, 'http'))
            <p class="text-sm text-gray-500 mt-1">Saat ini: {{ $galeri->url_file }}</p>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Atau URL</label>
        <input type="text" name="url_file" value="{{ old('url_file', str_starts_with($galeri->url_file ?? '', 'http') ? $galeri->url_file : '') }}"
            class="mt-1 w-full rounded border-gray-300">
    </div>
    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
