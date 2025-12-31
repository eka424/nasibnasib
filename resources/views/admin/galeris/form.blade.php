@csrf
@if(isset($galeri) && $galeri->exists)
    @method('PUT')
@endif

@php
    $isEdit = isset($galeri) && $galeri->exists;

    $selectedKategori = old('kategori', $galeri->kategori ?? 'idarah');
    $selectedSeksi = old('seksi', $galeri->seksi ?? '');

    // untuk url_file input: hanya isi jika memang http
    $urlValue = old('url_file', (isset($galeri) && str_starts_with($galeri->url_file ?? '', 'http')) ? $galeri->url_file : '');
@endphp

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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipe</label>
            <select name="tipe" id="tipe" class="mt-1 w-full rounded border-gray-300">
                @foreach (['image' => 'Gambar', 'video' => 'Video'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('tipe', $galeri->tipe ?? 'image') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="kategori" id="kategori" class="mt-1 w-full rounded border-gray-300" required>
                @foreach (($deptOptions ?? ['idarah'=>'Idarah','imarah'=>'Imarah','riayah'=>'Riayah']) as $value => $label)
                    <option value="{{ $value }}" @selected($selectedKategori === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Seksi</label>
        <select name="seksi" id="seksi" class="mt-1 w-full rounded border-gray-300">
            <option value="">(Opsional) Pilih Seksi</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Seksi akan mengikuti kategori.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">File (Upload)</label>
        <input type="file" name="attachment" class="mt-1 w-full rounded border-gray-300">

        @if($isEdit && ($galeri->url_file ?? null) && !str_starts_with($galeri->url_file, 'http'))
            <p class="text-sm text-gray-500 mt-1">Saat ini (storage): {{ $galeri->url_file }}</p>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Atau URL (untuk Youtube/Drive/Link langsung)</label>
        <input type="text" name="url_file" value="{{ $urlValue }}"
            class="mt-1 w-full rounded border-gray-300" placeholder="https://...">
        <p class="text-xs text-gray-500 mt-1">Wajib isi salah satu: upload file atau URL.</p>
    </div>

    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sectionsMap = @json($sectionsMap ?? []);
    const kategoriEl = document.getElementById('kategori');
    const seksiEl = document.getElementById('seksi');

    const currentSeksi = @json($selectedSeksi);

    function fillSeksi(kategori) {
        const list = sectionsMap[kategori] || [];
        seksiEl.innerHTML = '<option value="">(Opsional) Pilih Seksi</option>';

        list.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            if (s === currentSeksi) opt.selected = true;
            seksiEl.appendChild(opt);
        });
    }

    fillSeksi(kategoriEl.value);
    kategoriEl.addEventListener('change', () => {
        fillSeksi(kategoriEl.value);
        // reset selection saat ganti kategori
        seksiEl.value = '';
    });
});
</script>
