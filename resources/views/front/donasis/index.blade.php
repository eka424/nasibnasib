<x-front-layout>
  @php
    // ====== Data utama dari controller ======
    $bg = '#13392f';         // theme utama front
    $accent = '#E7B14B';     // gold accent
    $primary = '#0F4A3A';

    $bgImage = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80';
    $posterFallback = 'https://images.unsplash.com/photo-1541417904950-b855846fe074?auto=format&fit=crop&w=1800&q=80';

    $totalRaised = $donasis->sum('dana_terkumpul');
    $totalTarget = $donasis->sum('target_dana');
    $activeCampaigns = $donasis->count();
    $totalDonors = $donasis->sum(fn ($d) => (int) ($d->transaksi_donasis_count ?? 0));

    $overallProgress = $totalTarget > 0 ? min(100, max(0, ($totalRaised / $totalTarget) * 100)) : 0;

    $recentList = collect($recentDonations ?? [])->map(function ($item) {
      return [
        'name' => $item->anonymous ? 'Anonymous' : ($item->user->name ?? 'Anonim'),
        'amount' => (int) ($item->jumlah ?? 0),
        'time' => optional($item->created_at)->diffForHumans() ?? 'Baru saja',
        'anonymous' => (bool) ($item->anonymous ?? false),
      ];
    });

    if ($recentList->isEmpty()) {
      $recentList = collect([
        ['name' => 'Ahmad Rizki', 'amount' => 500000, 'time' => '2 jam lalu', 'anonymous' => false],
        ['name' => 'Anonymous', 'amount' => 750000, 'time' => '4 jam lalu', 'anonymous' => true],
        ['name' => 'Siti Nurhaliza', 'amount' => 250000, 'time' => '6 jam lalu', 'anonymous' => false],
        ['name' => 'Anonymous', 'amount' => 1000000, 'time' => '1 hari lalu', 'anonymous' => true],
      ]);
    }

    $presetAmounts = [100000, 250000, 500000, 1000000, 2500000, 5000000];
    $selectedAmount = max(0, (int) old('jumlah', $presetAmounts[1] ?? 250000));
    $formattedSelectedAmount = number_format($selectedAmount, 0, ',', '.');

    $hasTransaksiRoute = \Illuminate\Support\Facades\Route::has('donasi.transaksi');
    $donasiActionTemplate = $hasTransaksiRoute
      ? route('donasi.transaksi', ['donasi' => '__ID__'])
      : url()->current();
    $defaultDonasiId = optional($donasis->first())->id;

    $newsletterHasRoute = \Illuminate\Support\Facades\Route::has('newsletter.store');
    $newsletterAction = $newsletterHasRoute ? route('newsletter.store') : url()->current();
    $newsletterMethod = $newsletterHasRoute ? 'POST' : 'GET';

    $daysLeft = function ($endDate) {
      if (! $endDate) return null;
      $diff = now()->diffInDays(\Carbon\Carbon::parse($endDate)->endOfDay(), false);
      return max(0, $diff);
    };

    // ====== Icons (flaticon-like outline) ======
    $ico = [
      'heart' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
      'gift' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>',
      'chart' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-3 3 2 5-6"/></svg>',
      'users' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="3"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a3 3 0 0 1 0 5.74"/></svg>',
      'card' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>',
      'target' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></svg>',
      'mail' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>',
      'arrowDown' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>',
      'spark' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.2 6.2L20 10l-5.8 1.8L12 18l-2.2-6.2L4 10l5.8-1.8L12 2z"/></svg>',
    ];
  @endphp

  <style>
    :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
    svg{ stroke: currentColor; }
    svg *{ vector-effect: non-scaling-stroke; }

    .glass{
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.08);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 18px 60px -45px rgba(0,0,0,.55);
    }

    /* ====== FIX: field putih + teks hitam (biar jelas pas ngetik/pilih) ====== */
    .field-light{
      color: #0f172a !important;           /* slate-900 */
      background: rgba(255,255,255,.95) !important;
      border-color: rgba(255,255,255,.18) !important;
    }
    .field-light::placeholder{
      color: rgba(15,23,42,.55) !important;
    }
    .field-light:focus{
      outline: none;
      box-shadow: 0 0 0 2px rgba(231,177,75,.55);
      border-color: rgba(231,177,75,.45) !important;
    }
    select.field-light option{ color:#0f172a; }
  </style>

  <div class="min-h-screen text-white" style="background: var(--bg);">

    {{-- HERO --}}
    <header class="relative overflow-hidden border-b border-white/10">
      <div class="absolute inset-0 -z-10">
        <img src="{{ $bgImage }}" alt="Donasi Masjid"
             class="h-full w-full object-cover opacity-35" referrerpolicy="no-referrer">
        <div class="absolute inset-0 bg-gradient-to-b from-black/65 via-black/35 to-black/75"></div>
        <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl"
             style="background: rgba(231,177,75,0.14);"></div>
        <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
      </div>

      <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
        <div class="mx-auto max-w-3xl text-center">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90 backdrop-blur">
            {!! $ico['spark'] !!} DONASI MASJID
          </span>

          <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
            Donasi Masjid Agung Al-A’la
          </h1>
          <p class="mt-2 text-sm text-white/80 sm:text-base">
            Dukung program masjid secara transparan dan berdampak. Pilih campaign, tentukan nominal, lalu lanjut pembayaran.
          </p>

          {{-- Stats --}}
          <div class="glass mt-6 rounded-[28px] p-4 sm:p-5">
            <div class="grid gap-3 sm:grid-cols-4">
              <div class="rounded-2xl border border-white/10 bg-white/6 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Total Terkumpul</p>
                <p class="mt-1 text-sm font-extrabold text-white">Rp {{ number_format($totalRaised, 0, ',', '.') }}</p>
              </div>
              <div class="rounded-2xl border border-white/10 bg-white/6 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Target Keseluruhan</p>
                <p class="mt-1 text-sm font-extrabold text-white">Rp {{ number_format($totalTarget, 0, ',', '.') }}</p>
              </div>
              <div class="rounded-2xl border border-white/10 bg-white/6 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Donatur</p>
                <p class="mt-1 text-sm font-extrabold text-white">{{ number_format($totalDonors, 0, ',', '.') }}</p>
              </div>
              <div class="rounded-2xl border border-white/10 bg-white/6 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Campaign Aktif</p>
                <p class="mt-1 text-sm font-extrabold text-white">{{ $activeCampaigns }}</p>
              </div>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-white/6 p-4">
              <div class="flex items-center justify-between text-xs font-semibold text-white/70">
                <span>Progress keseluruhan</span>
                <span>{{ number_format($overallProgress, 0) }}%</span>
              </div>
              <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full"
                  style="width: {{ $overallProgress }}%; background: linear-gradient(90deg, var(--accent), #0F4A3A);">
                </div>
              </div>
            </div>

            <div class="mt-4 flex flex-wrap justify-center gap-2">
              <button type="button" data-scroll="#donation-form"
                class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
                style="background: var(--accent);">
                {!! $ico['heart'] !!} Donasi Sekarang
              </button>
              <button type="button" data-scroll="#campaigns"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">
                {!! $ico['arrowDown'] !!} Lihat Campaign
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    {{-- MAIN --}}
    <main id="donation-form" class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[1.35fr_.65fr] lg:px-8">

      {{-- FORM CARD --}}
      <section class="glass overflow-hidden rounded-[28px]">
        <div class="border-b border-white/10 bg-white/6 px-6 py-5">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
              {!! $ico['gift'] !!}
            </span>
            <div>
              <h2 class="text-base font-extrabold text-white">Form Donasi</h2>
              <p class="mt-1 text-sm text-white/70">Isi data dengan benar agar pembayaran lancar.</p>
            </div>
          </div>
        </div>

        <form class="space-y-5 px-6 py-6"
              id="donationForm"
              method="{{ $hasTransaksiRoute ? 'POST' : 'GET' }}"
              action="{{ $hasTransaksiRoute && $defaultDonasiId ? route('donasi.transaksi', ['donasi' => $defaultDonasiId]) : url()->current() }}"
              data-action-template="{{ $donasiActionTemplate }}"
              data-default-donasi="{{ $defaultDonasiId }}"
              data-initial-amount="{{ $selectedAmount }}">
          @if ($hasTransaksiRoute)
            @csrf
          @endif

          <div>
            <label class="text-sm font-semibold text-white/80">Pilih Campaign</label>
            <select name="campaign_id" id="campaign_id"
              class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm">
              <option value="">Donasi Umum</option>
              @foreach ($donasis as $c)
                <option value="{{ $c->id }}">{{ $c->judul }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <div class="flex items-center justify-between">
              <label class="text-sm font-semibold text-white/80">Pilih Jumlah Donasi</label>
              <span class="text-xs font-semibold text-white/70">Dipilih: <span id="amount_preview" class="text-white">Rp {{ $formattedSelectedAmount }}</span></span>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
              @foreach ($presetAmounts as $a)
                <button type="button"
                  class="js-amount rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-left text-sm font-semibold text-white hover:bg-white/10"
                  data-amount="{{ $a }}">
                  Rp {{ number_format($a, 0, ',', '.') }}
                </button>
              @endforeach
            </div>

            <input
              class="field-light mt-3 h-12 w-full rounded-2xl border px-4 text-sm"
              type="number" min="10000" name="amount_custom" id="amount_custom"
              placeholder="Atau nominal custom (contoh: 125000)"
              value="{{ $selectedAmount ?: '' }}">

            <input type="hidden" name="jumlah" id="amount_final" value="{{ $selectedAmount }}">

            @error('jumlah')
              <p class="mt-2 text-xs font-semibold text-red-200">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="text-sm font-semibold text-white/80">Jenis Donasi</label>
            <div class="mt-3 grid gap-2 sm:grid-cols-2">
              <button type="button"
                class="segBtn js-frequency rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-left text-sm font-semibold text-white hover:bg-white/10 active"
                data-value="once">
                Donasi Sekali
                <span class="mt-1 block text-xs font-medium text-white/65">Satu kali pembayaran</span>
              </button>
              <button type="button"
                class="segBtn js-frequency rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-left text-sm font-semibold text-white hover:bg-white/10"
                data-value="monthly">
                Donasi Bulanan
                <span class="mt-1 block text-xs font-medium text-white/65">Komitmen rutin tiap bulan</span>
              </button>
            </div>
            <input type="hidden" name="frequency" id="frequency" value="once">
          </div>

          <div class="grid gap-3 sm:grid-cols-3">
            <div>
              <label class="text-sm font-semibold text-white/80">Nama</label>
              <input class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm"
                     name="name" placeholder="Nama lengkap" value="{{ old('name') }}">
            </div>
            <div>
              <label class="text-sm font-semibold text-white/80">Email</label>
              <input class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm"
                     type="email" name="email" placeholder="email@example.com" value="{{ old('email') }}">
            </div>
            <div>
              <label class="text-sm font-semibold text-white/80">WhatsApp</label>
              <input class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm"
                     name="whatsapp" placeholder="08xxxxxxxxxx" value="{{ old('whatsapp') }}">
            </div>
          </div>

          <div class="grid gap-3 sm:grid-cols-2">
            <div>
              <label class="text-sm font-semibold text-white/80">Metode Pembayaran</label>
              <select class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm"
                      name="pay_method">
                <option value="transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
                <option value="card">Kartu Kredit/Debit</option>
              </select>
            </div>

            <div>
              <label class="text-sm font-semibold text-white/80">Kategori</label>
              <select class="field-light mt-2 h-12 w-full rounded-2xl border px-4 text-sm"
                      name="category">
                <option value="general">Kategori Umum</option>
                <option value="pembangunan">Pembangunan</option>
                <option value="pendidikan">Pendidikan</option>
                <option value="sosial">Sosial</option>
              </select>
            </div>
          </div>

          <div>
            <label class="text-sm font-semibold text-white/80">Pesan / Doa (opsional)</label>
            <textarea rows="3"
              class="field-light mt-2 w-full rounded-2xl border px-4 py-3 text-sm"
              name="message" placeholder="Tuliskan pesan atau doa...">{{ old('message') }}</textarea>
          </div>

          <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
            style="background: var(--accent);">
            {!! $ico['heart'] !!} Donasi Sekarang
          </button>

          <p class="text-xs text-white/60">
            Pembayaran diproses via Xendit. Setelah submit Anda akan diarahkan ke halaman pembayaran.
          </p>
        </form>
      </section>

      {{-- SIDEBAR --}}
      <aside class="space-y-4">
        {{-- Impact --}}
        <section class="glass rounded-[28px] p-5">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
              {!! $ico['chart'] !!}
            </span>
            <div>
              <h3 class="text-sm font-extrabold text-white">Ringkasan Dampak</h3>
              <p class="mt-1 text-xs text-white/70">Progress total seluruh campaign.</p>
            </div>
          </div>

          <div class="mt-4 space-y-2 text-sm text-white/80">
            <div class="flex items-center justify-between"><span>Total Terkumpul</span><span class="font-extrabold text-white">Rp {{ number_format($totalRaised, 0, ',', '.') }}</span></div>
            <div class="flex items-center justify-between"><span>Target</span><span class="font-extrabold text-white">Rp {{ number_format($totalTarget, 0, ',', '.') }}</span></div>
            <div class="flex items-center justify-between"><span>Donatur</span><span class="font-extrabold text-white">{{ number_format($totalDonors) }} orang</span></div>
            <div class="flex items-center justify-between"><span>Campaign</span><span class="font-extrabold text-white">{{ $activeCampaigns }}</span></div>

            <div class="mt-3 rounded-2xl border border-white/10 bg-white/6 p-4">
              <div class="flex items-center justify-between text-xs font-semibold text-white/70">
                <span>Progress</span>
                <span>{{ number_format($overallProgress, 0) }}%</span>
              </div>
              <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/10">
                <div class="h-full rounded-full"
                  style="width: {{ $overallProgress }}%; background: linear-gradient(90deg, var(--accent), #0F4A3A);">
                </div>
              </div>
            </div>
          </div>
        </section>

        {{-- Recent --}}
        <section class="glass rounded-[28px] p-5">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
              {!! $ico['users'] !!}
            </span>
            <div>
              <h3 class="text-sm font-extrabold text-white">Donasi Terbaru</h3>
              <p class="mt-1 text-xs text-white/70">Update terbaru dari jamaah.</p>
            </div>
          </div>

          <div class="mt-4 divide-y divide-white/10">
            @foreach ($recentList as $d)
              @php $initial = $d['anonymous'] ? 'A' : strtoupper(mb_substr($d['name'], 0, 1)); @endphp
              <div class="flex items-center justify-between gap-3 py-3">
                <div class="flex items-center gap-3">
                  <div class="flex h-10 w-10 items-center justify-center rounded-full border text-sm font-extrabold
                    {{ $d['anonymous'] ? 'border-[rgba(231,177,75,0.35)] bg-[rgba(231,177,75,0.14)] text-white' : 'border-[rgba(16,185,129,0.35)] bg-[rgba(16,185,129,0.14)] text-white' }}">
                    {{ $initial }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-bold text-white/95 truncate">{{ $d['name'] }}</p>
                    <p class="text-xs text-white/60">{{ $d['time'] }}</p>
                  </div>
                </div>

                <p class="text-sm font-extrabold text-white">
                  Rp {{ number_format($d['amount'], 0, ',', '.') }}
                </p>
              </div>
            @endforeach
          </div>
        </section>

        {{-- Payment Info --}}
        <section class="glass rounded-[28px] p-5">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
              {!! $ico['card'] !!}
            </span>
            <div>
              <h3 class="text-sm font-extrabold text-white">Info Pembayaran</h3>
              <p class="mt-1 text-xs text-white/70">Contoh kanal pembayaran (bisa kamu ganti dinamis).</p>
            </div>
          </div>

          <div class="mt-4 space-y-2">
            <div class="rounded-2xl border border-white/10 bg-white/6 p-4">
              <p class="text-xs font-semibold text-white/70">BCA Virtual Account</p>
              <p class="mt-1 text-sm font-extrabold text-white">1234 5678 9012 3456</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/6 p-4">
              <p class="text-xs font-semibold text-white/70">GoPay / OVO</p>
              <p class="mt-1 text-sm font-extrabold text-white">0812-3456-7890</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/6 p-4">
              <p class="text-xs font-semibold text-white/70">QRIS</p>
              <p class="mt-1 text-sm font-extrabold text-white">Scan di aplikasi favorit</p>
            </div>
          </div>
        </section>
      </aside>
    </main>

    {{-- CAMPAIGNS --}}
    <section id="campaigns" class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
      <div class="text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90">
          {!! $ico['target'] !!} CAMPAIGN AKTIF
        </span>
        <h2 class="mt-3 text-2xl font-extrabold text-white sm:text-3xl">Program Donasi Masjid</h2>
        <p class="mx-auto mt-2 max-w-2xl text-sm text-white/75">
          Pilih campaign yang ingin Anda dukung. Semua donasi dicatat secara transparan.
        </p>
      </div>

      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($donasis as $c)
          @php
            $p = $c->target_dana > 0 ? min(100, max(0, ($c->dana_terkumpul / $c->target_dana) * 100)) : 0;
            $left = $daysLeft($c->tanggal_selesai);
            $donors = (int) ($c->transaksi_donasis_count ?? 0);
            $poster = $c->poster ?? $posterFallback;
          @endphp

          <article class="glass overflow-hidden rounded-[28px]">
            <div class="relative h-44">
              <img src="{{ $poster }}" alt="{{ $c->judul }}"
                   class="h-full w-full object-cover opacity-90" referrerpolicy="no-referrer">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

              <span class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/85 px-3 py-1 text-[11px] font-extrabold text-[#13392f]">
                {!! $ico['target'] !!} Program Masjid
              </span>

              <span class="absolute right-3 top-3 inline-flex items-center rounded-full border border-white/12 bg-white/10 px-3 py-1 text-[11px] font-semibold text-white">
                {{ is_null($left) ? 'Fleksibel' : $left . ' hari lagi' }}
              </span>
            </div>

            <div class="p-5">
              <h3 class="text-base font-extrabold text-white line-clamp-2">{{ $c->judul }}</h3>
              <p class="mt-2 text-sm text-white/70 line-clamp-3">
                {{ \Illuminate\Support\Str::limit(strip_tags($c->deskripsi), 160) }}
              </p>

              <div class="mt-4 rounded-2xl border border-white/10 bg-white/6 p-4">
                <div class="flex items-center justify-between text-xs font-semibold text-white/70">
                  <span>Terkumpul</span>
                  <span>Rp {{ number_format($c->dana_terkumpul, 0, ',', '.') }}</span>
                </div>

                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/10">
                  <div class="h-full rounded-full"
                    style="width: {{ $p }}%; background: linear-gradient(90deg, var(--accent), #0F4A3A);">
                  </div>
                </div>

                <div class="mt-2 flex items-center justify-between text-xs text-white/70">
                  <span>Target: <span class="font-semibold text-white">Rp {{ number_format($c->target_dana, 0, ',', '.') }}</span></span>
                  <span>Donatur: <span class="font-semibold text-white">{{ number_format($donors) }}</span></span>
                </div>
              </div>

              <button type="button"
                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
                style="background: var(--accent);"
                data-scroll="#donation-form"
                data-campaign-id="{{ $c->id }}">
                {!! $ico['heart'] !!} Donasi Campaign Ini
              </button>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-6">
        {{ $donasis->links() }}
      </div>
    </section>

    {{-- NEWSLETTER --}}
    <section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
      <div class="glass rounded-[28px] p-6 text-center">
        <div class="mx-auto flex max-w-2xl flex-col items-center">
          <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
            {!! $ico['mail'] !!}
          </span>
          <h2 class="mt-3 text-xl font-extrabold text-white">Saling Menguatkan dalam Kebaikan</h2>
          <p class="mt-2 text-sm text-white/70">
            Dapatkan update campaign donasi terbaru langsung ke email Anda.
          </p>

          <form class="mt-4 flex w-full max-w-xl flex-col gap-2 sm:flex-row"
                method="{{ $newsletterMethod }}" action="{{ $newsletterAction }}">
            @if ($newsletterHasRoute)
              @csrf
            @endif
            <input type="email" name="email" required
              class="field-light h-12 w-full flex-1 rounded-2xl border px-4 text-sm"
              placeholder="Email Anda">
            <button type="submit"
              class="h-12 rounded-2xl px-5 text-sm font-semibold text-[#13392f] hover:brightness-110"
              style="background: var(--accent);">
              Berlangganan
            </button>
          </form>
        </div>
      </div>
    </section>

    {{-- MOBILE STICKY CTA --}}
    <div class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-[#13392f]/85 p-3 backdrop-blur lg:hidden">
      <div class="mx-auto max-w-7xl grid grid-cols-2 gap-2">
        <button type="button" data-scroll="#donation-form"
          class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f]"
          style="background: var(--accent);">
          Donasi
        </button>
        <button type="button" data-scroll="#campaigns"
          class="w-full rounded-2xl border border-white/12 bg-white/6 px-4 py-3 text-sm font-semibold text-white">
          Campaign
        </button>
      </div>
    </div>

    {{-- JS: scroll + preset amount + frequency + campaign pick --}}
    <script>
      (function () {
        const rupiah = (n) => new Intl.NumberFormat("id-ID", {
          style: "currency", currency: "IDR", maximumFractionDigits: 0
        }).format(n || 0);

        // smooth scroll
        document.querySelectorAll("[data-scroll]").forEach((btn) => {
          btn.addEventListener("click", () => {
            const target = btn.getAttribute("data-scroll");
            const el = document.querySelector(target);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
          });
        });

        // update form action when campaign dipilih
        const form = document.getElementById("donationForm");
        const campaignSelect = document.getElementById("campaign_id");
        const actionTemplate = form?.dataset.actionTemplate || "";
        const defaultDonasiId = form?.dataset.defaultDonasi || "";

        if (form && campaignSelect && actionTemplate.includes("__ID__")) {
          const updateAction = () => {
            const chosen = campaignSelect.value || defaultDonasiId;
            if (chosen) form.action = actionTemplate.replace("__ID__", chosen);
          };
          campaignSelect.addEventListener("change", updateAction);
          updateAction();
        }

        // campaign buttons -> select + change
        document.querySelectorAll("[data-campaign-id]").forEach((btn) => {
          btn.addEventListener("click", () => {
            const chosen = btn.getAttribute("data-campaign-id") || "";
            if (campaignSelect) {
              campaignSelect.value = chosen;
              campaignSelect.dispatchEvent(new Event("change"));
            }
          });
        });

        // amount preset
        const amountButtons = Array.from(document.querySelectorAll(".js-amount"));
        const amountCustom = document.getElementById("amount_custom");
        const amountFinal = document.getElementById("amount_final");
        const amountPreview = document.getElementById("amount_preview");
        const initialAmount = parseInt(form?.dataset.initialAmount || "0", 10) || 0;

        function setActive(btn) {
          amountButtons.forEach(b => b.classList.remove("ring-2", "ring-[rgba(231,177,75,0.55)]", "bg-white/10"));
          if (btn) btn.classList.add("ring-2", "ring-[rgba(231,177,75,0.55)]", "bg-white/10");
        }

        function setAmount(n, { fromCustom = false } = {}) {
          const clean = Math.max(0, parseInt(n || "0", 10));
          if (amountFinal) amountFinal.value = String(clean);
          if (amountPreview) amountPreview.textContent = rupiah(clean);
          if (!fromCustom && amountCustom) amountCustom.value = clean ? clean : "";
        }

        setAmount(initialAmount);
        const defaultBtn = amountButtons.find(b => b.getAttribute("data-amount") === String(initialAmount));
        setActive(defaultBtn || null);

        amountButtons.forEach((btn) => {
          btn.addEventListener("click", () => {
            const n = parseInt(btn.getAttribute("data-amount") || "0", 10);
            setActive(btn);
            setAmount(n);
          });
        });

        if (amountCustom) {
          amountCustom.addEventListener("input", () => {
            setActive(null);
            setAmount(amountCustom.value, { fromCustom: true });
          });
        }

        // frequency
        const freqButtons = Array.from(document.querySelectorAll(".js-frequency"));
        const freqInput = document.getElementById("frequency");
        freqButtons.forEach((btn) => {
          btn.addEventListener("click", () => {
            freqButtons.forEach(b => b.classList.remove("ring-2", "ring-[rgba(231,177,75,0.55)]", "bg-white/10"));
            btn.classList.add("ring-2", "ring-[rgba(231,177,75,0.55)]", "bg-white/10");
            if (freqInput) freqInput.value = btn.getAttribute("data-value") || "once";
          });
        });
      })();
    </script>

  </div>
</x-front-layout>
