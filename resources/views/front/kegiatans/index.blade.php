<x-front-layout>
@php
  // ========= DATA =========
  $chips = ['Semua', 'Kajian', 'Pelatihan', 'Perlombaan', 'Sosial', 'Lainnya'];

  $q = request('q');
  $chipActive = request('chip', 'Semua');
  $from = request('from');
  $to = request('to');

  $jenisList = ['Kajian', 'Pelatihan', 'Perlombaan', 'Sosial', 'Lainnya'];
  $statusList = [
    'dibuka' => 'Pendaftaran Dibuka',
    'berlangsung' => 'Berlangsung',
    'selesai' => 'Selesai',
  ];

  $jenisChecked  = request('jenis', $jenisList);
  $statusChecked = request('status', array_keys($statusList));

  // ========= THEME (match your front) =========
  $bg = '#13392f';
  $accent = '#E7B14B';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ========= ICONS: simple outline (flaticon-like common look) =========
  $ico = [
    'search' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>',
    'calendar' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
    'clock' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    'pin' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.2"/></svg>',
    'filter' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9v7l4 2v-9l8-9z"/></svg>',
    'sliders' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7"/><path d="M4 10V3"/><path d="M12 21v-9"/><path d="M12 8V3"/><path d="M20 21v-5"/><path d="M20 12V3"/><path d="M2 14h4"/><path d="M10 8h4"/><path d="M18 16h4"/></svg>',
    'grid' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="2"/><rect x="13" y="3" width="8" height="8" rx="2"/><rect x="3" y="13" width="8" height="8" rx="2"/><rect x="13" y="13" width="8" height="8" rx="2"/></svg>',
    'x' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>',
    'badge' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 7 7 3-7 3-3 7-3-7-7-3 7-3 3-7z"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; }
  .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
  .scrollbar-none::-webkit-scrollbar{display:none;}
  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }

  /* ========= FIX: date input text gelap biar kebaca ========= */
  .date-dark{
    color:#0f172a !important;                 /* slate-900 */
    background: rgba(255,255,255,.92) !important;
    border-color: rgba(255,255,255,.18) !important;
  }
  .date-dark:focus{
    outline: none;
    box-shadow: 0 0 0 2px rgba(231,177,75,.55);
  }
  .date-dark::placeholder{ color: rgba(15,23,42,.55) !important; }

  /* Chrome/Safari indicator (icon kalender) */
  .date-dark::-webkit-calendar-picker-indicator{
    opacity: .85;
    cursor: pointer;
  }

  /* =========================
   FILTER CONTRAST FIX
   ========================= */

/* Judul section (Tanggal / Jenis / Status) */
.filter-title,
aside p.uppercase,
#filterPanel p.uppercase {
  color: rgba(255,255,255,0.95) !important;
}

/* Label checkbox */
.filter-label,
aside label,
#filterPanel label {
  color: rgba(255,255,255,0.9) !important;
  font-weight: 500;
}

/* Helper text */
.filter-hint,
aside .text-white\/70,
#filterPanel .text-white\/55 {
  color: rgba(255,255,255,0.75) !important;
}

/* Checkbox style */
input[type="checkbox"] {
  accent-color: #E7B14B;
  cursor: pointer;
}

/* Checkbox + label alignment */
label.inline-flex {
  align-items: center;
  gap: 0.55rem;
}

/* Mobile filter panel background stronger */
#filterPanel > div {
  background: rgba(19,57,47,0.96) !important;
}

/* Mobile filter title */
#filterPanel h2,
#filterPanel p.text-lg {
  color: #ffffff !important;
}

/* Mobile date input */
#filterPanel input[type="date"] {
  background: rgba(255,255,255,0.95) !important;
  color: #0f172a !important;
}

/* Desktop filter card text */
aside {
  color: rgba(255,255,255,0.92);
}

/* Buttons */
button[type="submit"],
button[type="button"] {
  font-weight: 600;
}

</style>

