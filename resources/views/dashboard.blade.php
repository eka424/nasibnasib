@php
    $stats = [
        'Artikel' => \App\Models\Artikel::count(),
        'Kegiatan' => \App\Models\Kegiatan::count(),
        'Donasi' => \App\Models\Donasi::count(),
        'Pertanyaan' => \App\Models\PertanyaanUstadz::count(),
    ];
@endphp

<x-app-layout>
    <h1 class="text-3xl font-semibold mb-6">Dashboard</h1>
    <div class="grid md:grid-cols-4 gap-4">
        @foreach ($stats as $label => $value)
            <div class="bg-white shadow rounded p-4 text-center">
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
