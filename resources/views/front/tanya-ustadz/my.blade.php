<x-front-layout>
    <section class="max-w-5xl mx-auto px-4 py-12 space-y-6">
        <div class="text-center space-y-2">
            <p class="text-sm uppercase tracking-[0.3rem] text-emerald-500">Ruang Konsultasi</p>
            <h1 class="text-3xl font-semibold">Pertanyaan Saya</h1>
        </div>
        <div class="space-y-4">
            @foreach ($pertanyaans as $pertanyaan)
                <div class="bg-white rounded-3xl shadow p-6 space-y-2">
                    <p class="font-semibold text-lg">{{ $pertanyaan->pertanyaan }}</p>
                    <p class="text-xs text-gray-500">Kategori: {{ ucfirst($pertanyaan->kategori ?? 'umum') }} • Status: {{ $pertanyaan->status }}</p>
                    @if($pertanyaan->jawaban)
                        <div class="text-sm text-gray-700 border-t pt-2">
                            {!! nl2br(e($pertanyaan->jawaban)) !!}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div>
            {{ $pertanyaans->links() }}
        </div>
    </section>
</x-front-layout>
