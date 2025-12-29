<x-front>
    <div class="py-8 text-[#DAF0DC] max-w-5xl mx-auto">
        <a href="{{ route('mosque.profile') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 hover:bg-white/15 mb-6">
            ← Kembali ke Profil
        </a>

        <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6 md:p-8">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Sejarah Masjid</h1>
            <p class="text-[#DAF0DC]/80 mb-6">{{ $profile->nama }}</p>

            <div class="text-[#DAF0DC]/90 leading-relaxed whitespace-pre-line">
                {{ $profile->sejarah ?? 'Belum ada sejarah.' }}
            </div>
        </div>
    </div>
</x-front>
