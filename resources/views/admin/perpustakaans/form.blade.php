@csrf
@if(isset($perpustakaan) && $perpustakaan->exists)
    @method('PUT')
@endif

@php
    $perpustakaan = $perpustakaan ?? null;
    $fileEbook = optional($perpustakaan)->file_ebook;
    $cover = optional($perpustakaan)->cover;
@endphp

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', optional($perpustakaan)->judul ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Penulis</label>
        <input type="text" name="penulis" value="{{ old('penulis', optional($perpustakaan)->penulis ?? '') }}"
            class="mt-1 w-full rounded border-gray-300">
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <input type="text" name="kategori" value="{{ old('kategori', optional($perpustakaan)->kategori ?? '') }}"
                class="mt-1 w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">ISBN</label>
            <input type="text" name="isbn" value="{{ old('isbn', optional($perpustakaan)->isbn ?? '') }}"
                class="mt-1 w-full rounded border-gray-300">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="mt-1 w-full rounded border-gray-300">{{ old('deskripsi', optional($perpustakaan)->deskripsi ?? '') }}</textarea>
    </div>
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
    <div>
        <label class="block text-sm font-medium text-gray-700">File Ebook</label>
        <input type="file" name="file_ebook" class="mt-1 w-full rounded border-gray-300">
        @if($fileEbook && !str_starts_with($fileEbook, 'http'))
            <p class="text-sm text-gray-500 mt-1">{{ $fileEbook }}</p>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Atau URL Ebook</label>
        <input type="text" name="file_url"
            value="{{ old('file_url', $fileEbook && str_starts_with($fileEbook, 'http') ? $fileEbook : '') }}"
            class="mt-1 w-full rounded border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Cover</label>
        <input type="file" name="cover" class="mt-1 w-full rounded border-gray-300">
        @if($cover && !str_starts_with($cover, 'http'))
            <p class="text-sm text-gray-500 mt-1">{{ $cover }}</p>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Atau URL Cover</label>
        <input type="text" name="cover_url"
            value="{{ old('cover_url', $cover && str_starts_with($cover, 'http') ? $cover : '') }}"
            class="mt-1 w-full rounded border-gray-300">
    </div>
    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
