<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-4">
        <h1 class="text-3xl font-semibold">{{ $kegiatan->nama_kegiatan }}</h1>
        <p class="text-sm text-gray-500">
            {{ $kegiatan->tanggal_mulai->format('d M Y H:i') }}
            @if($kegiatan->tanggal_selesai)
                - {{ $kegiatan->tanggal_selesai->format('d M Y H:i') }}
            @endif
        </p>
        <p class="text-sm text-gray-600">{{ $kegiatan->lokasi }}</p>
        <div class="prose max-w-none">
            {!! nl2br(e($kegiatan->deskripsi)) !!}
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Pendaftar</h2>
            <ul class="text-sm text-gray-700 space-y-1">
                @foreach ($kegiatan->pendaftarans as $pendaftaran)
                    <li>{{ $pendaftaran->user->name }} - <span class="capitalize">{{ $pendaftaran->status }}</span></li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
