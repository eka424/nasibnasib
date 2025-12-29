<x-front-layout>
@php
    // ====== KEEP: existing data sources (routes/ids tetap sama) ======
    $todayLabel = \Carbon\Carbon::now('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y');

    $bottomNav = [
        ['id' => 'beranda', 'label' => 'Home', 'href' => route('home'), 'icon' => '🏠', 'type' => 'link'],
        ['id' => 'kegiatan', 'label' => 'Kegiatan', 'href' => route('kegiatan.index'), 'icon' => '📅', 'type' => 'link'],
        ['id' => 'artikel', 'label' => 'Artikel', 'href' => route('artikel.index'), 'icon' => '📚', 'type' => 'link'],
        ['id' => 'donasi', 'label' => 'Donasi', 'href' => route('donasi.index'), 'icon' => '❤️', 'type' => 'link'],
    ];

    $quickAmounts = [
        ['label' => 'Rp 10.000', 'icon' => '✨'],
        ['label' => 'Rp 25.000', 'icon' => '🤲'],
        ['label' => 'Rp 50.000', 'icon' => '❤️'],
    ];

    $trustBadges = [
        ['label' => 'Transparan', 'icon' => '🔎'],
        ['label' => 'Terverifikasi', 'icon' => '✅'],
        ['label' => 'Cepat', 'icon' => '⚡'],
    ];

    $fasilitas = [
        ['icon' => '📚', 'text' => 'Perpustakaan digital bacaan Islami anak.'],
        ['icon' => '🧸', 'text' => 'Area bermain aman & mudah diawasi.'],
        ['icon' => '🧑‍🏫', 'text' => 'Pendamping relawan untuk ketertiban anak.'],
    ];

    $kegiatanAnak = [
        ['icon' => '📖', 'text' => 'Dongeng Islami & tahfidz akhir pekan.'],
        ['icon' => '🗣️', 'text' => 'Kelas tahsin keluarga.'],
        ['icon' => '🌿', 'text' => 'Workshop adab & akhlak anak.'],
    ];

    $reminders = [
        [
            'kind' => 'Hadits',
            'arabic' => 'إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ',
            'latin' => 'Innamal a‘mālu bin-niyyāt.',
            'meaning' => 'Sesungguhnya amal itu tergantung niatnya.',
            'ref' => 'HR. Bukhari & Muslim',
            'tags' => ['Niat', 'Ikhlas', 'Konsisten'],
        ],
        [
            'kind' => "Al-Qur'an",
            'arabic' => 'أَلَا بِذِكْرِ اللَّهِ تَطْمَئِنُّ الْقُلُوبُ',
            'latin' => "Alā bi dzikrillāhi taṭma'innul-qulūb.",
            'meaning' => 'Dengan mengingat Allah, hati menjadi tenang.',
            'ref' => "QS. Ar-Ra'd: 28",
            'tags' => ['Dzikir', 'Tenang', 'Hati'],
        ],
        [
            'kind' => "Al-Qur'an",
            'arabic' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا',
            'latin' => "Fa inna ma'al-'usri yusrā.",
            'meaning' => 'Bersama kesulitan ada kemudahan.',
            'ref' => 'QS. Al-Insyirah: 5',
            'tags' => ['Sabar', 'Optimis', 'Harapan'],
        ],
        [
            'kind' => 'Hadits',
            'arabic' => 'خَيْرُ النَّاسِ أَنْفَعُهُمْ لِلنَّاسِ',
            'latin' => "Khairun-nāsi anfa'uhum lin-nās.",
            'meaning' => 'Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia.',
            'ref' => 'Hadits masyhur',
            'tags' => ['Manfaat', 'Sosial', 'Amanah'],
        ],
    ];

    $formatRupiah = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $readingTime  = fn($artikel) => max(1, (int) ceil(str_word_count(strip_tags($artikel->content ?? '')) / 200));
    $artikelDate  = fn($artikel) => optional($artikel->created_at)->locale('id')->translatedFormat('d M Y');

    $featuredArtikel = $artikels->first();
    $otherArtikels   = $artikels->skip(1);
    $heroKegiatans   = $kegiatans->take(2);

    // ====== NEW: Mosque image slot + Jadwal Sholat ======
    $masjidImage = $masjidImage ?? 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';

    $fallbackSholat = [
        ['name' => 'Subuh',   'time' => '04:45'],
        ['name' => 'Dzuhur',  'time' => '12:05'],
        ['name' => 'Ashar',   'time' => '15:20'],
        ['name' => 'Maghrib', 'time' => '18:25'],
        ['name' => 'Isya',    'time' => '19:35'],
    ];

    $prayerTimes = $fallbackSholat;

    if (!empty($jadwalSholat) && is_array($jadwalSholat)) {
        $isAssoc = array_keys($jadwalSholat) !== range(0, count($jadwalSholat) - 1);
        if ($isAssoc) {
            $prayerTimes = collect($jadwalSholat)->map(fn($t, $n) => ['name' => (string)$n, 'time' => (string)$t])->values()->all();
        } else {
            $prayerTimes = $jadwalSholat;
        }
    }

// next prayer highlight (WITA)
$tz = 'Asia/Makassar';
$now = \Carbon\Carbon::now($tz);
$nextPrayerName = null;

try {
    $today = \Carbon\Carbon::today($tz);
    $candidates = collect($prayerTimes)
        ->map(function ($p) use ($today) {
            $time = data_get($p, 'time', '');
            $dt = null;

            if (preg_match('/^\d{2}:\d{2}$/', $time)) {
                $dt = $today->copy()->setTimeFromTimeString($time);
            }

            return [
                'name' => data_get($p, 'name', ''),
                'time' => $time,
                'dt'   => $dt,
            ];
        })
        ->filter(fn ($x) => $x['dt'] instanceof \Carbon\Carbon)
        ->sortBy('dt')
        ->values();

    $next = $candidates->first(fn ($x) => $x['dt']->greaterThan($now));
    if (!$next) $next = $candidates->first();

    $nextPrayerName = $next['name'] ?? null;
} catch (\Throwable $e) {
    $nextPrayerName = null;
}

@endphp

