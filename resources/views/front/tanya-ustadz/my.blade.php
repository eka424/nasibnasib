<x-front-layout>
@php
  // ===== THEME (match front) =====
  $bg = '#13392f';
  $accent = '#E7B14B';
  $primary = '#0F4A3A';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ===== ICONS (simple outline / flaticon-like) =====
  $ico = [
    'user' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>',
    'tag' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.2l-7.4 7.4a2 2 0 0 1-2.8 0L3 13.2V3h10.2l7.4 7.4a2 2 0 0 1 0 2.8z"/><path d="M7.5 7.5h.01"/></svg>',
    'check' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
    'clock' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    'message' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }
  .lc-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">

  {{-- Top bar --}}
  <header class="sticky top-0 z-50 border-b border-white/12 bg-[#13392f]/75 backdrop-blur">
    <div class="mx-auto max-w-5xl px-4 py-4">
      <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white/95">Pertanyaan Saya</p>
          <p class="text-xs text-white/65">Riwayat konsultasi jamaah</p>
        </div>

        <span class="hidden sm:inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/85">
          {!! $ico['user'] !!}
          Ruang Konsultasi
        </span>
      </div>
    </div>
  </header>

  {{-- Hero --}}
  <section class="relative">
    <div class="absolute inset-0 -z-10">
      <div class="h-full w-full bg-gradient-to-b from-black/45 via-black/25 to-black/60"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-10 sm:py-12">
      <div class="text-center space-y-2">
        <p class="inline-flex items-center justify-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90">
          {!! $ico['message'] !!} Ruang Konsultasi
        </p>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Pertanyaan Saya</h1>
        <p class="text-sm text-white/75">Lihat pertanyaan yang pernah Anda kirim beserta status & jawabannya.</p>
      </div>
    </div>
  </section>

  {{-- Content --}}
  <main class="mx-auto max-w-5xl px-4 pb-16 -mt-6 space-y-4">
    <div class="{{ $glass }} p-4 sm:p-5">
      <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="text-sm font-semibold text-white/90">Daftar Pertanyaan</div>
        <div class="text-xs text-white/65">
          Total: <span class="font-semibold text-white">{{ method_exists($pertanyaans,'total') ? number_format($pertanyaans->total()) : number_format($pertanyaans->count()) }}</span>
        </div>
      </div>
      <p class="mt-1 text-xs text-white/65">Klik/scroll untuk melihat jawaban jika sudah tersedia.</p>
    </div>

    <div class="space-y-4">
      @forelse ($pertanyaans as $pertanyaan)
        @php
          $kategori = ucfirst($pertanyaan->kategori ?? 'umum');
          $status = strtolower($pertanyaan->status ?? 'diproses');
          $tanggal = optional($pertanyaan->created_at)->translatedFormat('d M Y');

          $badge = match ($status) {
            'dijawab', 'selesai' => 'bg-[rgba(231,177,75,0.18)] text-white border-white/14',
            'diproses', 'proses', 'pending' => 'bg-white/10 text-white border-white/14',
            'ditolak' => 'bg-rose-500/20 text-rose-100 border-rose-200/20',
            default => 'bg-white/8 text-white/80 border-white/12',
          };

          $statusLabel = match ($status) {
            'dijawab', 'selesai' => 'Dijawab',
            'diproses', 'proses', 'pending' => 'Diproses',
            'ditolak' => 'Ditolak',
            default => ucfirst($status),
          };
        @endphp

        <article class="{{ $glass }} p-5 sm:p-6 transition hover:-translate-y-0.5 hover:bg-white/10">
          <div class="flex flex-wrap items-center gap-2 text-xs text-white/70">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1">
              {!! $ico['tag'] !!} {{ $kategori }}
            </span>

            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 {{ $badge }}">
              {!! $ico['check'] !!} {{ $statusLabel }}
            </span>

            <span class="ml-auto inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1">
              {!! $ico['clock'] !!} {{ $tanggal }}
            </span>
          </div>

          <h3 class="mt-3 text-lg sm:text-xl font-extrabold text-white leading-snug lc-2">
            {{ $pertanyaan->pertanyaan }}
          </h3>

          @if($pertanyaan->jawaban)
            <div class="mt-4 rounded-2xl border border-white/12 bg-white/6 p-4 text-sm text-white/85 leading-relaxed">
              <p class="text-xs font-semibold text-white/70 uppercase tracking-wide">Jawaban</p>
              <div class="mt-2 space-y-2">
                {!! nl2br(e($pertanyaan->jawaban)) !!}
              </div>
            </div>
          @else
            <div class="mt-3 rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-sm text-white/75">
              Jawaban belum tersedia. Silakan cek kembali nanti.
            </div>
          @endif
        </article>
      @empty
        <div class="{{ $glass }} p-8 text-center">
          <p class="text-lg font-extrabold text-white">Belum ada pertanyaan.</p>
          <p class="mt-1 text-sm text-white/75">Silakan ajukan pertanyaan di halaman Tanya Ustadz.</p>
          <a href="{{ route('tanya-ustadz.index') }}"
             class="mt-4 inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:brightness-105"
             style="background: var(--accent);">
            {!! $ico['message'] !!} Ke Tanya Ustadz
          </a>
        </div>
      @endforelse
    </div>

    <div class="pt-2">
      {{ $pertanyaans->links() }}
    </div>
  </main>

</div>
</x-front-layout>
