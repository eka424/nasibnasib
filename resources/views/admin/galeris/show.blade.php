<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-3">
        <h1 class="text-3xl font-semibold">{{ $galeri->judul }}</h1>
        <p class="text-sm text-gray-600">{{ $galeri->deskripsi }}</p>
        @if($galeri->tipe === 'image')
            <img src="{{ str_starts_with($galeri->url_file, 'http') ? $galeri->url_file : Storage::url($galeri->url_file) }}"
                alt="{{ $galeri->judul }}" class="w-full rounded" loading="lazy">
        @else
            <a href="{{ str_starts_with($galeri->url_file, 'http') ? $galeri->url_file : Storage::url($galeri->url_file) }}"
                target="_blank" class="text-emerald-600 underline">Putar Media</a>
        @endif
    </div>
</x-app-layout>