<div class="min-h-screen scroll-smooth overflow-x-hidden bg-[#70978C] text-[#1E4D40] pb-[calc(88px+env(safe-area-inset-bottom))]">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Amiri:wght@400;700&display=swap');
    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system; }
    h1,h2,h3,.heading { font-family: Poppins, ui-sans-serif, system-ui, -apple-system; }
    .arabic{font-family:'Amiri','Scheherazade New',serif;}
    .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
    .scrollbar-none::-webkit-scrollbar{display:none;}
    section[id]{ scroll-margin-top: 96px; }
    .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
    .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}
    .home-shell{max-width:1152px;}
    .snap-scroll{scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch; touch-action:pan-x; overscroll-behavior-x:contain;}
    .snap-item{scroll-snap-align:start;}

    /* Bottom nav active state (palette baru) */
    .bottom-item[data-active="true"]{
      background: rgba(235,176,77,0.95);
      color: #1E4D40;
      box-shadow: 0 10px 30px -18px rgba(30,77,64,0.55);
    }
    .bottom-item[data-active="false"]{ background: transparent; color: rgba(255,255,255,0.92); }
  </style>

  {{-- HERO (struktur baru + jadwal sholat + slot gambar masjid) --}}
  <section id="beranda" class="relative overflow-hidden pt-24">
    <div aria-hidden class="absolute inset-0 -z-20">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_10%,rgba(255,255,255,0.35),transparent_55%)]"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_20%,rgba(235,176,77,0.25),transparent_55%)]"></div>
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_55%_85%,rgba(30,77,64,0.25),transparent_60%)]"></div>
    </div>

    <div class="relative mx-auto home-shell max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
      <div class="flex justify-end lg:hidden mb-3">
        <button data-open-drawer
          class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/50 bg-white/20 text-white shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:shadow-lg"
          aria-label="Buka menu" type="button">☰</button>
      </div>

      <div class="grid gap-8 lg:grid-cols-12 lg:items-start opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
        {{-- Left: Headline + CTA --}}
        <div class="lg:col-span-7">
          <div class="rounded-[28px] border border-white/40 bg-white/20 p-6 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur sm:p-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-[#EBB04D]/95 px-4 py-2 text-xs font-semibold text-[#1E4D40] ring-1 ring-white/60">
              <span aria-hidden>✨</span> Ruang Digital Jamaah Masjid
            </div>

            <h1 class="heading mt-4 text-4xl font-extrabold leading-tight text-[#1E4D40] sm:text-5xl">
              Masjid Agung Al-A&#39;la
            </h1>

            <p class="mt-3 max-w-2xl text-sm text-[#1E4D40]/85 sm:text-base">
              Pantau kegiatan, baca artikel islami, dan berdonasi dengan aman — nyaman dipakai di HP.
            </p>

            <div class="mt-6 grid grid-cols-2 gap-3 lg:hidden">
              <a href="{{ route('kegiatan.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-[#1E4D40] shadow-lg transition active:scale-[0.98]">
                Jadwal
              </a>
              <a href="{{ route('donasi.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1E4D40] px-4 py-3 text-sm font-semibold text-white shadow-lg transition active:scale-[0.98]">
                Donasi
              </a>
            </div>

            <div class="mt-6 hidden flex-col gap-3 sm:flex-row sm:items-center sm:justify-center lg:flex lg:justify-start">
              <a href="{{ route('kegiatan.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#1E4D40] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:brightness-110">
                Jelajahi Kegiatan →
              </a>
              <a href="{{ route('artikel.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-full border border-white/70 bg-white/20 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-white/30">
                Baca Artikel ↗
              </a>
              <a href="{{ route('donasi.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#EBB04D] px-6 py-3 text-sm font-semibold text-[#1E4D40] shadow-lg transition hover:-translate-y-0.5 hover:brightness-110">
                Mulai Donasi ❤
              </a>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
              @foreach($trustBadges as $b)
                <span class="inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/15 px-3 py-1 text-xs font-semibold text-white">
                  <span aria-hidden>{{ $b['icon'] }}</span> {{ $b['label'] }}
                </span>
              @endforeach
            </div>
          </div>
        </div>