<div class="min-h-screen text-white" style="background: var(--bg);">

  {{-- TOP BAR --}}
  <header class="sticky top-0 z-50 border-b border-white/12 bg-[#13392f]/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-4">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white/95">Kegiatan</p>
          <p class="text-xs text-white/65">Agenda masjid untuk belajar & berkontribusi</p>
        </div>

        <div class="hidden sm:flex items-center gap-2">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/85">
            {!! $ico['grid'] !!} Daftar Kegiatan
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
            {!! $ico['badge'] !!} Agenda Masjid
          </p>

          <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
            Kegiatan Masjid Al-A’la
          </h1>

          <p class="mt-2 max-w-2xl text-sm text-white/80 sm:text-base">
            Temukan kegiatan terbaik untuk belajar, berbagi, dan berkontribusi bersama jamaah.
          </p>

          {{-- Chips --}}
          <div class="mt-5 flex flex-wrap gap-2 text-xs font-semibold">
            @foreach ($chips as $c)
              @php $active = $c === $chipActive; @endphp
              <a href="{{ request()->fullUrlWithQuery(['chip' => $c]) }}"
                 class="rounded-full border px-3 py-1.5 transition
                 {{ $active ? 'border-white/20 bg-white/10 text-white' : 'border-white/12 bg-white/5 text-white/80 hover:bg-white/8 hover:text-white' }}">
                {{ $c }}
              </a>
            @endforeach
          </div>
        </div>

        {{-- Search / Actions --}}
        <div class="lg:col-span-5">
          <div class="{{ $glass }} p-4 sm:p-5">
            <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
              <div class="relative flex-1">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-black/70">
                  {!! $ico['search'] !!}
                </span>
                <input
                  name="q"
                  value="{{ $q }}"
                  placeholder="Cari kegiatan, lokasi, atau deskripsi..."
                  class="h-11 w-full rounded-2xl border border-white/14 bg-white/6 pl-10 pr-3 text-sm text-white placeholder:text-black/55 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]" />
              </div>

              {{-- keep other filters --}}
              <input type="hidden" name="chip" value="{{ $chipActive }}">
              <input type="hidden" name="from" value="{{ $from }}">
              <input type="hidden" name="to" value="{{ $to }}">
              @foreach ((array) $jenisChecked as $j)
                <input type="hidden" name="jenis[]" value="{{ $j }}">
              @endforeach
              @foreach ((array) $statusChecked as $s)
                <input type="hidden" name="status[]" value="{{ $s }}">
              @endforeach

              <div class="flex gap-2">
                <button type="submit"
                  class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-[#0F4A3A] px-4 text-sm font-semibold text- transition hover:brightness-110">
                  {!! $ico['search'] !!} Cari
                </button>

                <button type="button" data-open-sheet
                  class="lg:hidden inline-flex h-11 items-center justify-center gap-2 rounded-2xl border border-white/14 bg-white/6 px-4 text-sm font-semibold text-black/90 transition hover:bg-white/10">
                  {!! $ico['filter'] !!} Filter
                </button>
              </div>
            </form>

            <div class="mt-3 rounded-2xl border border-white/12 bg-white/6 p-3 text-xs text-white/75">
              Gunakan filter tanggal, jenis, dan status untuk memudahkan pencarian.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- MAIN --}}
  <main class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 pb-16 sm:px-6 lg:grid-cols-[18rem_1fr] lg:px-8">

    {{-- DESKTOP FILTER --}}
    <aside class="hidden lg:block lg:sticky lg:top-24 lg:h-fit {{ $glass }} p-5">
      <form method="GET" class="space-y-6 text-sm">
        <input type="hidden" name="q" value="{{ $q }}">
        <input type="hidden" name="chip" value="{{ $chipActive }}">

        <div>
          <p class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Tanggal</p>
          <div class="grid gap-2">
            {{-- FIXED: date-dark --}}
            <input type="date" name="from" value="{{ $from }}"
              class="date-dark h-11 w-full rounded-2xl border px-3 text-sm focus:outline-none" />
            <input type="date" name="to" value="{{ $to }}"
              class="date-dark h-11 w-full rounded-2xl border px-3 text-sm focus:outline-none" />
          </div>
        </div>
