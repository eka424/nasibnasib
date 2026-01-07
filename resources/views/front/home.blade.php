<x-front-layout>
@php
    // ====== KEEP: existing data sources (routes/ids tetap sama) ======
    $todayLabel = \Carbon\Carbon::now('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y');

    // ====== ICONS: Outline / Sketch-like (inline SVG, no CDN) ======
    $svg = [
        'mosque' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16"/><path d="M6 20V10"/><path d="M18 20V10"/><path d="M8 10V6l4-3 4 3v4"/><path d="M10 20v-6h4v6"/></svg>',
        'home' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 10v10h14V10"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
        'book' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19a2 2 0 0 0 2 2h14"/><path d="M6 3h14v18H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>',
        'heart' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
        'menu' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
        'spark' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l1.2 4.2L17.4 7.4l-4.2 1.2L12 12l-1.2-3.4L6.6 7.4l4.2-1.2L12 2z"/><path d="M19 13l.6 2.1L22 16l-2.4.9L19 19l-.6-2.1L16 16l2.4-.9L19 13z"/></svg>',
        'hand' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 12V8a1 1 0 0 1 2 0v4"/><path d="M9 12V6a1 1 0 0 1 2 0v6"/><path d="M11 12V7a1 1 0 0 1 2 0v5"/><path d="M13 12V9a1 1 0 0 1 2 0v7a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4v-2a2 2 0 0 1 2-2h0"/></svg>',
        'bolt' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>',
        'info' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-5"/><path d="M12 8h.01"/></svg>',
        'report' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5a2 2 0 0 1 2-2h10l4 4v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M14 3v4h4"/><path d="M8 13h8"/><path d="M8 17h6"/><path d="M8 9h4"/></svg>',
        'wallet' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/><path d="M17 11h4"/><path d="M3 9h18"/></svg>',
        'card' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/></svg>',
        'time' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
        'pin' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.4 7-10a7 7 0 0 0-14 0c0 5.6 7 10 7 10z"/><circle cx="12" cy="11" r="2"/></svg>',
        'library' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16"/><path d="M6 20V9"/><path d="M10 20V9"/><path d="M14 20V9"/><path d="M18 20V9"/><path d="M3 9l9-5 9 5"/></svg>',
        'play' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 5v14l11-7z"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'leaf' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 3s-7 1-11 5-5 11-5 11 7-1 11-5 5-11 5-11z"/><path d="M10 14c2-2 6-4 6-4"/></svg>',
        'quote' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 11H6V7h4v4zm8 0h-4V7h4v4z"/><path d="M6 13h4v4H6z"/><path d="M14 13h4v4h-4z"/></svg>',
        'diamond' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 8-7 12L5 10l7-8z"/><path d="M5 10h14"/></svg>',
        'x' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="M6 6l12 12"/></svg>',
        'arrow_right' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h12"/><path d="m13 6 6 6-6 6"/></svg>',
    ];

    // ====== Nav data (routes tetap sama) ======
    $bottomNav = [
        ['id' => 'beranda', 'label' => 'Home', 'href' => route('home'), 'icon' => 'home', 'type' => 'link'],
        ['id' => 'kegiatan', 'label' => 'Kegiatan', 'href' => route('kegiatan.index'), 'icon' => 'calendar', 'type' => 'link'],
        ['id' => 'artikel', 'label' => 'Artikel', 'href' => route('artikel.index'), 'icon' => 'book', 'type' => 'link'],
    ];

    $quickAmounts = [
        ['label' => 'Rp 10.000', 'icon' => 'spark'],
        ['label' => 'Rp 25.000', 'icon' => 'hand'],
        ['label' => 'Rp 50.000', 'icon' => 'heart'],
    ];

    $trustBadges = [
        ['label' => 'Transparan', 'icon' => 'search'],
        ['label' => 'Terverifikasi', 'icon' => 'check'],
        ['label' => 'Cepat', 'icon' => 'bolt'],
    ];

    $fasilitas = [
        ['icon' => 'library', 'text' => 'Perpustakaan digital bacaan Islami anak.'],
        ['icon' => 'play', 'text' => 'Area bermain aman & mudah diawasi.'],
        ['icon' => 'users', 'text' => 'Pendamping relawan untuk ketertiban anak.'],
    ];

    $kegiatanAnak = [
        ['icon' => 'book', 'text' => 'Dongeng Islami & tahfidz akhir pekan.'],
        ['icon' => 'users', 'text' => 'Kelas tahsin keluarga.'],
        ['icon' => 'leaf', 'text' => 'Workshop adab & akhlak anak.'],
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

{{-- IMPORTANT: Background must match front-layout (solid #13392f) --}}
<div class="min-h-screen w-full overflow-x-hidden bg-[#13392f] text-white relative">

  {{-- PATTERN BACKGROUND (30%) --}}
  <div
    aria-hidden="true"
    class="fixed inset-0 pointer-events-none z-0 opacity-5"
    style="
      background-image: url('{{ asset('images/pattern.png') }}');
      background-repeat: repeat;
      background-size: 280px;
    "
  ></div>

  {{-- SEMUA KONTEN DI BAWAH INI --}}
  <div class="relative z-10">



  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Amiri:wght@400;700&display=swap');

    :root{
      --bg:#13392f;
      --gold:#E7B14B;
      --card: rgba(255,255,255,0.07);
      --card2: rgba(255,255,255,0.10);
      --border: rgba(255,255,255,0.14);
    }

    body { font-family: Inter, ui-sans-serif, system-ui, -apple-system; }
    h1,h2,h3,.heading { font-family: Poppins, ui-sans-serif, system-ui, -apple-system; }
    .arabic{font-family:'Amiri','Scheherazade New',serif;}
    .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
    .scrollbar-none::-webkit-scrollbar{display:none;}
    section[id]{ scroll-margin-top: 96px; }

    .shell{max-width:1200px;}

    /* Glass card that matches solid bg */
    .glass{
      background: var(--card);
      border: 1px solid var(--border);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      box-shadow: 0 18px 60px -42px rgba(0,0,0,.65);
    }
    .glass-strong{
      background: var(--card2);
      border: 1px solid var(--border);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }

    .btn{ transition: transform .15s ease, filter .15s ease, box-shadow .15s ease; }
    .btn:active{ transform: scale(.98); }
    .btn:hover{ transform: translateY(-1px); }

    .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
    .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}

    svg{ stroke: currentColor; }
    svg *{ vector-effect: non-scaling-stroke; }

    .prayer-card{ transition: transform .15s ease, background .15s ease, border-color .15s ease; }
    .prayer-card:hover{ transform: translateY(-1px); }
    .prayer-card.is-active{
      background: rgba(231,177,75,0.96) !important;
      border-color: rgba(255,255,255,0.35) !important;
    }
    .prayer-card.is-active .prayer-name,
    .prayer-card.is-active .prayer-time{
      color: #13392f !important;
    }

    .bottom-item[data-active="true"]{
      background: rgba(231,177,75,0.96);
      color: #13392f;
      box-shadow: 0 18px 60px -40px rgba(231,177,75,0.70);
    }
    .bottom-item[data-active="false"]{
      background: transparent;
      color: rgba(255,255,255,0.88);
    }
  </style>

  {{-- NO BACKDROP GRADIENT: keep clean solid background --}}
  {{-- (intentionally removed radial/linear gradients) --}}

  {{-- HERO --}}
  <section id="beranda" class="relative min-h-[100svh] pt-24">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16">
      <div class="grid gap-6 2xl:gap-10 lg:grid-cols-12 lg:items-stretch opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>

        <div class="lg:col-span-7">
          <div class="glass rounded-[28px] p-6 sm:p-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white ring-1 ring-white/15">
              <span class="inline-flex text-white/90">{!! $svg['spark'] !!}</span>
              Pantau kegiatan • Artikel • Sedekah
            </div>

            <h1 class="heading mt-4 text-4xl font-extrabold leading-tight text-white sm:text-5xl">
              Masjid Agung Al-A&#39;la
            </h1>

            <p class="mt-3 text-sm text-white/75 sm:text-base text-justify leading-relaxed">
  Masjid Agung Al-A'la merupakan pusat peribadatan umat Muslim sekaligus simbol toleransi beragama yang terletak di jantung Kota Gianyar, Bali. Sebagai masjid terbesar di kabupaten tersebut, bangunannya berdiri megah dengan arsitektur modern yang dipadukan dengan menara tinggi yang menjulang, menjadikannya salah satu landmark religi yang ikonik di kawasan tersebut.

  <br><br>

  Secara fungsional, masjid ini tidak hanya menjadi tempat ibadah salat fardu dan salat Jumat, tetapi juga berperan sebagai pusat aktivitas sosial dan pendidikan Islam bagi masyarakat sekitar. Letaknya yang strategis di pusat kota mencerminkan keharmonisan hidup berdampingan antara komunitas Muslim dengan masyarakat Gianyar yang mayoritas beragama Hindu. Keberadaan Masjid Agung Al-A'la menjadi bukti nyata dari semangat moderasi beragama dan kerukunan yang terjaga erat di Pulau Dewata.
</p>

            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
              <a href="{{ route('kegiatan.index') }}"
                class="btn inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#13392f] shadow">
                Jelajahi Kegiatan
                <span class="inline-flex">{!! $svg['arrow_right'] !!}</span>
              </a>
              <a href="{{ route('artikel.index') }}"
                class="btn inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/5 px-6 py-3 text-sm font-semibold text-white">
                <span class="inline-flex">{!! $svg['book'] !!}</span>
                Baca Artikel
              </a>
              </a>
            </div>
{{-- CTA Transparansi Keuangan --}}
<div class="mt-3">
  <a href="{{ route('public.finance') }}"
     class="btn flex items-center justify-between gap-3 rounded-[22px] border border-white/15 bg-white/5 px-5 py-4 text-white shadow-sm hover:bg-white/10">
    <div class="flex items-center gap-3">
      <span class="grid h-11 w-11 place-items-center rounded-2xl bg-[var(--gold)] text-[#13392f] shadow">
        {!! $svg['wallet'] !!}
      </span>
      <div class="min-w-0">
        <div class="heading text-sm sm:text-base font-extrabold text-white">
          Laporan Keuangan Masjid Agung Al-A&#39;la
        </div>
        <div class="text-xs text-white/70">
          Lihat pemasukan, pengeluaran, saldo, & bukti transaksi publik.
        </div>
      </div>
    </div>
    <span class="inline-flex text-white/85">
      {!! $svg['arrow_right'] !!}
    </span>
  </a>
</div>

            <div class="mt-7 grid gap-3 sm:grid-cols-2">
              <div class="rounded-2xl border border-white/15 bg-white/5 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Hari ini</p>
                <p class="mt-1 text-sm font-semibold text-white">{{ $todayLabel }}</p>
                <p class="mt-1 text-xs text-white/65">Gianyar, Bali • WITA</p>
              </div>

              <div class="rounded-2xl border border-white/15 bg-white/5 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Kepercayaan</p>
                <div class="mt-2 flex flex-wrap gap-2">
                  @foreach($trustBadges as $b)
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                      <span class="inline-flex text-white/90">{!! $svg[$b['icon']] !!}</span>
                      {{ $b['label'] }}
                    </span>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
              <a href="#kegiatan" class="btn inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs font-semibold text-white/90">
                <span class="inline-flex">{!! $svg['calendar'] !!}</span>
                Lihat highlight kegiatan
              </a>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="glass rounded-[28px] overflow-hidden">
            <div class="relative aspect-[16/10] w-full bg-white/5">
              <img
                src="{{ asset('images/fotomasjid.jpg') }}"
                alt="Gambar Masjid"
                loading="lazy"
                referrerpolicy="no-referrer"
                class="h-full w-full object-cover"
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
              <div class="absolute left-4 top-4">
                <span class="inline-flex items-center gap-2 rounded-full bg-black/35 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/15 backdrop-blur">
                  <span class="inline-flex">{!! $svg['mosque'] !!}</span>
                  Masjid Al Ala Gianyar
                </span>
              </div>
            </div>

            <div class="p-5 sm:p-6">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Jadwal Sholat</p>
                  <p class="mt-1 text-sm font-semibold text-white/95">{{ $todayLabel }}</p>
                  <p class="mt-0.5 text-xs text-white/65">Gianyar, Bali</p>

                  <p class="mt-2 text-xs text-white/70 leading-tight">
                    Waktu sekarang: <span id="realtimeClock" class="font-semibold text-white">--:--:--</span> WITA
                  </p>
                  <p class="mt-0.5 text-xs text-white/70 leading-tight">
                    Hitung mundur: <span id="nextCountdown" class="font-semibold text-white">--:--:--</span>
                  </p>
                </div>

                <span id="nextPrayerBadge" class="inline-flex items-center rounded-full bg-[var(--gold)] px-3 py-1 text-xs font-semibold text-[#13392f]">
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
                    class="prayer-card rounded-2xl border border-white/15 {{ $isNext ? 'is-active' : 'bg-white/5' }} px-4 py-3"
                    data-prayer="{{ $nm }}"
                    data-time="{{ $tm }}"
                  >
                    <p class="prayer-name text-xs font-semibold {{ $isNext ? 'text-[#13392f]' : 'text-white/70' }}">
                      {{ $nm }}
                    </p>
                    <p class="prayer-time heading mt-0.5 text-lg font-extrabold {{ $isNext ? 'text-[#13392f]' : 'text-white' }}">
                      {{ $tm }}
                    </p>
                  </div>
                @endforeach
              </div>

              <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('kegiatan.index') }}"
                  class="btn inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-[#13392f] shadow">
                  <span class="inline-flex">{!! $svg['calendar'] !!}</span>
                  Lihat Agenda
                </a>
                </a>
              </div>

              <p class="mt-3 text-[11px] text-white/60">
              
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- STATS --}}
  <section id="stats" class="relative py-10 sm:py-14">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16" opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="glass rounded-[28px] p-6 sm:p-8">
        <div class="mb-4 flex items-end justify-between gap-4">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Ringkasan</p>
            <h2 class="heading mt-2 text-2xl font-extrabold text-white">Statistik Masjid</h2>
            <p class="mt-1 text-sm text-white/70">Singkat, rapi, dan nyaman.</p>
          </div>
        </div>

        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none sm:grid sm:grid-cols-2 sm:gap-4 sm:overflow-visible lg:grid-cols-4">
          @forelse($stats as $s)
            <div class="w-[72%] flex-none rounded-2xl border border-white/15 bg-white/5 px-5 py-5 text-white shadow-sm transition hover:-translate-y-1 sm:w-auto sm:flex-auto">
              <div class="flex items-center gap-4">
                <div class="grid h-12 w-12 place-content-center rounded-2xl bg-white/10 text-2xl ring-1 ring-white/10">
                  {{ $s['icon'] }}
                </div>
                <div>
                  <p class="heading text-3xl font-extrabold text-white">{{ $s['value'] }}</p>
                  <p class="text-sm text-white/70">{{ $s['label'] }}</p>
                </div>
              </div>
            </div>
          @empty
            <p class="text-sm text-white/70">Statistik belum tersedia.</p>
          @endforelse
        </div>

        <p class="mt-2 text-xs text-white/60 sm:hidden">Geser untuk melihat statistik lain →</p>
      </div>
    </div>
  </section>

  {{-- KEGIATAN --}}
  <section id="kegiatan" class="py-12 sm:py-16">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16" opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Highlight</p>
          <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Kegiatan Terdekat</h2>
          <p class="mt-1 text-sm text-white/70">Swipe kartu untuk cepat.</p>
        </div>
        <a href="{{ route('kegiatan.index') }}" class="text-sm font-semibold text-white/90 underline decoration-white/30 underline-offset-4 hover:decoration-white">
          Semua Kegiatan →
        </a>
      </div>

      <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-none md:grid md:grid-cols-2 md:overflow-visible lg:grid-cols-3">
        @forelse($kegiatans as $kegiatan)
          <article class="w-[86%] max-w-sm flex-none overflow-hidden rounded-[28px] border border-white/15 bg-white/5 text-white shadow-md transition hover:-translate-y-1 md:w-auto md:max-w-none md:flex-auto">
            <div class="relative aspect-[16/10] w-full bg-white/5">
              <img loading="lazy"
                src="{{ $kegiatan->poster ?: 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1400&q=80' }}"
                alt="{{ $kegiatan->nama_kegiatan }}"
                class="h-full w-full object-cover"
                referrerpolicy="no-referrer" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
            </div>

            <div class="space-y-3 p-5">
              <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="rounded-full bg-white/10 px-3 py-1 font-semibold text-white ring-1 ring-white/10">
                  {{ $kegiatan->kategori ?? 'Program Masjid' }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 font-semibold text-white/70 ring-1 ring-white/10">
                  <span class="inline-flex">{!! $svg['calendar'] !!}</span>
                  {{ optional($kegiatan->tanggal_mulai)->translatedFormat('d M Y') ?? 'Jadwal menyusul' }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 font-semibold text-white/70 ring-1 ring-white/10">
                  <span class="inline-flex">{!! $svg['pin'] !!}</span>
                  {{ $kegiatan->lokasi ?? 'Lokasi menyusul' }}
                </span>
              </div>

              <h3 class="heading text-lg font-semibold text-white line-clamp-2">{{ $kegiatan->nama_kegiatan }}</h3>
              <p class="text-sm leading-relaxed text-white/70 line-clamp-3">
                {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 140) }}
              </p>

              <div class="grid grid-cols-2 gap-2 pt-1">
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                  class="btn inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-[#13392f] shadow">
                  Daftar
                </a>
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                  class="btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white">
                  <span class="inline-flex">{!! $svg['info'] !!}</span>
                  Detail
                </a>
              </div>
            </div>
          </article>
        @empty
          <p class="text-sm text-white/70">Belum ada kegiatan tersedia.</p>
        @endforelse
      </div>

      <p class="mt-2 text-xs text-white/60 md:hidden">Swipe kiri/kanan untuk lihat kegiatan lain →</p>
    </div>
  </section>

 {{-- ARTIKEL --}}