{{-- Right: Mosque image + Jadwal Sholat --}}
<div class="lg:col-span-5">
  <div class="overflow-hidden rounded-[28px] border border-white/45 bg-white/20 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur">
    {{-- SLOT GAMBAR MASJID --}}
    <div class="relative aspect-[16/10] w-full bg-white/10">
      <img
        src="{{ $masjidImage }}"
        alt="Gambar Masjid"
        loading="lazy"
        referrerpolicy="no-referrer"
        class="h-full w-full object-cover"
        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-[#1E4D40]/55 via-transparent to-transparent"></div>

      <div class="absolute left-4 top-4">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/40 backdrop-blur">
          🕌 Foto Masjid (bisa diganti)
        </span>
      </div>
    </div>

    {{-- JADWAL SHOLAT --}}
    <div class="p-5 sm:p-6">
      <div class="flex items-start justify-between gap-3">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Jadwal Sholat</p>
          <p class="mt-1 text-sm font-semibold text-white/95">{{ $todayLabel }}</p>
          <p class="mt-0.5 text-xs text-white/85">Gianyar, Bali</p>

          {{-- realtime + countdown --}}
          <p class="mt-0.5 text-xs text-white/85 leading-tight">
            Waktu sekarang: <span id="realtimeClock" class="font-semibold">--:--:--</span> WITA
          </p>
          <p class="mt-0.5 text-xs text-white/85 leading-tight">
            Hitung mundur: <span id="nextCountdown" class="font-semibold">--:--:--</span>
          </p>
        </div>

        {{-- badge next (dibikin bisa diubah JS) --}}
        <span id="nextPrayerBadge" class="inline-flex items-center rounded-full bg-[#EBB04D] px-3 py-1 text-xs font-semibold text-[#1E4D40]">
          Next: {{ $nextPrayerName ?? 'Hari ini' }}
        </span>
      </div>

      <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
        @foreach($prayerTimes as $p)
          @php
            $nm = (string) data_get($p,'name','');
            $tm = (string) data_get($p,'time','--:--');
            $isNext = $nextPrayerName && ($nm === $nextPrayerName);
          @endphp

          <div
            class="prayer-card rounded-2xl border border-white/50 {{ $isNext ? 'bg-[#EBB04D]' : 'bg-white/15' }} px-4 py-3"
            data-prayer="{{ $nm }}"
            data-time="{{ $tm }}"
          >
            <p class="prayer-name text-xs font-semibold {{ $isNext ? 'text-[#1E4D40]' : 'text-white/85' }}">
              {{ $nm }}
            </p>
            <p class="prayer-time heading mt-0.5 text-lg font-extrabold {{ $isNext ? 'text-[#1E4D40]' : 'text-white' }}">
              {{ $tm }}
            </p>
          </div>
        @endforeach
      </div>

      <div class="mt-4 grid grid-cols-2 gap-2">
        <a href="{{ route('kegiatan.index') }}"
          class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5">
          Lihat Agenda
        </a>
        <a href="{{ route('donasi.index') }}"
          class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/70 bg-[#1E4D40] px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:brightness-110">
          Donasi Sekarang
        </a>
      </div>

      <p class="mt-3 text-[11px] text-white/80">
        *Data jadwal sholat bisa kamu inject dari controller lewat <span class="font-semibold">$jadwalSholat</span>.
      </p>
    </div>
  </div>
</div>

{{-- HERO GRID DITUTUP BIAR SECTION SETELAHNYA NGGAK KETELEN --}}
</div>  {{-- end .grid --}}
</div>  {{-- end .home-shell wrapper --}}
</section>

  {{-- STATS --}}
  <section id="stats" class="relative py-10 sm:py-14">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="rounded-[28px] border border-white/45 bg-white/25 p-5 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur sm:p-8">
        <div class="mb-4">
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Ringkasan</p>
          <h2 class="heading mt-2 text-2xl font-extrabold text-white">Statistik Masjid</h2>
          <p class="mt-1 text-sm text-white/85">Singkat, rapi, dan enak discroll.</p>
        </div>

        <div class="flex snap-scroll snap-x snap-mandatory gap-3 overflow-x-auto pb-2 scrollbar-none sm:grid sm:grid-cols-2 sm:gap-4 sm:overflow-visible lg:grid-cols-4">
          @forelse($stats as $s)
            <div class="w-[70%] flex-none snap-item snap-start rounded-2xl border border-white/50 bg-white px-5 py-5 text-[#1E4D40] shadow-sm transition hover:-translate-y-1 sm:w-auto sm:flex-auto">
              <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-content-center rounded-2xl bg-[#70978C]/15 text-2xl text-[#1E4D40] ring-1 ring-[#1E4D40]/10">
                  {{ $s['icon'] }}
                </div>
                <div>
                  <p class="heading text-3xl font-extrabold text-[#1E4D40]">{{ $s['value'] }}</p>
                  <p class="text-sm text-[#1E4D40]/75">{{ $s['label'] }}</p>
                </div>
              </div>
            </div>
          @empty
            <p class="text-sm text-white/85">Statistik belum tersedia.</p>
          @endforelse
        </div>

        <p class="mt-2 text-xs text-white/80 sm:hidden">Geser untuk melihat statistik lain →</p>
      </div>
    </div>
  </section>

  {{-- KEGIATAN --}}
  <section id="kegiatan" class="py-12 sm:py-16">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Highlight</p>
          <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Kegiatan Terdekat</h2>
          <p class="mt-1 text-sm text-white/85">Swipe card di HP biar cepat.</p>
        </div>
        <a href="{{ route('kegiatan.index') }}" class="text-sm font-semibold text-white underline decoration-white/60 underline-offset-4 hover:decoration-white">
          Semua Kegiatan →
        </a>
      </div>

      <div class="flex snap-scroll snap-x snap-mandatory gap-4 overflow-x-auto pb-2 scrollbar-none md:grid md:grid-cols-2 md:overflow-visible lg:grid-cols-3" style="WebkitOverflowScrolling:touch;">
        @forelse($kegiatans as $kegiatan)
          <article class="w-[86%] max-w-sm flex-none snap-item snap-start overflow-hidden rounded-[28px] border border-white/50 bg-white text-[#1E4D40] shadow-md transition hover:-translate-y-1 md:w-auto md:max-w-none md:flex-auto">
            <div class="relative aspect-[16/10] w-full bg-[#70978C]/10">
              <img loading="lazy"
                src="{{ $kegiatan->poster ?: 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1400&q=80' }}"
                alt="{{ $kegiatan->nama_kegiatan }}"
                class="h-full w-full object-cover"
                referrerpolicy="no-referrer" />
              <div class="absolute inset-0 bg-gradient-to-t from-[#1E4D40]/35 via-transparent to-transparent"></div>
            </div>

            <div class="space-y-3 p-5">
              <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="rounded-full bg-[#70978C]/15 px-3 py-1 font-semibold text-[#1E4D40] ring-1 ring-[#1E4D40]/10">
                  {{ $kegiatan->kategori ?? 'Program Masjid' }}
                </span>
                <span class="rounded-full bg-[#1E4D40]/5 px-3 py-1 font-semibold text-[#1E4D40]/75">
                  🗓 {{ optional($kegiatan->tanggal_mulai)->translatedFormat('d M Y') ?? 'Jadwal menyusul' }}
                </span>
                <span class="rounded-full bg-[#1E4D40]/5 px-3 py-1 font-semibold text-[#1E4D40]/75">
                   {{ $kegiatan->lokasi ?? 'Lokasi menyusul' }}
                </span>
              </div>

              <h3 class="heading text-lg font-semibold text-[#1E4D40] line-clamp-2">{{ $kegiatan->nama_kegiatan }}</h3>
              <p class="text-sm leading-relaxed text-[#1E4D40]/75 line-clamp-3">
                {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 140) }}
              </p>

              <div class="grid grid-cols-2 gap-2 pt-1">
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#1E4D40] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:brightness-110">
                  Daftar
                </a>
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-[#1E4D40]/15 bg-white px-4 py-2 text-sm font-semibold text-[#1E4D40] shadow-sm transition hover:bg-[#70978C]/10">
                  ℹ️ Detail
                </a>
              </div>
            </div>
          </article>
        @empty
          <p class="text-sm text-white/85">Belum ada kegiatan tersedia.</p>
        @endforelse
      </div>

      <p class="mt-2 text-xs text-white/85 md:hidden">Swipe kiri/kanan untuk lihat kegiatan lain →</p>
    </div>
  </section>

  {{-- PROFIL --}}
  <section id="profil" class="py-12 sm:py-16">
    <div class="mx-auto grid home-shell max-w-7xl grid-cols-1 items-center gap-10 px-4 sm:px-6 md:grid-cols-2 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="space-y-5">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Tentang Masjid</p>
        <h2 class="heading text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Visi & Misi</h2>
        <p class="text-sm text-white/85">Masjid sebagai pusat ibadah, pendidikan, dan pemberdayaan jamaah.</p>

        <div class="rounded-[28px] border border-white/50 bg-white p-6 text-[#1E4D40] shadow-md space-y-4">
          <div>
            <h3 class="text-sm font-semibold">Visi</h3>
            <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-[#1E4D40]/75">
              <li>Pusat peradaban Islam modern yang inklusif dan berkemajuan.</li>
              <li>Menghadirkan jamaah berilmu dan berdaya saing.</li>
            </ul>
          </div>
          <div>
            <h3 class="text-sm font-semibold">Misi</h3>
            <ul class="mt-2 list-disc space-y-1 pl-4 text-sm text-[#1E4D40]/75">
              <li>Menyelenggarakan ibadah berjamaah yang khusyuk.</li>
              <li>Mendorong pendidikan Islam sepanjang hayat.</li>
              <li>Memperkuat program sosial dan ekonomi umat.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="relative aspect-[4/3] w-full overflow-hidden rounded-[28px] border border-white/45 shadow-lg">
        <img loading="lazy" src="{{ $masjidImage }}" alt="Interior masjid"
          class="h-full w-full object-cover"
          referrerpolicy="no-referrer"
          onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';" />
        <div class="absolute inset-0 bg-gradient-to-t from-[#1E4D40]/55 via-transparent to-transparent"></div>
      </div>
    </div>
  </section>

  {{-- PROGRAM KELUARGA --}}
  <section id="program" class="py-12 sm:py-16">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="relative overflow-hidden rounded-[32px] border border-white/45 bg-white/25 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
        <div aria-hidden class="pointer-events-none absolute inset-0 -z-10">
          <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(255,255,255,0.35),transparent_55%)]"></div>
          <div class="absolute inset-y-0 right-0 w-1/2 bg-[radial-gradient(circle_at_70%_30%,rgba(235,176,77,0.25),transparent_55%)]"></div>
        </div>

        <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-12 lg:items-stretch lg:gap-10 lg:p-10">
          <div class="lg:col-span-6">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/90">Program Keluarga</p>
            <h2 class="heading mt-3 text-3xl font-extrabold leading-tight text-white sm:text-4xl">Masjid Ramah Anak</h2>
            <p class="mt-3 max-w-xl text-sm text-white/85 sm:text-base">Layout rapi, mudah dibaca, dan tetap modern.</p>

            <div class="mt-6 rounded-[28px] border border-white/50 bg-white p-6 text-[#1E4D40] shadow-xl">
              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <p class="heading text-base font-extrabold text-[#1E4D40]">Fasilitas</p>
                  <ul class="mt-3 space-y-2">
                    @foreach($fasilitas as $i)
                      <li class="flex items-start gap-2 text-sm text-[#1E4D40]/80">
                        <span class="mt-0.5 grid h-7 w-7 place-items-center rounded-xl bg-[#70978C]/15 ring-1 ring-[#1E4D40]/10" aria-hidden>{{ $i['icon'] }}</span>
                        <span class="leading-relaxed">{{ $i['text'] }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div>
                  <p class="heading text-base font-extrabold text-[#1E4D40]">Kegiatan</p>
                  <ul class="mt-3 space-y-2">
                    @foreach($kegiatanAnak as $i)
                      <li class="flex items-start gap-2 text-sm text-[#1E4D40]/80">
                        <span class="mt-0.5 grid h-7 w-7 place-items-center rounded-xl bg-[#EBB04D]/25 ring-1 ring-[#1E4D40]/10" aria-hidden>{{ $i['icon'] }}</span>
                        <span class="leading-relaxed">{{ $i['text'] }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>

              <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <a href="{{ route('perpustakaan.index') }}"
                  class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1E4D40] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:brightness-110">
                  <span aria-hidden>🧾</span> Lihat Program
                </a>
                <a href="{{ route('kegiatan.index') }}"
                  class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EBB04D] px-5 py-3 text-sm font-semibold text-[#1E4D40] shadow-md transition hover:-translate-y-0.5 hover:brightness-110">
                  <span aria-hidden>🗓️</span> Jadwal Anak
                </a>
              </div>
            </div>
          </div>

          <div class="lg:col-span-6">
            <div class="relative h-full overflow-hidden rounded-[28px] border border-white/45 bg-white/15 shadow-2xl">
              <div class="relative h-full min-h-[420px]">
                <img
                  src="https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=2000&q=80"
                  alt="Program keluarga masjid"
                  loading="lazy"
                  referrerpolicy="no-referrer"
                  onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';"
                  class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-b from-[#1E4D40]/45 via-[#1E4D40]/10 to-[#1E4D40]/70"></div>

                <div class="absolute left-6 top-6 flex flex-wrap items-center gap-2">
                  <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/40 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-[#EBB04D]"></span>
                    Program keluarga
                  </span>
                  <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/40 backdrop-blur">
                    <span aria-hidden></span> Ramah anak
                  </span>
                </div>

                <div class="absolute bottom-6 left-6 right-6">
                  <div class="rounded-[28px] bg-white p-5 text-[#1E4D40] shadow-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-[#1E4D40]/60">Highlight</p>
                    <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                      <div>
                        <p class="heading text-xl font-extrabold">Akhir Pekan Ceria</p>
                        <p class="mt-1 text-sm text-[#1E4D40]/75">Dongeng Islami • Tahfidz • Games edukatif</p>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#70978C]/15 px-3 py-1 text-xs font-semibold text-[#1E4D40]"><span aria-hidden>⏱️</span> Sabtu–Ahad</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#1E4D40]/5 px-3 py-1 text-xs font-semibold text-[#1E4D40]"><span aria-hidden>📍</span> Aula</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-[#1E4D40]/5 px-3 py-1 text-xs font-semibold text-[#1E4D40]"><span aria-hidden>🕗</span> 08:00</span>
                      </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                      <a href="{{ route('kegiatan.index') }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#1E4D40] px-4 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:brightness-110">
                        <span aria-hidden></span> Daftar Anak
                      </a>
                      <a href="{{ route('kegiatan.index') }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-[#1E4D40]/15 bg-white px-4 py-3 text-sm font-semibold text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#70978C]/10">
                        <span aria-hidden>ℹ</span> Detail
                      </a>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  {{-- ARTIKEL + DONASI --}}
  <section id="artikel" class="py-12 sm:py-16">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="rounded-[32px] border border-white/45 bg-white/25 p-6 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur sm:p-8">
        <div class="mb-5 flex items-end justify-between gap-3">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Artikel</p>
            <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white">Artikel Terbaru</h2>
            <p class="mt-1 text-sm text-white/85">Ringkas, mudah discan, enak di HP.</p>
          </div>
          <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-white underline decoration-white/60 underline-offset-4 hover:decoration-white">Semua</a>
        </div>

        <div class="grid gap-4 lg:grid-cols-5">
          @if($featuredArtikel)
            <a href="{{ route('artikel.show', $featuredArtikel) }}" class="group relative overflow-hidden rounded-[28px] border border-white/45 bg-white text-[#1E4D40] shadow-md lg:col-span-3">
              <div class="relative h-56 w-full sm:h-60">
                <img
                  src="{{ $featuredArtikel->thumbnail ?: $masjidImage }}"
                  alt="{{ $featuredArtikel->title }}"
                  class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.02]"
                  referrerpolicy="no-referrer" />
                <div class="absolute inset-0 bg-gradient-to-t from-[#1E4D40]/70 via-[#1E4D40]/10 to-transparent"></div>
                <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-[#EBB04D] px-3 py-1 text-xs font-semibold text-[#1E4D40]">
                  Featured
                </div>
              </div>

              <div class="space-y-2 p-5">
                <p class="text-xs text-[#1E4D40]/70">
                  {{ $artikelDate($featuredArtikel) }} • {{ $readingTime($featuredArtikel) }} menit baca
                </p>
                <h3 class="heading text-xl font-extrabold line-clamp-2">{{ $featuredArtikel->title }}</h3>
                <p class="text-sm text-[#1E4D40]/75 line-clamp-3">
                  {{ \Illuminate\Support\Str::limit(strip_tags($featuredArtikel->content ?? ''), 160) }}
                </p>
                <div class="pt-1">
                  <span class="inline-flex items-center gap-2 text-sm font-semibold text-[#1E4D40]">
                    Baca selengkapnya <span aria-hidden>→</span>
                  </span>
                </div>
              </div>
            </a>
          @endif

          <div class="grid gap-3 lg:col-span-2">
            @forelse($otherArtikels->take(4) as $a)
              <a href="{{ route('artikel.show', $a) }}" class="group flex gap-3 rounded-[24px] border border-white/45 bg-white p-4 text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5">
                <div class="h-16 w-20 flex-none overflow-hidden rounded-2xl bg-[#70978C]/15">
                  <img
                    src="{{ $a->thumbnail ?: $masjidImage }}"
                    alt="{{ $a->title }}"
                    class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.02]"
                    referrerpolicy="no-referrer" />
                </div>
                <div class="min-w-0">
                  <p class="text-[11px] text-[#1E4D40]/70">{{ $artikelDate($a) }} • {{ $readingTime($a) }} mnt</p>
                  <p class="mt-1 text-sm font-semibold line-clamp-2">{{ $a->title }}</p>
                </div>
              </a>
            @empty
              <p class="text-sm text-white/85">Belum ada artikel.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- DONASI --}}
  <section id="donasi" class="py-12 sm:py-16">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="rounded-[32px] border border-white/45 bg-white/25 p-6 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur sm:p-8">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Donasi</p>
            <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white">Program Donasi</h2>
            <p class="mt-1 text-sm text-white/85">Cepat pilih nominal, lanjut ke halaman donasi.</p>
          </div>
          <a href="{{ route('donasi.index') }}" class="text-sm font-semibold text-white underline decoration-white/60 underline-offset-4 hover:decoration-white">
            Semua Donasi →
          </a>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
          {{-- Quick Amount --}}
          <div class="rounded-[28px] border border-white/45 bg-white p-5 text-[#1E4D40] shadow-sm">
            <p class="heading text-lg font-extrabold">Cepat Donasi</p>
            <p class="mt-1 text-sm text-[#1E4D40]/75">Pilih nominal, lalu lanjut.</p>

            <div class="mt-4 grid grid-cols-3 gap-2">
              @foreach($quickAmounts as $qa)
                <a href="{{ route('donasi.index') }}"
                  class="flex items-center justify-center gap-2 rounded-2xl border border-[#1E4D40]/15 bg-white px-3 py-3 text-xs font-semibold text-[#1E4D40] transition hover:bg-[#70978C]/10">
                  <span aria-hidden>{{ $qa['icon'] }}</span> {{ $qa['label'] }}
                </a>
              @endforeach
            </div>

            <a href="{{ route('donasi.index') }}"
              class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#EBB04D] px-4 py-3 text-sm font-semibold text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5 hover:brightness-110">
              💳 Lanjut Donasi
            </a>

            <div class="mt-4 flex flex-wrap gap-2">
              @foreach($trustBadges as $b)
                <span class="inline-flex items-center gap-2 rounded-full bg-[#70978C]/15 px-3 py-1 text-xs font-semibold text-[#1E4D40] ring-1 ring-[#1E4D40]/10">
                  <span aria-hidden>{{ $b['icon'] }}</span> {{ $b['label'] }}
                </span>
              @endforeach
            </div>
          </div>

          {{-- Donasi cards --}}
          <div class="lg:col-span-2">
            <div class="flex snap-scroll snap-x snap-mandatory gap-4 overflow-x-auto pb-2 scrollbar-none md:grid md:grid-cols-2 md:overflow-visible">
              @forelse($donasis as $d)
                @php
                  $target = (int) ($d->target ?? 0);
                  $terkumpul = (int) ($d->terkumpul ?? 0);
                  $progress = ($target > 0) ? min(100, ($terkumpul / $target) * 100) : 0;
                  $sisa = max(0, $target - $terkumpul);

                  $status = 'Aktif';
                  $statusClasses = 'border-[#1E4D40]/15 bg-[#70978C]/15 text-[#1E4D40]';
                  $dotClasses = 'bg-[#EBB04D]';
                @endphp

                <article class="w-[86%] flex-none snap-item snap-start rounded-[28px] border border-white/45 bg-white p-5 text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5 md:w-auto">
                  <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                      <p class="text-base font-semibold text-[#1E4D40]">
                        <span class="mr-2" aria-hidden>💠</span>
                        {{ $d->judul }}
                      </p>
                      <p class="mt-1 text-xs text-[#1E4D40]/70">
                        Target <span class="font-semibold text-[#1E4D40]">{{ $formatRupiah($target) }}</span>
                        <span class="mx-2 text-[#1E4D40]/20">•</span>
                        <span class="font-semibold text-[#1E4D40]">{{ number_format($progress, 0) }}%</span>
                      </p>
                    </div>

                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $statusClasses }}">
                      <span class="h-1.5 w-1.5 rounded-full {{ $dotClasses }}"></span>
                      {{ $status }}
                    </span>
                  </div>

                  <div class="mt-4">
                    <div class="h-2 w-full rounded-full bg-[#1E4D40]/10">
                      <div class="h-2 rounded-full bg-[#1E4D40] transition-[width] duration-700" style="width: {{ $progress }}%"></div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-[11px] text-[#1E4D40]/75">
                      <div class="rounded-2xl border border-[#1E4D40]/10 bg-[#70978C]/10 px-3 py-2">
                        <p class="text-[#1E4D40]/65">Terkumpul</p>
                        <p class="mt-0.5 text-xs font-semibold text-[#1E4D40]">{{ $formatRupiah($terkumpul) }}</p>
                      </div>
                      <div class="rounded-2xl border border-[#1E4D40]/10 bg-[#70978C]/10 px-3 py-2">
                        <p class="text-[#1E4D40]/65">Sisa</p>
                        <p class="mt-0.5 text-xs font-semibold text-[#1E4D40]">{{ $formatRupiah($sisa) }}</p>
                      </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                      {{-- keep route target same --}}
                      <a href="{{ route('donasi.show', $d) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#1E4D40] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:brightness-110">
                        <span aria-hidden>💳</span> Donasi
                      </a>
                      <a href="{{ route('donasi.show', $d) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-[#1E4D40]/15 bg-white px-4 py-2 text-sm font-semibold text-[#1E4D40] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#70978C]/10">
                        <span aria-hidden>📈</span> Laporan
                      </a>
                    </div>
                  </div>
                </article>
              @empty
                <p class="text-sm text-white/85">Belum ada program donasi aktif.</p>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- PENGINGAT HARIAN (Carousel) --}}
  <section class="relative py-12 sm:py-14">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="relative overflow-hidden rounded-[32px] border border-white/45 bg-white/25 p-6 shadow-[0_24px_60px_-40px_rgba(30,77,64,0.55)] backdrop-blur sm:p-8">
        <div class="mb-4 flex items-end justify-between gap-3">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Pengingat</p>
            <h2 class="heading mt-2 text-2xl font-extrabold text-white">Pengingat Harian</h2>
            <p class="mt-1 text-sm text-white/85">Swipe untuk ganti kartu.</p>
          </div>
          <div class="hidden items-center gap-2 sm:flex">
            <button id="reminderPrev" type="button" aria-label="Sebelumnya"
              class="rounded-2xl border border-white/60 bg-white/20 px-3 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/30">←</button>
            <button id="reminderNext" type="button" aria-label="Berikutnya"
              class="rounded-2xl border border-white/60 bg-white/20 px-3 py-2 text-xs font-semibold text-white backdrop-blur transition hover:bg-white/30">→</button>
          </div>
        </div>

        <div id="reminderScroller"
          class="scrollbar-none snap-scroll flex snap-x snap-mandatory gap-3 overflow-x-auto px-1 py-2 sm:px-2"
          style="WebkitOverflowScrolling:touch;">
          @foreach($reminders as $idx => $it)
            <article data-card class="snap-item snap-center">
              <div class="relative w-[84vw] max-w-[560px] overflow-hidden rounded-[32px] border border-white/55 bg-white shadow-[0_18px_56px_-32px_rgba(0,0,0,0.45)] sm:w-[70vw]">
                <div class="relative px-5 py-6 text-[#1E4D40] sm:px-7 sm:py-7">
                  <div class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-2xl bg-[#70978C]/15 text-[#1E4D40] ring-1 ring-[#1E4D40]/10">
                    <span aria-hidden class="text-base">❝</span>
                  </div>

                  <p class="text-center text-xs font-semibold uppercase tracking-[0.45em] text-[#1E4D40]/60">PENGINGAT HARIAN</p>

                  <p class="arabic mt-3 text-center text-xl leading-[2.15] text-[#1E4D40] sm:text-2xl" dir="rtl">
                    {{ $it['arabic'] }}
                  </p>

                  @if(!empty($it['latin']))
                    <p class="mt-2.5 text-center text-xs text-[#1E4D40]/70 sm:text-sm">{{ $it['latin'] }}</p>
                  @endif

                  <p class="mt-3.5 text-center text-xs font-medium text-[#1E4D40]/80 sm:text-sm">{{ $it['meaning'] }}</p>

                  <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#EBB04D] px-3.5 py-1.5 text-xs font-semibold text-[#1E4D40]">
                      <span class="h-1.5 w-1.5 rounded-full bg-[#1E4D40]"></span>
                      {{ $it['ref'] }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full bg-[#70978C]/15 px-3.5 py-1.5 text-xs font-semibold text-[#1E4D40] ring-1 ring-[#1E4D40]/10">
                      {{ $it['kind'] }}
                    </span>
                  </div>

                  @if(!empty($it['tags']))
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                      @foreach($it['tags'] as $t)
                        <span class="rounded-full border border-[#1E4D40]/10 bg-white px-3 py-1 text-[11px] font-semibold text-[#1E4D40]/80">
                          #{{ $t }}
                        </span>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            </article>
          @endforeach
        </div>

        <div id="reminderDots" class="mt-4 flex justify-center gap-2"></div>

        <p class="mt-4 rounded-2xl border border-white/45 bg-white/20 p-4 text-xs text-white/85">
          Tips: navigasi cepat lewat <span class="font-semibold text-white">Bottom Bar</span>.
        </p>
      </div>
    </div>
  </section>

  {{-- BOTTOM NAV (fungsi tetap: data-bottom-item + data-bottom-id) --}}
  <nav class="fixed inset-x-0 bottom-0 z-50 pb-[env(safe-area-inset-bottom)]">
    <div class="mx-auto home-shell max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="rounded-[26px] border border-white/25 bg-[#1E4D40]/92 p-2 shadow-[0_18px_60px_-35px_rgba(0,0,0,0.55)] backdrop-blur">
        <div class="grid grid-cols-4 gap-1">
          @foreach($bottomNav as $n)
            <a href="{{ $n['href'] }}"
              data-bottom-item
              data-bottom-id="{{ $n['id'] }}"
              class="bottom-item flex flex-col items-center justify-center gap-0.5 rounded-2xl px-2 py-2.5 text-center text-xs font-semibold transition"
              aria-label="{{ $n['label'] }}">
              <span class="text-base" aria-hidden>{{ $n['icon'] }}</span>
              <span class="text-[11px]">{{ $n['label'] }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </nav>

  {{-- MOBILE DRAWER (id + data-* tetap) --}}
  <div id="mobileDrawerRoot" class="pointer-events-none fixed inset-0 z-[60]">
    <div id="mobileDrawerOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity"></div>

    <aside id="mobileDrawer" class="absolute right-0 top-0 h-full w-[86vw] max-w-sm translate-x-full bg-[#1E4D40] text-white shadow-2xl transition-transform">
      <div class="flex h-full flex-col p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-white/70">Menu</p>
            <p class="heading text-lg font-extrabold">Masjid Agung Al-A&#39;la</p>
          </div>
          <button data-close-drawer class="grid h-11 w-11 place-items-center rounded-2xl border border-white/25 bg-white/10 text-white" aria-label="Tutup menu" type="button">
            ✕
          </button>
        </div>

        <div class="mt-5 space-y-2">
          <a href="#beranda" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Beranda</span><span class="text-white/60">→</span>
          </a>
          <a href="#kegiatan" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Kegiatan</span><span class="text-white/60">→</span>
          </a>
          <a href="#artikel" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Artikel</span><span class="text-white/60">→</span>
          </a>
          <a href="#donasi" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Donasi</span><span class="text-white/60">→</span>
          </a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2">
          <button class="rounded-2xl border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15" type="button">
            Masuk
          </button>
          <button class="rounded-2xl bg-[#EBB04D] px-4 py-3 text-sm font-semibold text-[#1E4D40] shadow transition hover:brightness-110" type="button">
            Daftar
          </button>
        </div>

        <div class="mt-4 rounded-2xl border border-white/15 bg-white/10 p-4 text-xs text-white/75">
          Tips: navigasi cepat lewat <span class="font-semibold text-white">Bottom Bar</span>.
        </div>

        <div class="mt-auto pt-4 text-[11px] text-white/60">
          © {{ date('Y') }} Masjid Agung Al-A&#39;la
        </div>
      </div>
    </aside>
  </div>

  {{-- SCRIPTS (fungsi tetap: drawer, animate, bottom nav active, reminder carousel) --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // ===== Drawer open/close =====
      const drawerRoot = document.getElementById('mobileDrawerRoot');
      const overlay = document.getElementById('mobileDrawerOverlay');
      const drawer = document.getElementById('mobileDrawer');
      const openBtns = document.querySelectorAll('[data-open-drawer]');
      const closeBtns = document.querySelectorAll('[data-close-drawer]');

      function setDrawer(open) {
        if (!drawerRoot || !overlay || !drawer) return;
        if (open) {
          drawerRoot.classList.remove('pointer-events-none');
          overlay.classList.remove('opacity-0'); overlay.classList.add('opacity-100');
          drawer.classList.remove('translate-x-full'); drawer.classList.add('translate-x-0');
          document.body.style.overflow = 'hidden';
        } else {
          drawerRoot.classList.add('pointer-events-none');
          overlay.classList.add('opacity-0'); overlay.classList.remove('opacity-100');
          drawer.classList.add('translate-x-full'); drawer.classList.remove('translate-x-0');
          document.body.style.overflow = '';
        }
      }
      openBtns.forEach(btn => btn.addEventListener('click', () => setDrawer(true)));
      closeBtns.forEach(btn => btn.addEventListener('click', () => setDrawer(false)));
      overlay?.addEventListener('click', () => setDrawer(false));
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setDrawer(false); });

      // ===== Smooth scroll for hash links (biar enak + auto close drawer) =====
      document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
          const href = a.getAttribute('href');
          if (!href || href === '#') return;
          const target = document.querySelector(href);
          if (!target) return;
          e.preventDefault();
          setDrawer(false);
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          history.pushState(null, '', href);
        });
      });

      // ===== Animate on view =====
      const animEls = Array.from(document.querySelectorAll('[data-animate]'));
      if ('IntersectionObserver' in window && animEls.length) {
        const obs = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.remove('opacity-0','translate-y-6');
              entry.target.classList.add('opacity-100','translate-y-0');
              observer.unobserve(entry.target);
            }
          });
        }, { threshold: 0.16 });

        animEls.forEach((el, idx) => {
          el.style.transitionDelay = `${Math.min(idx * 70, 420)}ms`;
          obs.observe(el);
        });
      }

      // ===== Bottom nav active section =====
      const sectionIds = ['beranda','kegiatan','artikel','donasi'];
      const sections = sectionIds.map(id => document.getElementById(id)).filter(Boolean);
      const bottomItems = Array.from(document.querySelectorAll('[data-bottom-item]'));

      function setActiveBottom(id) {
        bottomItems.forEach(el => {
          el.setAttribute('data-active', (el.getAttribute('data-bottom-id') === id) ? 'true' : 'false');
        });
      }
      setActiveBottom('beranda');

      if ('IntersectionObserver' in window && sections.length) {
        const secObs = new IntersectionObserver((entries) => {
          const visible = entries
            .filter(e => e.isIntersecting)
            .sort((a,b) => b.intersectionRatio - a.intersectionRatio)[0];
          if (visible?.target?.id) setActiveBottom(visible.target.id);
        }, { threshold: [0.2,0.35,0.5,0.65] });

        sections.forEach(sec => secObs.observe(sec));
      }

      // ===== Reminder carousel controls + dots =====
      const scroller = document.getElementById('reminderScroller');
      const prevBtn = document.getElementById('reminderPrev');
      const nextBtn = document.getElementById('reminderNext');
      const dotsWrap = document.getElementById('reminderDots');

      if (scroller && dotsWrap) {
        const cards = Array.from(scroller.querySelectorAll('[data-card]'));
        const dots = cards.map((_, i) => {
          const b = document.createElement('button');
          b.type = 'button';
          b.className = 'h-2.5 w-2.5 rounded-full bg-white/40 transition';
          b.setAttribute('aria-label', `Buka kartu ${i+1}`);
          b.dataset.dotIndex = String(i);
          dotsWrap.appendChild(b);
          return b;
        });

        function getActiveIndex() {
          // cari card paling dekat dengan tengah viewport scroller
          const rect = scroller.getBoundingClientRect();
          const mid = rect.left + rect.width / 2;
          let bestIdx = 0, bestDist = Infinity;
          cards.forEach((c, i) => {
            const r = c.getBoundingClientRect();
            const cMid = r.left + r.width / 2;
            const d = Math.abs(cMid - mid);
            if (d < bestDist) { bestDist = d; bestIdx = i; }
          });
          return bestIdx;
        }

        function setDots(activeIdx) {
          dots.forEach((d, i) => {
            d.classList.toggle('bg-white', i === activeIdx);
            d.classList.toggle('bg-white/40', i !== activeIdx);
          });
        }

        function scrollToIndex(idx) {
          const target = cards[idx];
          if (!target) return;
          const left = target.offsetLeft - (scroller.clientWidth - target.clientWidth) / 2;
          scroller.scrollTo({ left, behavior: 'smooth' });
        }

        setDots(0);

        scroller.addEventListener('scroll', () => {
          window.requestAnimationFrame(() => setDots(getActiveIndex()));
        }, { passive: true });

        dotsWrap.addEventListener('click', (e) => {
          const btn = e.target.closest('button[data-dot-index]');
          if (!btn) return;
          const idx = parseInt(btn.dataset.dotIndex, 10);
          if (!Number.isNaN(idx)) scrollToIndex(idx);
        });

        prevBtn?.addEventListener('click', () => {
          const idx = getActiveIndex();
          scrollToIndex(Math.max(0, idx - 1));
        });
        nextBtn?.addEventListener('click', () => {
          const idx = getActiveIndex();
          scrollToIndex(Math.min(cards.length - 1, idx + 1));
        });
      }
    });
  </script>
