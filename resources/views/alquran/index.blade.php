<x-front-layout>
  <div class="min-h-screen bg-[#13392f] text-white">
    <div class="mx-auto max-w-5xl px-4 py-10">

      {{-- Header --}}
      <div class="flex flex-col gap-2">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
          Daftar Surah Al-Qur'an
        </h1>
        <p class="text-sm text-white/70">
          Pilih surah untuk membaca ayat dan terjemahan.
        </p>
      </div>

      {{-- Error --}}
      @if(!empty($error))
        <div class="mt-5 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-red-100">
          <div class="font-bold">Terjadi masalah</div>
          <div class="text-sm text-red-100/90">Error: {{ $error }}</div>
        </div>
      @endif

      {{-- List --}}
      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse(($surahs ?? []) as $s)
          <a
            href="{{ route('quran.show', ['surah' => $s['nomor']]) }}"
            class="group rounded-[22px] border border-white/15 bg-white/5 p-5 shadow-sm backdrop-blur
                   transition hover:-translate-y-0.5 hover:bg-white/10 hover:border-white/25"
          >
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <div class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">
                  Surah #{{ $s['nomor'] }}
                </div>

                <div class="mt-1 text-lg font-extrabold text-white group-hover:text-white">
                  {{ $s['namaLatin'] ?? '-' }}
                </div>

                @if(!empty($s['arti']))
                  <div class="mt-1 text-sm text-white/75">
                    {{ $s['arti'] }}
                  </div>
                @endif
              </div>

              <div class="grid h-11 w-11 flex-none place-items-center rounded-2xl bg-[#E7B14B] text-[#13392f] shadow">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h12"/>
                  <path d="m13 6 6 6-6 6"/>
                </svg>
              </div>
            </div>

            <div class="mt-4 h-px w-full bg-white/10"></div>

            <div class="mt-3 text-xs text-white/60">
              Ketuk untuk membuka surah →
            </div>
          </a>
        @empty
          <div class="rounded-2xl border border-white/15 bg-white/5 p-6 text-white/80">
            Belum ada data surah.
          </div>
        @endforelse
      </div>

    </div>
  </div>
</x-front-layout>