<a href="{{ route('kegiatan.calendar') }}"
   class="inline-flex items-center rounded-xl bg-[#E7B14B] px-4 py-2 text-sm font-bold text-[#13392f]">
  Kalender Kegiatan →
</a>

        <div>
          <p class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Jenis</p>
          <div class="grid grid-cols-2 gap-2 text-xs">
            @foreach ($jenisList as $j)
              <label class="inline-flex items-center gap-2 text-white">
                <input type="checkbox" name="jenis[]" value="{{ $j }}"
                  class="h-4 w-4 rounded border-white/25 bg-white/10"
                  {{ in_array($j, (array)$jenisChecked) ? 'checked' : '' }} />
                {{ $j }}
              </label>
            @endforeach
          </div>
        </div>

        <div>
          <p class="mb-2 text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Status</p>
          <div class="grid gap-2 text-xs">
            @foreach ($statusList as $key => $label)
              <label class="inline-flex items-center gap-2 text-white/85">
                <input type="checkbox" name="status[]" value="{{ $key }}"
                  class="h-4 w-4 rounded border-white/25 bg-white/10"
                  {{ in_array($key, (array)$statusChecked) ? 'checked' : '' }} />
                {{ $label }}
              </label>
            @endforeach
          </div>
        </div>

        <div class="flex gap-2 text-xs font-semibold">
          <a href="{{ route('kegiatan.index') }}"
            class="flex-1 rounded-2xl border border-white/14 bg-white/6 px-3 py-3 text-center text-white/85 hover:bg-white/10">
            Reset
          </a>
          <button class="flex-1 rounded-2xl px-3 py-3 text-[#13392f]"
            style="background: var(--accent);">
            Terapkan
          </button>
        </div>
      </form>
    </aside>

    {{-- CONTENT --}}
    <section>
      {{-- Banner --}}
      <div class="{{ $glass }} mb-4 overflow-hidden">
        <div class="relative">
          <img src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1600&q=80"
            alt="Banner Kegiatan Masjid"
            class="h-32 w-full object-cover opacity-80" referrerpolicy="no-referrer">
          <div class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/25 to-transparent"></div>
          <div class="relative px-5 py-4">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/75">Kegiatan</p>
            <p class="text-lg font-extrabold text-white leading-tight">Semua agenda masjid dalam satu tempat</p>
            <p class="mt-1 text-sm text-white/75">Klik detail untuk info lengkap dan jika ingin mendaftar</p>
          </div>
        </div>
      </div>

      {{-- Cards --}}
      <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2 scrollbar-none md:grid md:grid-cols-2 xl:grid-cols-3 md:overflow-visible"
           style="WebkitOverflowScrolling:touch;">

        @forelse ($kegiatans as $kegiatan)
          @php
            $start = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);
            $end = \Carbon\Carbon::parse($kegiatan->tanggal_selesai ?? $kegiatan->tanggal_mulai);

            $status = $start->isFuture() ? 'dibuka' : ($end->isPast() ? 'selesai' : 'berlangsung');

            $statusLabel = [
              'dibuka' => 'Pendaftaran Dibuka',
              'berlangsung' => 'Sedang Berlangsung',
              'selesai' => 'Selesai',
            ][$status];

            $statusClass = match ($status) {
              'dibuka' => 'bg-[rgba(231,177,75,0.16)] text-white border-white/14',
              'berlangsung' => 'bg-white/10 text-white border-white/14',
              default => 'bg-white/6 text-white/80 border-white/12',
            };

             $count = (int) ($kegiatan->pendaftarans_count ?? 0);

  // ✅ ambil dari DB (admin input)
  $quota = $kegiatan->kuota; // null = terbuka

  $isOpenQuota = empty($quota);
  $remaining = $isOpenQuota ? null : max(0, (int)$quota - $count);

  // progress hanya kalau ada kuota
  $progress = $isOpenQuota ? 0 : min(100, (int) round(($count / max(1, (int)$quota)) * 100));

  $isFull = !$isOpenQuota && $count >= (int)$quota;

            $poster = $kegiatan->poster ?? 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1400&q=80';
          @endphp

          <article class="group w-[85vw] max-w-sm flex-none snap-start overflow-hidden rounded-[24px] border border-white/12 bg-white/95 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_70px_-48px_rgba(0,0,0,0.45)] md:w-auto md:max-w-none md:flex-auto">
            <div class="relative aspect-video overflow-hidden">
              <img loading="lazy"
                   src="{{ $poster }}"
                   alt="{{ $kegiatan->nama_kegiatan }}"
                   class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                   referrerpolicy="no-referrer">
              <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>

              <span class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/18 bg-white/85 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#13392f]">
                Program Masjid
              </span>

              <span class="absolute right-3 top-3 inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-semibold {{ $statusClass }}">
                {{ $statusLabel }}
              </span>
            </div>

            <div class="p-4 space-y-3 text-slate-900">
              <h3 class="text-base font-extrabold leading-snug line-clamp-2">
                {{ $kegiatan->nama_kegiatan }}
              </h3>

              <div class="space-y-1 text-xs text-slate-600">
                <p class="flex items-center gap-2">
                  <span class="text-[#13392f]">{!! $ico['calendar'] !!}</span>
                  <span>{{ $start->translatedFormat('D, d M Y') }}</span>
                  <span class="text-slate-300">•</span>
                  <span class="inline-flex items-center gap-1">
                    <span class="text-[#13392f]">{!! $ico['clock'] !!}</span>
                    {{ $start->translatedFormat('H:i') }} WITA
                  </span>
                </p>
                <p class="flex items-center gap-2">
                  <span class="text-[#13392f]">{!! $ico['pin'] !!}</span>
                  <span class="line-clamp-1">{{ $kegiatan->lokasi }}</span>
                </p>
              </div>

              <p class="text-sm text-slate-600 line-clamp-3">
                {{ \Illuminate\Support\Str::limit(strip_tags($kegiatan->deskripsi), 140) }}
              </p>

              {{-- Progress --}}
              <div>
  @if($isOpenQuota)
    <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500">
      <span>Kuota terbuka</span>
      <span>{{ $count }} terdaftar</span>
    </div>
  @else
    <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
      <div class="h-full rounded-full"
           style="width: {{ $progress }}%; background: linear-gradient(90deg, {{ $accent }}, #0F4A3A);"></div>
    </div>

    <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500">
      @if($isFull)
        <span class="font-semibold text-red-600">Kuota Penuh</span>
      @else
        <span>{{ $remaining }} kursi tersisa</span>
      @endif
      <span>{{ $count }}/{{ $quota }}</span>
    </div>
  @endif
