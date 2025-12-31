<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-3">
        <h1 class="text-3xl font-semibold">{{ $galeri->judul }}</h1>

        <div class="text-sm text-gray-600 space-y-1">
            <div><span class="font-semibold">Kategori:</span> {{ $galeri->kategori ?? '-' }}</div>
            <div><span class="font-semibold">Seksi:</span> {{ $galeri->seksi ?? '-' }}</div>
            <div><span class="font-semibold">Tipe:</span> {{ $galeri->tipe }}</div>
        </div>

        <p class="text-sm text-gray-600">{{ $galeri->deskripsi }}</p>

        @php
            $isLocal = ($galeri->url_file ?? null) && !str_starts_with($galeri->url_file, 'http');
            $mediaUrl = $isLocal ? \Illuminate\Support\Facades\Storage::url($galeri->url_file) : $galeri->url_file;
        @endphp

        @if($galeri->tipe === 'image')
            <img src="{{ $mediaUrl }}"
                alt="{{ $galeri->judul }}" class="w-full rounded" loading="lazy">
        @else
            <a href="{{ $mediaUrl }}" target="_blank" class="text-emerald-600 underline">Putar Media</a>
        @endif
    </div>
</x-app-layout>