</div>


<script>
(() => {
  const TZ = 'Asia/Makassar'; // Bali = WITA
  const pad = (n) => String(n).padStart(2, '0');

  function getNowPartsTZ() {
    const parts = new Intl.DateTimeFormat('en-GB', {
      timeZone: TZ,
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false
    }).formatToParts(new Date());

    const map = {};
    for (const p of parts) {
      if (p.type !== 'literal') map[p.type] = p.value;
    }
    return map; // year, month, day, hour, minute, second
  }

  function dateFromParts(parts, hhmmss) {
    return new Date(`${parts.year}-${parts.month}-${parts.day}T${hhmmss}`);
  }

  const clockEl = document.getElementById('realtimeClock');
  const nextBadgeEl = document.getElementById('nextPrayerBadge');
  const countdownEl = document.getElementById('nextCountdown');
  const cards = Array.from(document.querySelectorAll('.prayer-card'));

  function setCardActive(card, isActive) {
    const nameEl = card.querySelector('.prayer-name');
    const timeEl = card.querySelector('.prayer-time');

    if (isActive) {
      card.classList.add('bg-[#EBB04D]');
      card.classList.remove('bg-white/15');

      nameEl && nameEl.classList.add('text-[#1E4D40]');
      nameEl && nameEl.classList.remove('text-white/85');

      timeEl && timeEl.classList.add('text-[#1E4D40]');
      timeEl && timeEl.classList.remove('text-white');
    } else {
      card.classList.remove('bg-[#EBB04D]');
      card.classList.add('bg-white/15');

      nameEl && nameEl.classList.remove('text-[#1E4D40]');
      nameEl && nameEl.classList.add('text-white/85');

      timeEl && timeEl.classList.remove('text-[#1E4D40]');
      timeEl && timeEl.classList.add('text-white');
    }
  }

  function tick() {
    const parts = getNowPartsTZ();
    const now = dateFromParts(parts, `${parts.hour}:${parts.minute}:${parts.second}`);

    if (clockEl) clockEl.textContent = `${parts.hour}:${parts.minute}:${parts.second}`;

    const prayers = cards.map((card) => {
      const name = card.dataset.prayer || '';
      const time = card.dataset.time || '';
      if (!/^\d{2}:\d{2}$/.test(time)) return null;

      const dt = dateFromParts(parts, `${time}:00`);
      return { card, name, time, dt };
    }).filter(Boolean).sort((a, b) => a.dt - b.dt);

    if (!prayers.length) return;

    let next = prayers.find(p => p.dt > now);
    if (!next) {
      next = { ...prayers[0] };
      next.dt = new Date(next.dt.getTime() + 24 * 60 * 60 * 1000);
    }

    if (nextBadgeEl) nextBadgeEl.textContent = `Next: ${next.name}`;

    prayers.forEach(p => setCardActive(p.card, false));

    const nextToday = prayers.find(p => p.name === next.name && p.time === next.time);
    if (nextToday) setCardActive(nextToday.card, true);

    if (countdownEl) {
      let diff = Math.max(0, Math.floor((next.dt - now) / 1000));
      const h = Math.floor(diff / 3600); diff %= 3600;
      const m = Math.floor(diff / 60);
      const s = diff % 60;
      countdownEl.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
    }
  }

  tick();
  setInterval(tick, 1000);
})();
</script>

</x-front-layout>
