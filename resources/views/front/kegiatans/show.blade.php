<x-front-layout>
@php
  // ========= Theme =========
  $bg = '#13392f';
  $accent = '#E7B14B';
  $primary = '#0F4A3A';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ========= Poster =========
  $poster = $kegiatan->poster ?? null;

  // kalau ada path storage lokal
  if ($poster && !str_starts_with($poster, 'http')) {
    $poster = \Illuminate\Support\Facades\Storage::url($poster);
  }

  // fallback kalau kosong
  $poster = $poster ?: 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';

  // ========= Labels =========
  $startLabel = optional($kegiatan->tanggal_mulai)->locale('id')->translatedFormat('l, d M Y • H:i') ?? '-';
  $endLabel = $kegiatan->tanggal_selesai
    ? optional($kegiatan->tanggal_selesai)->locale('id')->translatedFormat('l, d M Y • H:i')
    : null;

  $kategori = $kegiatan->kategori ?? 'Program Masjid';
  $biaya = $kegiatan->biaya ?? 0;

  $formatRupiah = function ($n) {
    $n = (int) ($n ?? 0);
    return $n <= 0 ? 'Gratis' : 'Rp ' . number_format($n, 0, ',', '.');
  };

  $status = 'Terbuka';
  if (!empty($kegiatan->tanggal_selesai) && $kegiatan->tanggal_selesai->isPast()) {
    $status = 'Selesai';
  }

  // ========= WhatsApp =========
  $wa = $kegiatan->contact_whatsapp ?? $kegiatan->whatsapp ?? null;
  $waNum = $wa ? preg_replace('/\D+/', '', $wa) : null;
  $waLink = $waNum
    ? 'https://wa.me/' . $waNum . '?text=' . urlencode("Assalamu'alaikum, saya ingin tanya tentang kegiatan: {$kegiatan->nama_kegiatan}")
    : null;

  // ========= Google Calendar =========
  $gcalLink = null;
  try {
    $start = $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->copy()->utc() : null;
    $end = $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->copy()->utc() : ($start ? $start->copy()->addHours(2) : null);

    if ($start && $end) {
      $fmt = fn($d) => $d->format('Ymd\THis\Z');
      $gcalLink = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        . '&text=' . urlencode($kegiatan->nama_kegiatan)
        . '&dates=' . $fmt($start) . '/' . $fmt($end)
        . '&details=' . urlencode('Info kegiatan masjid')
        . '&location=' . urlencode($kegiatan->lokasi ?? '');
    }
  } catch (\Throwable $e) {
    $gcalLink = null;
  }

  // ========= Icons =========
  $ico = [
    'badge' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 7 7 3-7 3-3 7-3-7-7-3 7-3 3-7z"/></svg>',
    'home' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5"/><path d="M5 10v10h14V10"/></svg>',
    'list' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>',
    'calendar' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
    'pin' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.2"/></svg>',
    'money' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M7 10h.01"/><path d="M17 14h.01"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>',
    'check' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
    'copy' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><rect x="2" y="2" width="13" height="13" rx="2"/></svg>',
    'wa' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 1-13.5 7.8L3 21l1.3-4.2A9 9 0 1 1 21 12z"/><path d="M8.5 10.5c.7 2 2.2 3.5 4.2 4.2"/><path d="M13.2 14.7l1.6-.5c.3-.1.7 0 .9.3l.6.8"/></svg>',
    'arrow' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
  svg { stroke: currentColor; }
  svg * { vector-effect: non-scaling-stroke; }
  <style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
  svg { stroke: currentColor; }
  svg * { vector-effect: non-scaling-stroke; }

  /* ===== FLASH "SUDAH TERDAFTAR" KEBYET INFINITE ===== */
  @keyframes kebyetFlash {
    0%   { transform: translateY(0);   filter: brightness(1); }
    20%  { transform: translateY(-1px); filter: brightness(1.15); }
    40%  { transform: translateY(0);   filter: brightness(1); }
    60%  { transform: translateY(-1px); filter: brightness(1.25); }
    100% { transform: translateY(0);   filter: brightness(1); }
  }

  @keyframes glowFlash {
    0%   { box-shadow: 0 0 0 0 rgba(231,177,75,0.0), 0 18px 60px -45px rgba(0,0,0,0.35); }
    25%  { box-shadow: 0 0 0 3px rgba(231,177,75,0.55), 0 0 30px rgba(231,177,75,0.35), 0 18px 60px -45px rgba(0,0,0,0.35); }
    50%  { box-shadow: 0 0 0 0 rgba(231,177,75,0.0), 0 18px 60px -45px rgba(0,0,0,0.35); }
    75%  { box-shadow: 0 0 0 3px rgba(231,177,75,0.65), 0 0 34px rgba(231,177,75,0.45), 0 18px 60px -45px rgba(0,0,0,0.35); }
    100% { box-shadow: 0 0 0 0 rgba(231,177,75,0.0), 0 18px 60px -45px rgba(0,0,0,0.35); }
  }

  .flash-kebyet {
    border-color: rgba(231,177,75,0.55) !important;
    background: rgba(231,177,75,0.10) !important;
    animation: glowFlash 1.1s ease-in-out infinite, kebyetFlash 1.1s ease-in-out infinite;
  }

  .flash-kebyet .flash-text {
    color: #FFD66B; /* kuning kontras */
    font-weight: 800;
    letter-spacing: 0.2px;
    text-shadow: 0 0 10px rgba(231,177,75,0.25);
  }

  .flash-kebyet .flash-icon {
    color: #FFD66B;
  }
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">

  {{-- TOP BAR --}}
  <header class="sticky top-0 z-50 border-b border-white/12 bg-[#13392f]/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white/95">Detail Kegiatan</p>
          <p class="text-xs text-white/65">Informasi lengkap agenda masjid</p>
        </div>

        <div class="hidden sm:flex items-center gap-2">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/85">
            {!! $ico['badge'] !!} {{ $kategori }}
          </span>
        </div>
      </div>
    </div>
  </header>

  {{-- HERO --}}
  <section class="relative overflow-hidden">
    <div aria-hidden class="absolute inset-0 -z-10">
      <img src="{{ $poster }}" alt="{{ $kegiatan->nama_kegiatan }}" class="h-full w-full object-cover opacity-35" referrerpolicy="no-referrer">
      <div class="absolute inset-0 bg-gradient-to-b from-black/65 via-black/35 to-black/70"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">

      {{-- Breadcrumb --}}
      <nav class="text-xs text-white/70">
        <ol class="flex flex-wrap items-center gap-2">
          <li>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:text-white">
              {!! $ico['home'] !!} Beranda
            </a>
          </li>
          <li class="text-white/45">/</li>
          <li>
            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 hover:text-white">
              {!! $ico['list'] !!} Kegiatan
            </a>
          </li>
          <li class="text-white/45">/</li>
          <li class="font-semibold text-white line-clamp-1">Detail</li>
        </ol>
      </nav>

      {{-- FLASH --}}
@if (session('kegiatan_flash'))
  @php
    $flash = session('kegiatan_flash');
    $flashMsg = $flash['message'] ?? '';
    $flashGcal = $flash['gcal'] ?? null;

    // biar cuma "sudah terdaftar" yang kebyet (optional)
    $isAlready = str_contains(mb_strtolower($flashMsg), 'sudah terdaftar');
  @endphp

  <div class="mt-4 rounded-2xl border border-white/14 bg-white/10 p-4 text-white backdrop-blur
              shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)]
              {{ $isAlready ? 'flash-kebyet' : '' }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="text-sm font-semibold">
        <span class="{{ $isAlready ? 'flash-icon' : '' }}">{!! $ico['check'] !!}</span>
        <span class="ml-1 {{ $isAlready ? 'flash-text' : '' }}">{{ $flashMsg }}</span>
      </div>

      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-2">
  <a href="{{ route('kegiatan.riwayat') }}"
    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
    {!! $ico['list'] !!} Lihat Riwayat
  </a>

  @if($flashGcal)
    <a href="{{ $flashGcal }}" target="_blank" rel="noreferrer"
      class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
      {!! $ico['calendar'] !!} Tambah ke Google Calendar
    </a>
  @endif
</div>

    </div>
  </div>
@endif


      {{-- Chips --}}
      <div class="mt-4 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/90">
          {!! $ico['badge'] !!} {{ $kategori }}
        </span>

        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/90">
          {!! $ico['money'] !!} {{ $formatRupiah($biaya) }}
        </span>

        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/90">
          {!! $ico['check'] !!} {{ $status }}
        </span>
      </div>

      <h1 class="mt-3 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
        {{ $kegiatan->nama_kegiatan }}
      </h1>

      <div class="mt-4 grid gap-2 sm:grid-cols-2">
        <div class="inline-flex items-start gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-3">
          <span class="mt-0.5 text-white/85">{!! $ico['calendar'] !!}</span>
          <div class="text-sm text-white/90">
            <div class="font-semibold">{{ $startLabel }}</div>
            @if($endLabel)
              <div class="text-white/65">Sampai: {{ $endLabel }}</div>
            @endif
          </div>
        </div>

        <div class="inline-flex items-start gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-3">
          <span class="mt-0.5 text-white/85">{!! $ico['pin'] !!}</span>
          <div class="text-sm text-white/90">
            <div class="font-semibold line-clamp-1">{{ $kegiatan->lokasi ?? 'Lokasi menyusul' }}</div>
            <div class="text-white/65">Pastikan datang lebih awal.</div>
          </div>
        </div>
      </div>

      {{-- Quick actions (desktop) --}}
      <div class="mt-5 hidden flex-wrap gap-2 sm:flex">
        @if($gcalLink)
          <a href="{{ $gcalLink }}" target="_blank" rel="noreferrer"
            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            {!! $ico['calendar'] !!} Tambah ke Kalender
          </a>
        @endif

        <button type="button" data-copy-link data-default-text="Salin Link"
          class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
          {!! $ico['copy'] !!} Salin Link
        </button>

        @if($waLink)
          <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
            class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold text-[#13392f] hover:brightness-105"
            style="background: var(--accent);">
            {!! $ico['wa'] !!} WhatsApp Panitia
          </a>
        @endif
      </div>
    </div>
  </section>

  {{-- POSTER (rapi + konsisten) --}}
  <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-6">
    <div class="overflow-hidden rounded-[28px] border border-white/12 bg-white/5 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)]">
      <div class="aspect-[16/7] w-full">
        <img
          src="{{ $poster }}"
          alt="Poster {{ $kegiatan->nama_kegiatan }}"
          class="h-full w-full object-cover"
          referrerpolicy="no-referrer"
        >
      </div>
    </div>
  </section>

  {{-- CONTENT --}}
  <main class="mx-auto max-w-7xl px-4 pb-24 pt-6 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-12">

      {{-- LEFT --}}
      <section class="lg:col-span-8">
        <div class="overflow-hidden rounded-[28px] border border-white/10 bg-white/95 text-slate-900 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)]">
          <div class="border-b border-slate-200 bg-white px-5 py-4 sm:px-7">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700">Detail Kegiatan</p>
            <p class="mt-1 text-sm text-slate-600">Informasi lengkap kegiatan & deskripsi.</p>
          </div>

          <div class="px-5 py-6 sm:px-7">
            <div class="prose max-w-none prose-slate prose-headings:font-extrabold prose-headings:text-slate-900 prose-a:text-emerald-700">
              {!! nl2br(e($kegiatan->deskripsi ?? '')) !!}
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
              <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Kuota</p>
                <p class="mt-0.5 text-sm font-semibold text-slate-900">
                  {{ !empty($kegiatan->kuota) ? $kegiatan->kuota.' orang' : 'Terbuka' }}
                </p>
              </div>

              <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Biaya</p>
                <p class="mt-0.5 text-sm font-semibold text-slate-900">
                  {{ $formatRupiah($biaya) }}
                </p>
              </div>

              <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <p class="text-xs text-slate-500">Status</p>
                <p class="mt-0.5 text-sm font-semibold {{ $status === 'Selesai' ? 'text-slate-700' : 'text-emerald-700' }}">
                  {{ $status }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- RIGHT --}}
      <aside class="lg:col-span-4">
        <div class="sticky top-6 space-y-4">

          {{-- Register card --}}
          <div class="{{ $glass }} p-5">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-sm font-extrabold text-white">Pendaftaran</p>
                <p class="mt-1 text-xs text-white/70">Daftar lebih cepat biar tidak ketinggalan info.</p>
              </div>
              <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white/90">
                {!! $ico['badge'] !!}
              </span>
            </div>

            <div class="mt-4 space-y-2">
              @auth
                <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold text-white hover:brightness-110 active:scale-[0.99]"
                    style="background: var(--primary);">
                    {!! $ico['check'] !!} Daftar Kegiatan
                  </button>
                </form>
              @else
                <a href="{{ route('login') }}"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold text-white hover:brightness-110"
                  style="background: var(--primary);">
                  {!! $ico['arrow'] !!} Login untuk Daftar
                </a>
              @endauth

              <div class="grid grid-cols-2 gap-2">
                @if($gcalLink)
                  <a href="{{ $gcalLink }}" target="_blank" rel="noreferrer"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-3 text-sm font-semibold text-white hover:bg-white/10">
                    {!! $ico['calendar'] !!} Kalender
                  </a>
                @else
                  <button type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-3 text-sm font-semibold text-white/60 cursor-not-allowed">
                    {!! $ico['calendar'] !!} Kalender
                  </button>
                @endif

                <button type="button" data-copy-link data-default-text="Salin Link"
                  class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-3 text-sm font-semibold text-white hover:bg-white/10">
                  {!! $ico['copy'] !!} Salin Link
                </button>
              </div>

              @if($waLink)
                <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f] hover:brightness-105"
                  style="background: var(--accent);">
                  {!! $ico['wa'] !!} Tanya Panitia (WA)
                </a>
              @endif
            </div>
          </div>

          {{-- Tips --}}
          <div class="{{ $glass }} p-5">
            <p class="text-sm font-extrabold text-white">Tips hadir</p>
            <ul class="mt-3 space-y-2 text-sm text-white/80">
              <li class="flex gap-2"><span class="mt-0.5 text-white/60">•</span> Datang 10 menit lebih awal.</li>
              <li class="flex gap-2"><span class="mt-0.5 text-white/60">•</span> Bawa catatan / mushaf bila perlu.</li>
              <li class="flex gap-2"><span class="mt-0.5 text-white/60">•</span> Parkir sesuai arahan panitia.</li>
            </ul>
          </div>

        </div>
      </aside>

    </div>
  </main>

  {{-- MOBILE STICKY CTA --}}
  <div class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-[#13392f]/85 p-3 backdrop-blur lg:hidden">
    <div class="mx-auto max-w-7xl">
      <div class="grid grid-cols-2 gap-2">
        @auth
          <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
            @csrf
            <button type="submit"
              class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-white hover:brightness-110 active:scale-[0.99]"
              style="background: var(--primary);">
              {!! $ico['check'] !!} Daftar
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
            class="w-full rounded-2xl px-4 py-3 text-center text-sm font-semibold text-white hover:brightness-110"
            style="background: var(--primary);">
            {!! $ico['arrow'] !!} Login
          </a>
        @endauth

        @if($waLink)
          <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
            class="w-full rounded-2xl px-4 py-3 text-center text-sm font-semibold text-[#13392f] hover:brightness-105"
            style="background: var(--accent);">
            {!! $ico['wa'] !!} WhatsApp
          </a>
        @else
          <button type="button" data-copy-link data-default-text="Salin Link"
            class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f] hover:brightness-105"
            style="background: var(--accent);">
            {!! $ico['copy'] !!} Salin Link
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- Scripts --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('[data-copy-link]');
      buttons.forEach(btn => {
        const defaultText = btn.getAttribute('data-default-text') || 'Salin Link';
        btn.addEventListener('click', async () => {
          try {
            await navigator.clipboard.writeText(window.location.href);
            btn.innerHTML = `{!! $ico['check'] !!} <span>Tersalin</span>`;
            setTimeout(() => {
              btn.innerHTML = `{!! $ico['copy'] !!} <span>${defaultText}</span>`;
            }, 1200);
          } catch (e) {
            alert('Gagal menyalin link');
          }
        });
      });
    });
  </script>

</div>
</x-front-layout>