<section id="artikel" class="py-12 sm:py-16">
  <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16" opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
    <div class="glass rounded-[32px] p-6 sm:p-8">
      <div class="mb-5 flex items-end justify-between gap-3">
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Artikel</p>
          <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white">Artikel Terbaru</h2>
          <p class="mt-1 text-sm text-white/70">Ringkas, mudah discan.</p>
        </div>
        <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-white/90 underline decoration-white/30 underline-offset-4 hover:decoration-white">Semua</a>
      </div>

      <div class="grid gap-4 lg:grid-cols-5">
        @if($featuredArtikel)
          @php
            $thumb = $featuredArtikel->thumbnail ?? null;
            if ($thumb) {
              if (\Illuminate\Support\Str::startsWith($thumb, ['http://','https://'])) {
                $thumbUrl = $thumb;
              } else {
                $normalized = ltrim($thumb, '/');
                if (\Illuminate\Support\Str::startsWith($normalized, 'storage/')) {
                  $normalized = \Illuminate\Support\Str::after($normalized, 'storage/');
                } elseif (!\Illuminate\Support\Str::contains($normalized, '/')) {
                  $normalized = 'thumbnails/' . $normalized;
                }
                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                if (!$disk->exists($normalized) && !\Illuminate\Support\Str::contains($normalized, '.')) {
                  foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                    $candidate = $normalized . '.' . $ext;
                    if ($disk->exists($candidate)) {
                      $normalized = $candidate;
                      break;
                    }
                  }
                  if (!$disk->exists($normalized)) {
                    $base = basename($normalized);
                    $match = collect($disk->files('thumbnails'))
                      ->first(fn ($f) => \Illuminate\Support\Str::startsWith(basename($f), $base . '.'));
                    if ($match) {
                      $normalized = $match;
                    }
                  }
                }
                $thumbUrl = $disk->url($normalized);
              }
            } else {
              $thumbUrl = $masjidImage;
            }
          @endphp

          <a href="{{ route('artikel.show', $featuredArtikel) }}" class="group relative overflow-hidden rounded-[28px] border border-white/15 bg-white/5 text-white shadow-md lg:col-span-3">
            <div class="relative h-56 w-full sm:h-60">
              <img
                src="{{ $thumbUrl }}"
                alt="{{ $featuredArtikel->title }}"
                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.02]"
                referrerpolicy="no-referrer"
                onerror="this.onerror=null;this.src='{{ $masjidImage }}';"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
              <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-[var(--gold)] px-3 py-1 text-xs font-semibold text-[#13392f]">
                Featured
              </div>
            </div>

            <div class="space-y-2 p-5">
              <p class="text-xs text-white/65">
                {{ $artikelDate($featuredArtikel) }} • {{ $readingTime($featuredArtikel) }} menit baca
              </p>
              <h3 class="heading text-xl font-extrabold line-clamp-2">{{ $featuredArtikel->title }}</h3>
              <p class="text-sm text-white/70 line-clamp-3">
                {{ \Illuminate\Support\Str::limit(strip_tags($featuredArtikel->content ?? ''), 160) }}
              </p>
              <div class="pt-1">
                <span class="inline-flex items-center gap-2 text-sm font-semibold text-white">
                  Baca selengkapnya <span aria-hidden>→</span>
                </span>
              </div>
            </div>
          </a>
        @endif

        <div class="grid gap-3 lg:col-span-2">
          @forelse($otherArtikels->take(4) as $a)
            @php
              $thumb = $a->thumbnail ?? null;
              if ($thumb) {
                if (\Illuminate\Support\Str::startsWith($thumb, ['http://','https://'])) {
                  $thumbUrl = $thumb;
                } else {
                  $normalized = ltrim($thumb, '/');
                  if (\Illuminate\Support\Str::startsWith($normalized, 'storage/')) {
                    $normalized = \Illuminate\Support\Str::after($normalized, 'storage/');
                  } elseif (!\Illuminate\Support\Str::contains($normalized, '/')) {
                    $normalized = 'thumbnails/' . $normalized;
                  }
                  $disk = \Illuminate\Support\Facades\Storage::disk('public');
                  if (!$disk->exists($normalized) && !\Illuminate\Support\Str::contains($normalized, '.')) {
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                      $candidate = $normalized . '.' . $ext;
                      if ($disk->exists($candidate)) {
                        $normalized = $candidate;
                        break;
                      }
                    }
                    if (!$disk->exists($normalized)) {
                      $base = basename($normalized);
                      $match = collect($disk->files('thumbnails'))
                        ->first(fn ($f) => \Illuminate\Support\Str::startsWith(basename($f), $base . '.'));
                      if ($match) {
                        $normalized = $match;
                      }
                    }
                  }
                  $thumbUrl = $disk->url($normalized);
                }
              } else {
                $thumbUrl = $masjidImage;
              }
            @endphp

            <a href="{{ route('artikel.show', $a) }}" class="group flex gap-3 rounded-[24px] border border-white/15 bg-white/5 p-4 text-white shadow-sm transition hover:-translate-y-0.5">
              <div class="h-16 w-20 flex-none overflow-hidden rounded-2xl bg-white/10 ring-1 ring-white/10">
                <img
                  src="{{ $thumbUrl }}"
                  alt="{{ $a->title }}"
                  class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.02]"
                  referrerpolicy="no-referrer"
                  onerror="this.onerror=null;this.src='{{ $masjidImage }}';"
                />
              </div>
              <div class="min-w-0">
                <p class="text-[11px] text-white/60">{{ $artikelDate($a) }} • {{ $readingTime($a) }} mnt</p>
                <p class="mt-1 text-sm font-semibold line-clamp-2">{{ $a->title }}</p>
              </div>
            </a>
          @empty
            <p class="text-sm text-white/70">Belum ada artikel.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>




  {{-- PENGINGAT --}}
  <section class="relative py-12 sm:py-14">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16" opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
      <div class="glass rounded-[32px] p-6 sm:p-8">
        <div class="mb-4 flex items-end justify-between gap-3">
          <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Pengingat</p>
            <h2 class="heading mt-2 text-2xl font-extrabold text-white">Pengingat Harian</h2>
            <p class="mt-1 text-sm text-white/70">Swipe untuk ganti kartu.</p>
          </div>
          <div class="hidden items-center gap-2 sm:flex">
            <button id="reminderPrev" type="button" aria-label="Sebelumnya"
              class="btn rounded-2xl border border-white/15 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:bg-white/10">←</button>
            <button id="reminderNext" type="button" aria-label="Berikutnya"
              class="btn rounded-2xl border border-white/15 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:bg-white/10">→</button>
          </div>
        </div>

        <div id="reminderScroller"
          class="scrollbar-none flex snap-x snap-mandatory gap-3 overflow-x-auto px-1 py-2 sm:px-2"
          style="scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch;">

          @foreach($reminders as $idx => $it)
            <article data-card class="snap-center">
              <div class="relative w-[84vw] max-w-[560px] overflow-hidden rounded-[32px] border border-white/15 bg-white/5 shadow-[0_18px_56px_-32px_rgba(0,0,0,0.55)] sm:w-[70vw]">
                <div class="relative px-5 py-6 text-white sm:px-7 sm:py-7">
                  <div class="mx-auto mb-3 grid h-10 w-10 place-items-center rounded-2xl bg-white/10 ring-1 ring-white/10 text-white/90">
                    {!! $svg['quote'] !!}
                  </div>

                  <p class="text-center text-xs font-semibold uppercase tracking-[0.45em] text-white/65">PENGINGAT HARIAN</p>

                  <p class="arabic mt-3 text-center text-xl leading-[2.15] text-white sm:text-2xl" dir="rtl">
                    {{ $it['arabic'] }}
                  </p>

                  @if(!empty($it['latin']))
                    <p class="mt-2.5 text-center text-xs text-white/70 sm:text-sm">{{ $it['latin'] }}</p>
                  @endif

                  <p class="mt-3.5 text-center text-xs font-medium text-white/80 sm:text-sm">{{ $it['meaning'] }}</p>

                  <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[var(--gold)] px-3.5 py-1.5 text-xs font-semibold text-[#13392f]">
                      <span class="h-1.5 w-1.5 rounded-full bg-[#13392f]"></span>
                      {{ $it['ref'] }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white ring-1 ring-white/10">
                      {{ $it['kind'] }}
                    </span>
                  </div>

                  @if(!empty($it['tags']))
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                      @foreach($it['tags'] as $t)
                        <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] font-semibold text-white/85">
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

        <p class="mt-4 rounded-2xl border border-white/15 bg-white/5 p-4 text-xs text-white/70">
          Tips: navigasi cepat lewat <span class="font-semibold text-white">Bottom Bar</span>.
        </p>
      </div>
    </div>
  </section>

  {{-- BOTTOM NAV --}}
  <nav class="fixed inset-x-0 bottom-0 z-50 pb-[env(safe-area-inset-bottom)]">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-10 2xl:px-16">
      <div class="rounded-[26px] border border-white/12 bg-black/45 p-2 shadow-[0_18px_60px_-35px_rgba(0,0,0,0.75)] backdrop-blur">
        <div class="grid grid-cols-4 gap-1">
          @foreach($bottomNav as $n)
            <a href="{{ $n['href'] }}"
              data-bottom-item
              data-bottom-id="{{ $n['id'] }}"
              class="bottom-item btn flex flex-col items-center justify-center gap-1 rounded-2xl px-2 py-2.5 text-center text-xs font-semibold"
              aria-label="{{ $n['label'] }}">
              <span class="text-base inline-flex" aria-hidden>{!! $svg[$n['icon']] !!}</span>
              <span class="text-[11px]">{{ $n['label'] }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </nav>

  {{-- MOBILE DRAWER --}}
  <div id="mobileDrawerRoot" class="pointer-events-none fixed inset-0 z-[60]">
    <div id="mobileDrawerOverlay" class="absolute inset-0 bg-black/60 opacity-0 transition-opacity"></div>

    <aside id="mobileDrawer" class="absolute right-0 top-0 h-full w-[86vw] max-w-sm translate-x-full bg-[#13392f] text-white shadow-2xl transition-transform">
      <div class="flex h-full flex-col p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-white/60">Menu</p>
            <p class="heading text-lg font-extrabold">Masjid Agung Al-A&#39;la</p>
          </div>
          <button data-close-drawer class="grid h-11 w-11 place-items-center rounded-2xl border border-white/15 bg-white/5 text-white" aria-label="Tutup menu" type="button">
            {!! $svg['x'] !!}
          </button>
        </div>

        <div class="mt-5 space-y-2">
          <a href="#beranda" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Beranda</span><span class="text-white/50">→</span>
          </a>
          <a href="#kegiatan" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Kegiatan</span><span class="text-white/50">→</span>
          </a>
          <a href="#artikel" data-close-drawer class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
            <span>Artikel</span><span class="text-white/50">→</span>
          </a>
          <a href="{{ route('public.finance') }}" data-close-drawer
   class="flex items-center justify-between rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
  <span>Transparansi Keuangan</span><span class="text-white/50">→</span>
</a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2">
          <button class="btn rounded-2xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10" type="button">
            Masuk
          </button>
          <button class="btn rounded-2xl bg-[var(--gold)] px-4 py-3 text-sm font-semibold text-[#13392f] shadow" type="button">
            Daftar
          </button>
        </div>

        <div class="mt-4 rounded-2xl border border-white/15 bg-white/5 p-4 text-xs text-white/70">
          Tips: navigasi cepat lewat <span class="font-semibold text-white">Bottom Bar</span>.
        </div>

        <div class="mt-auto pt-4 text-[11px] text-white/55">
          © {{ date('Y') }} Masjid Agung Al-A&#39;la
        </div>
      </div>
    </aside>
  </div>

  {{-- SCRIPTS (drawer, animate, bottom nav active, reminder carousel) --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
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

      // Smooth scroll for hash links
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

      // Animate on view
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

      // Bottom nav active section
      const sectionIds = ['beranda','kegiatan','artikel'];
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

      // Reminder dots
      const scroller = document.getElementById('reminderScroller');
      const prevBtn = document.getElementById('reminderPrev');
      const nextBtn = document.getElementById('reminderNext');
      const dotsWrap = document.getElementById('reminderDots');

      if (scroller && dotsWrap) {
        const cards = Array.from(scroller.querySelectorAll('[data-card]'));
        const dots = cards.map((_, i) => {
          const b = document.createElement('button');
          b.type = 'button';
          b.className = 'h-2.5 w-2.5 rounded-full bg-white/30 transition';
          b.setAttribute('aria-label', `Buka kartu ${i+1}`);
          b.dataset.dotIndex = String(i);
          dotsWrap.appendChild(b);
          return b;
        });

        function getActiveIndex() {
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
            d.classList.toggle('bg-white/30', i !== activeIdx);
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

  {{-- Jadwal Sholat realtime + countdown (WITA) --}}
  <script>
    (() => {
      const TZ = 'Asia/Makassar';
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
        for (const p of parts) if (p.type !== 'literal') map[p.type] = p.value;
        return map;
      }

      function dateFromParts(parts, hhmmss) {
        return new Date(`${parts.year}-${parts.month}-${parts.day}T${hhmmss}`);
      }

      const clockEl = document.getElementById('realtimeClock');
      const nextBadgeEl = document.getElementById('nextPrayerBadge');
      const countdownEl = document.getElementById('nextCountdown');
      const cards = Array.from(document.querySelectorAll('.prayer-card'));

      function setCardActive(card, isActive) {
        card.classList.toggle('is-active', !!isActive);
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

</div> {{-- end z-10 wrapper --}}
</div> {{-- end main wrapper --}}
</x-front-layout>

