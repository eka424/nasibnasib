<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-2">
        <h1 class="text-2xl font-semibold">{{ $pendaftaran->user->name }}</h1>
        <p class="text-sm text-gray-500">{{ $pendaftaran->user->email }}</p>
        <p>Kegiatan: <strong>{{ $pendaftaran->kegiatan->nama_kegiatan }}</strong></p>
        <p>Status: <span class="capitalize">{{ $pendaftaran->status }}</span></p>
        <p>Daftar pada: {{ $pendaftaran->created_at->format('d M Y H:i') }}</p>
    </div>
</x-app-layout>
