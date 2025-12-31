<x-front-layout>
@php
  $bg = '#13392f';
  $accent = '#E7B14B';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  $ico = [
    'history' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v6h6"/><path d="M12 7v5l3 2"/></svg>',
    'calendar' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
    'check' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
    'x' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>',
    'clock' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    'doc' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; }
  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">
  {{-- TOP BAR --}}
  <header class="sticky top-0 z-50 border-b border-white/12 bg-[#13392f]/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white/95">Riwayat</p>
          <p class="text-xs text-white/65">Pendaftaran kegiatan yang pernah Anda ikuti</p>
        </div>

        <div class="hidden sm:flex items-center gap-2">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/85">
            {!! $ico['history'] !!} Riwayat Saya
          </span>
        </div>
      </div>
    </div>
  </header>

  {{-- HERO --}}
  <section class="relative">
    <div class="absolute inset-0 -z-10">
      <div class="h-full w-full bg-gradient-to-b from-black/45 via-black/25 to-black/55"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
      <div class="grid gap-6 lg:grid-cols-12 lg:items-end">
        <div class="lg:col-span-7">
          <p class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90 backdrop-blur">
            {!! $ico['doc'] !!} Riwayat Saya
          </p>

          <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
            Pendaftaran Kegiatan
          </h1>

          <p class="mt-2 max-w-2xl text-sm text-white/80 sm:text-base">
            Pantau status pendaftaran seluruh kegiatan yang pernah Anda ikuti.
          </p>
        </div>

        <div class="lg:col-span-5">
          <div class="{{ $glass }} p-4 sm:p-5">
            <div class="flex items-center justify-between gap-3">
              <div class="min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Ringkasan</p>
                <p class="mt-1 text-sm text-white/85">
                  Total data: <span class="font-semibold text-white">{{ method_exists($pendaftaranKegiatans, 'total') ? number_format($pendaftaranKegiatans->total()) : number_format($pendaftaranKegiatans->count()) }}</span>
                </p>
              </div>
              <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white/90">
                {!! $ico['history'] !!}
              </span>
            </div>

            <div class="mt-3 rounded-2xl border border-white/12 bg-white/6 p-3 text-xs text-white/75">
              Status: <span class="font-semibold text-white">diterima</span>, <span class="font-semibold text-white">diproses</span>, atau <span class="font-semibold text-white">ditolak</span>.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CONTENT --}}
  <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="{{ $glass }} overflow-hidden">
      {{-- Desktop table --}}
      <div class="hidden md:block">
        <table class="w-full text-sm">
          <thead class="border-b border-white/12 bg-white/6">
            <tr class="text-left text-white/80">
              <th class="px-6 py-4 font-semibold">Kegiatan</th>
              <th class="px-6 py-4 font-semibold">Status</th>
              <th class="px-6 py-4 font-semibold">Tanggal Daftar</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-white/10">
            @forelse ($pendaftaranKegiatans as $pendaftaran)
              @php
                $st = strtolower($pendaftaran->status ?? 'diproses');

                $badge = match ($st) {
                  'diterima' => 'bg-[rgba(16,185,129,0.16)] text-white border-white/14', // emerald-ish
                  'ditolak'  => 'bg-[rgba(239,68,68,0.16)] text-white border-white/14',  // red-ish
                  default    => 'bg-white/10 text-white border-white/14',                // pending
                };

                $icon = match ($st) {
                  'diterima' => $ico['check'],
                  'ditolak'  => $ico['x'],
                  default    => $ico['clock'],
                };
              @endphp

              <tr class="hover:bg-white/5 transition">
                <td class="px-6 py-4">
                  <div class="font-semibold text-white/95">
                    {{ $pendaftaran->kegiatan->nama_kegiatan ?? '-' }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $badge }}">
                    {!! $icon !!}
                    <span class="capitalize">{{ $pendaftaran->status }}</span>
                  </span>
                </td>

                <td class="px-6 py-4 text-white/85">
                  <span class="inline-flex items-center gap-2">
                    {!! $ico['calendar'] !!}
                    {{ optional($pendaftaran->created_at)->translatedFormat('d M Y') }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-6 py-10 text-center text-white/75">
                  Belum ada riwayat pendaftaran.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Mobile cards --}}
      <div class="md:hidden p-4 space-y-3">
        @forelse ($pendaftaranKegiatans as $pendaftaran)
          @php
            $st = strtolower($pendaftaran->status ?? 'diproses');

            $badge = match ($st) {
              'diterima' => 'bg-[rgba(16,185,129,0.16)] text-white border-white/14',
              'ditolak'  => 'bg-[rgba(239,68,68,0.16)] text-white border-white/14',
              default    => 'bg-white/10 text-white border-white/14',
            };

            $icon = match ($st) {
              'diterima' => $ico['check'],
              'ditolak'  => $ico['x'],
              default    => $ico['clock'],
            };
          @endphp

          <div class="rounded-2xl border border-white/12 bg-white/6 p-4">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="text-sm font-extrabold text-white/95">
                  {{ $pendaftaran->kegiatan->nama_kegiatan ?? '-' }}
                </p>

                <p class="mt-2 inline-flex items-center gap-2 text-xs text-white/80">
                  {!! $ico['calendar'] !!}
                  {{ optional($pendaftaran->created_at)->translatedFormat('d M Y') }}
                </p>
              </div>

              <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $badge }}">
                {!! $icon !!}
                <span class="capitalize">{{ $pendaftaran->status }}</span>
              </span>
            </div>
          </div>
        @empty
          <div class="rounded-2xl border border-white/12 bg-white/6 p-6 text-center text-sm text-white/75">
            Belum ada riwayat pendaftaran.
          </div>
        @endforelse
      </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
      {{ $pendaftaranKegiatans->links() }}
    </div>
  </main>
</div>
</x-front-layout>
