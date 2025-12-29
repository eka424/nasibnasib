<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-3">
        <h1 class="text-3xl font-semibold">{{ $perpustakaan->judul }}</h1>
        <p class="text-sm text-gray-500">{{ $perpustakaan->penulis }}</p>
        <div class="prose max-w-none">
            {!! nl2br(e($perpustakaan->deskripsi)) !!}
        </div>
        <div class="flex gap-4 text-sm">
            <a href="{{ str_starts_with($perpustakaan->file_ebook, 'http') ? $perpustakaan->file_ebook : Storage::url($perpustakaan->file_ebook) }}"
                class="text-emerald-600 underline" target="_blank">Unduh Ebook</a>
            <a href="{{ str_starts_with($perpustakaan->cover, 'http') ? $perpustakaan->cover : Storage::url($perpustakaan->cover) }}"
                class="text-blue-600 underline" target="_blank">Lihat Cover</a>
        </div>
    </div>
</x-app-layout>
