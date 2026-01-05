@csrf
@if(isset($perpustakaan) && $perpustakaan->exists)
  @method('PUT')
@endif

@php
  $perpustakaan = $perpustakaan ?? null;
  $fileEbook = old('file_url', optional($perpustakaan)->file_ebook);
  $cover = old('cover_url', optional($perpustakaan)->cover);
@endphp

<div class="space-y-5">

  {{-- Judul --}}
  <div>
    <label class="block text-sm font-medium text-gray-700">Judul</label>
    <input type="text" name="judul"
      value="{{ old('judul', optional($perpustakaan)->judul ?? '') }}"
      class="mt-1 w-full rounded border-gray-300"
      required>
  </div>

  {{-- Penulis --}}
  <div>
    <label class="block text-sm font-medium text-gray-700">Penulis</label>
    <input type="text" name="penulis"
      value="{{ old('penulis', optional($perpustakaan)->penulis ?? '') }}"
      class="mt-1 w-full rounded border-gray-300">
  </div>

  {{-- Kategori + ISBN --}}
  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="block text-sm font-medium text-gray-700">Kategori</label>
      <input type="text" name="kategori"
        value="{{ old('kategori', optional($perpustakaan)->kategori ?? '') }}"
        class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">ISBN</label>
      <input type="text" name="isbn"
        value="{{ old('isbn', optional($perpustakaan)->isbn ?? '') }}"
        class="mt-1 w-full rounded border-gray-300">
    </div>
  </div>

  {{-- Deskripsi --}}
  <div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="deskripsi" rows="4"
      class="mt-1 w-full rounded border-gray-300">{{ old('deskripsi', optional($perpustakaan)->deskripsi ?? '') }}</textarea>
  </div>

  {{-- Stok --}}
  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="block text-sm font-medium text-gray-700">Total Eksemplar</label>
      <input type="number" name="stok_total" min="0"
        value="{{ old('stok_total', optional($perpustakaan)->stok_total ?? 0) }}"
        class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Eksemplar Tersedia</label>
      <input type="number" name="stok_tersedia" min="0"
        value="{{ old('stok_tersedia', optional($perpustakaan)->stok_tersedia ?? (optional($perpustakaan)->stok_total ?? 0)) }}"
        class="mt-1 w-full rounded border-gray-300">
    </div>
  </div>

  <hr class="border-gray-200">

  {{-- Link PDF --}}
  <div>
    <label class="block text-sm font-medium text-gray-700">Link PDF Ebook (Google Drive)</label>
    <input type="url" name="file_url"
      value="{{ $fileEbook }}"
      placeholder="Tempel link Drive PDF (Share: Anyone with the link - Viewer)"
      class="mt-1 w-full rounded border-gray-300">

    <p class="mt-1 text-xs text-gray-500">
      Pastikan Google Drive: <b>Share → Anyone with the link → Viewer</b>. (Kalau tidak, preview bisa gagal.)
    </p>

    @if($fileEbook)
      <div class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-3">
        <div class="text-xs font-medium text-gray-600 mb-2">Preview PDF</div>
        <iframe
          src="{{ $fileEbook }}"
          class="w-full h-[520px] rounded-lg border border-gray-200 bg-white"
          loading="lazy"
          referrerpolicy="no-referrer"
        ></iframe>

        <a href="{{ $fileEbook }}" target="_blank" rel="noreferrer"
          class="mt-2 inline-block text-sm text-emerald-700 hover:underline">
          Buka PDF di tab baru →
        </a>
      </div>
    @endif
  </div>

  {{-- Link Cover --}}
  <div>
    <label class="block text-sm font-medium text-gray-700">Link Cover (Google Drive / URL Gambar)</label>
    <input type="url" name="cover_url"
      value="{{ $cover }}"
      placeholder="Tempel link Drive gambar (Share: Anyone with the link - Viewer)"
      class="mt-1 w-full rounded border-gray-300">

    @if($cover)
      <div class="mt-3">
        <div class="text-xs font-medium text-gray-600 mb-2">Preview Cover</div>
        <img
          src="{{ $cover }}"
          class="w-44 rounded-xl border border-gray-200 bg-white"
          alt="Cover"
          loading="lazy"
          referrerpolicy="no-referrer"
          onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
        >
        <div class="hidden text-xs text-red-600">
          Preview cover gagal. Biasanya karena link Drive belum public (Anyone with the link) atau link bukan file gambar.
        </div>
      </div>
    @endif
  </div>

  <div class="pt-2">
    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">
      Simpan
    </button>
  </div>

</div>