</div>

              <div class="flex items-center justify-between gap-2 pt-1">
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                   class="inline-flex items-center justify-center rounded-2xl px-3 py-2 text-xs font-semibold text-white transition hover:brightness-110"
                   style="background: #0F4A3A;">
                  Detail
                </a>

                @auth
                  @if ($status === 'dibuka')
                    <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
                      @csrf
                    </form>
                  @endif
                @endauth
              </div>
            </div>
          </article>
        @empty
          <div class="w-full {{ $glass }} p-6">
            <p class="text-sm text-white/85">Tidak ada kegiatan ditemukan.</p>
          </div>
        @endforelse
      </div>

      <p class="mt-2 text-xs text-white/70 md:hidden">
        Geser kiri/kanan untuk melihat kegiatan lainnya.
      </p>

      <div class="mt-8">
        {{ $kegiatans->appends(request()->query())->links() }}
      </div>
    </section>
  </main>

  {{-- MOBILE BOTTOM SHEET FILTER --}}
  <div id="filterSheet" class="fixed inset-0 z-[80] lg:hidden pointer-events-none">
    <div id="filterBackdrop"
      class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-300"></div>

    <div id="filterPanel" class="absolute inset-x-0 bottom-0 translate-y-full transition-transform duration-300">
      <div class="mx-auto max-w-xl rounded-t-[28px] border border-white/12 bg-[#13392f]/92 p-5 shadow-2xl backdrop-blur">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Filter</p>
            <p class="mt-1 text-lg font-extrabold text-white">Atur Pencarian</p>
          </div>

          <button type="button" data-close-sheet
            class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white transition hover:bg-white/10">
            {!! $ico['x'] !!}
          </button>
        </div>

        <form method="GET" class="mt-4 space-y-4">
          <input type="hidden" name="q" value="{{ $q }}">
          <input type="hidden" name="chip" value="{{ $chipActive }}">

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs font-semibold text-white/70">Dari</label>
              {{-- FIXED: date-dark --}}
              <input type="date" name="from" value="{{ $from }}"
                class="date-dark mt-1 h-11 w-full rounded-2xl border px-3 text-sm focus:outline-none" />
            </div>
            <div>
              <label class="text-xs font-semibold text-white/70">Sampai</label>
              {{-- FIXED: date-dark --}}
              <input type="date" name="to" value="{{ $to }}"
                class="date-dark mt-1 h-11 w-full rounded-2xl border px-3 text-sm focus:outline-none" />
            </div>
          </div>

          <div>
            <p class="text-xs font-semibold text-white/70">Jenis Kegiatan</p>
            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
              @foreach ($jenisList as $j)
                <label class="inline-flex items-center gap-2 text-white/85">
                  <input type="checkbox" name="jenis[]" value="{{ $j }}"
                    class="h-4 w-4 rounded border-white/25 bg-white/10"
                    {{ in_array($j, (array)$jenisChecked) ? 'checked' : '' }}>
                  {{ $j }}
                </label>
              @endforeach
            </div>
          </div>

          <div>
            <p class="text-xs font-semibold text-white/70">Status</p>
            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
              @foreach ($statusList as $key => $label)
                <label class="inline-flex items-center gap-2 text-white/85">
                  <input type="checkbox" name="status[]" value="{{ $key }}"
                    class="h-4 w-4 rounded border-white/25 bg-white/10"
                    {{ in_array($key, (array)$statusChecked) ? 'checked' : '' }}>
                  {{ $label }}
                </label>
              @endforeach
            </div>
          </div>

          <div class="flex gap-2 pt-1">
            <a href="{{ route('kegiatan.index') }}"
              class="inline-flex flex-1 items-center justify-center rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
              Reset
            </a>
            <button type="submit"
              class="inline-flex flex-1 items-center justify-center rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f]"
              style="background: var(--accent);">
              Terapkan
            </button>
          </div>

          <p class="text-xs text-white/55">
            Filter ini nyaman dipakai satu tangan di mobile.
          </p>
        </form>
      </div>
    </div>
  </div>

  {{-- JS: bottom sheet --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sheet = document.getElementById('filterSheet');
      const panel = document.getElementById('filterPanel');
      const backdrop = document.getElementById('filterBackdrop');

      const openBtns = document.querySelectorAll('[data-open-sheet]');
      const closeBtns = document.querySelectorAll('[data-close-sheet]');

      const openSheet = () => {
        sheet.classList.remove('pointer-events-none');
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        panel.classList.remove('translate-y-full');
        panel.classList.add('translate-y-0');
        document.body.style.overflow = 'hidden';
      };

      const closeSheet = () => {
        backdrop.classList.add('opacity-0');
        backdrop.classList.remove('opacity-100');
        panel.classList.add('translate-y-full');
        panel.classList.remove('translate-y-0');
        document.body.style.overflow = '';
        setTimeout(() => sheet.classList.add('pointer-events-none'), 250);
      };

      openBtns.forEach(btn => btn.addEventListener('click', openSheet));
      closeBtns.forEach(btn => btn.addEventListener('click', closeSheet));
      backdrop.addEventListener('click', closeSheet);

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSheet();
      });
    });
  </script>
</div>
</x-front-layout>
