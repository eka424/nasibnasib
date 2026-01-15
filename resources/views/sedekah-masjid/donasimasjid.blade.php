<x-front-layout>
  @php
    $bg = '#13392f';
    $accent = '#E7B14B';
    $posterFallback = 'https://images.unsplash.com/photo-1541417904950-b855846fe074?auto=format&fit=crop&w=1800&q=80';

    $ico = [
      'spark' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.2 6.2L20 10l-5.8 1.8L12 18l-2.2-6.2L4 10l5.8-1.8L12 2z"/></svg>',
      'hand'  => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 11v-1a2 2 0 0 1 4 0v1"/><path d="M11 10V8a2 2 0 1 1 4 0v2"/><path d="M15 10V9a2 2 0 1 1 4 0v5a6 6 0 0 1-6 6H9a6 6 0 0 1-6-6v-2a2 2 0 0 1 4 0v1"/></svg>',
      'target'=> '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></svg>',
      'chart' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-3 3 2 5-6"/></svg>',
      'users' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="3"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a3 3 0 0 1 0 5.74"/></svg>',
    ];

    $overallProgress = $overallProgress ?? 0;
    $recent = $recent ?? collect();
  @endphp

  <div class="min-h-screen text-white" style="background: {{ $bg }};">
    <header class="relative overflow-hidden border-b border-white/10">
      <div class="absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/35 to-black/70"></div>
        <div class="absolute -left-20 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
        <div class="absolute -right-24 top-10 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
      </div>

      <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90 backdrop-blur">
            {!! $ico['spark'] !!} SEDEKAH MASJID
          </span>

          <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
            Sedekah untuk Masjid Agung Al-A’la
          </h1>

          <p class="mt-2 text-sm text-white/80 sm:text-base">
            “Perumpamaan orang yang menafkahkan hartanya di jalan Allah seperti sebutir benih…” (QS. Al-Baqarah: 261).
          </p>

          <div class="mt-6 rounded-[28px] border border-white/14 bg-white/10 p-4 backdrop-blur sm:p-5">
            <div class="grid gap-3 sm:grid-cols-3">
              <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Total Terkumpul</p>
                <p class="mt-1 text-sm font-extrabold text-white">Rp {{ number_format($totalRaised ?? 0, 0, ',', '.') }}</p>
              </div>
              <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Target Keseluruhan</p>
                <p class="mt-1 text-sm font-extrabold text-white">Rp {{ number_format($totalTarget ?? 0, 0, ',', '.') }}</p>
              </div>
              <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-left">
                <p class="text-xs font-semibold text-white/65">Progress</p>
                <p class="mt-1 text-sm font-extrabold text-white">{{ number_format($overallProgress, 0) }}%</p>
              </div>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-white/10 p-4">
              <div class="flex items-center justify-between text-xs font-semibold text-white/70">
                <span>Progress keseluruhan</span>
                <span>{{ number_format($overallProgress, 0) }}%</span>
              </div>
              <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/15">
                <div class="h-full rounded-full" style="width: {{ $overallProgress }}%; background: linear-gradient(90deg, {{ $accent }}, #0F4A3A);"></div>
              </div>

              <div class="mt-4 flex flex-wrap justify-center gap-2">
                <button type="button" data-scroll="#form-sedekah"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
                        style="background: {{ $accent }};">
                  {!! $ico['hand'] !!} Mulai Sedekah
                </button>
                <button type="button" data-scroll="#program"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15">
                  {!! $ico['target'] !!} Lihat Program
                </button>
                @auth
                  <a href="{{ route('sedekah.riwayat') }}"
                     class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15">
                    {!! $ico['users'] !!} Riwayat
                  </a>
                @endauth
              </div>
            </div>
          </div>

        </div>
      </div>
    </header>

    <main id="form-sedekah" class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[1.35fr_.65fr] lg:px-8">

      {{-- FORM --}}
      <section class="overflow-hidden rounded-[28px] border border-white/14 bg-white/10 backdrop-blur">
        <div class="border-b border-white/10 bg-white/10 px-6 py-5">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white">
              {!! $ico['hand'] !!}
            </span>
            <div>
              <h2 class="text-base font-extrabold text-white">Form Sedekah</h2>
              <p class="mt-1 text-sm text-white/70">Niatkan karena Allah, isi data seperlunya, lalu lanjut pembayaran.</p>
            </div>
          </div>
        </div>

        <form class="space-y-5 px-6 py-6" method="POST" action="{{ route('sedekah.transaksi') }}" id="sedekahForm">
          @csrf

          <div>
            <label class="text-sm font-semibold text-white/85">Pilih Program (opsional)</label>
            <select name="sedekah_campaign_id" id="sedekah_campaign_id"
              class="mt-2 h-12 w-full rounded-2xl border border-white/15 bg-white px-4 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]">
              <option value="">Sedekah Umum Masjid</option>
              @foreach ($campaigns as $c)
                <option value="{{ $c->id }}">{{ $c->judul }}</option>
              @endforeach
            </select>
            <p class="mt-2 text-xs text-white/65">Jika tidak dipilih, sedekah akan masuk ke kas masjid (umum).</p>
          </div>

          <div>
            <div class="flex items-center justify-between">
              <label class="text-sm font-semibold text-white/85">Nominal Sedekah</label>
              <span class="text-xs font-semibold text-white/75">Minimal Rp 10.000</span>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
              @php $preset = [10000, 25000, 50000, 100000, 250000, 500000]; @endphp
              @foreach ($preset as $a)
                <button type="button"
                  class="js-amount rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-left text-sm font-semibold text-white hover:bg-white/15"
                  data-amount="{{ $a }}">
                  Rp {{ number_format($a, 0, ',', '.') }}
                </button>
              @endforeach
            </div>

            <input id="jumlah" name="jumlah" type="number" min="10000"
              class="mt-3 h-12 w-full rounded-2xl border border-white/15 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
              placeholder="Atau isi nominal lainnya (contoh: 125000)" required>

            @error('jumlah')
              <p class="mt-2 text-xs font-semibold text-red-200">{{ $message }}</p>
            @enderror
          </div>

          <div class="grid gap-3 sm:grid-cols-3">
            <div>
              <label class="text-sm font-semibold text-white/85">Nama (opsional)</label>
              <input name="nama"
                class="mt-2 h-12 w-full rounded-2xl border border-white/15 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                placeholder="Hamba Allah">
            </div>
            <div>
              <label class="text-sm font-semibold text-white/85">Email (opsional)</label>
              <input name="email" type="email"
                class="mt-2 h-12 w-full rounded-2xl border border-white/15 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                placeholder="email@example.com">
            </div>
            <div>
              <label class="text-sm font-semibold text-white">WhatsApp (opsional)</label>
              <input name="whatsapp"
                class="mt-2 h-12 w-full rounded-2xl border border-white/15 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                placeholder="08xxxxxxxxxx">
            </div>
          </div>

          <div>
            <label class="text-sm font-semibold text-white/85">Pesan / Doa (opsional)</label>
            <textarea name="pesan" rows="3"
              class="mt-2 w-full rounded-2xl border border-white/15 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
              placeholder="Semoga Allah menerima amal ini dan melapangkan rezeki."></textarea>
          </div>

          <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
            style="background: {{ $accent }};">
            {!! $ico['hand'] !!} Lanjut Pembayaran (Midtrans)
          </button>

          <p class="text-xs text-white/65">
            Pembayaran diproses melalui Midtrans (sandbox). Status sedekah akan ter-update otomatis setelah notifikasi diterima.
          </p>
        </form>
      </section>

      {{-- SIDEBAR --}}
      <aside class="space-y-4">
        <section class="rounded-[28px] border border-white/14 bg-white/10 p-5 backdrop-blur">
          <div class="flex items-start gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white">
              {!! $ico['chart'] !!}
            </span>
            <div>
              <h3 class="text-sm font-extrabold text-white">Sedekah Terbaru</h3>
              <p class="mt-1 text-xs text-white/70">Transaksi sukses terakhir.</p>
            </div>
          </div>

          <div class="mt-4 divide-y divide-white/10">
            @forelse ($recent as $r)
              <div class="flex items-center justify-between py-3">
                <div class="min-w-0">
                  <p class="text-sm font-bold text-white/95 truncate">
                    {{ $r->nama ?: 'Hamba Allah' }}
                  </p>
                  <p class="text-xs text-white/60">
                    {{ $r->campaign?->judul ?: 'Sedekah Umum Masjid' }} • {{ $r->created_at->diffForHumans() }}
                  </p>
                </div>
                <p class="text-sm font-extrabold text-white">
                  Rp {{ number_format($r->jumlah, 0, ',', '.') }}
                </p>
              </div>
            @empty
              <p class="text-sm text-white/70">Belum ada data sedekah berhasil.</p>
            @endforelse
          </div>
        </section>
      </aside>
    </main>

    {{-- PROGRAM --}}
    <section id="program" class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
      <div class="text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90">
          {!! $ico['target'] !!} PROGRAM SEDEKAH
        </span>
        <h2 class="mt-3 text-2xl font-extrabold text-white sm:text-3xl">Pilih Program yang Ingin Didukung</h2>
        <p class="mx-auto mt-2 max-w-2xl text-sm text-white/75">
          Admin dapat menambah program kapan saja. Sedekah umum tetap tersedia.
        </p>
      </div>

      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($campaigns as $c)
          @php
            $p = $c->target_dana > 0 ? min(100, max(0, ($c->dana_terkumpul / $c->target_dana) * 100)) : 0;
            $poster = $c->poster ?: $posterFallback;
          @endphp

          <article class="overflow-hidden rounded-[28px] border border-white/14 bg-white/10 backdrop-blur">
            <div class="relative h-44">
              <img src="{{ $poster }}" alt="{{ $c->judul }}" class="h-full w-full object-cover opacity-90" referrerpolicy="no-referrer">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
              <span class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/90 px-3 py-1 text-[11px] font-extrabold text-[#13392f]">
                {!! $ico['target'] !!} Program
              </span>
            </div>

            <div class="p-5">
              <h3 class="text-base font-extrabold text-white line-clamp-2">{{ $c->judul }}</h3>
              <p class="mt-2 text-sm text-white/70 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($c->deskripsi), 160) }}</p>

              <div class="mt-4 rounded-2xl border border-white/10 bg-white/10 p-4">
                <div class="flex items-center justify-between text-xs font-semibold text-white/70">
                  <span>Terkumpul</span>
                  <span>Rp {{ number_format($c->dana_terkumpul, 0, ',', '.') }}</span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/15">
                  <div class="h-full rounded-full" style="width: {{ $p }}%; background: linear-gradient(90deg, {{ $accent }}, #0F4A3A);"></div>
                </div>
                <div class="mt-2 text-xs text-white/70">
                  Target: <span class="font-semibold text-white">Rp {{ number_format($c->target_dana, 0, ',', '.') }}</span>
                </div>
              </div>

              <button type="button"
                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
                style="background: {{ $accent }};"
                data-scroll="#form-sedekah"
                data-campaign-pick="{{ $c->id }}">
                {!! $ico['hand'] !!} Pilih & Sedekah
              </button>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-6">
        {{ $campaigns->links() }}
      </div>
    </section>

    <script>
      (function () {
        // smooth scroll
        document.querySelectorAll("[data-scroll]").forEach((btn) => {
          btn.addEventListener("click", () => {
            const target = btn.getAttribute("data-scroll");
            const el = document.querySelector(target);
            if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
          });
        });

        // preset amount
        const amountButtons = Array.from(document.querySelectorAll(".js-amount"));
        const jumlah = document.getElementById("jumlah");

        function setActive(btn) {
          amountButtons.forEach(b => b.classList.remove("ring-2","ring-[rgba(231,177,75,0.55)]","bg-white/15"));
          if (btn) btn.classList.add("ring-2","ring-[rgba(231,177,75,0.55)]","bg-white/15");
        }

        amountButtons.forEach(btn => {
          btn.addEventListener("click", () => {
            const n = parseInt(btn.getAttribute("data-amount") || "0", 10);
            if (jumlah) jumlah.value = String(n);
            setActive(btn);
          });
        });

        // pick campaign from program cards
        const select = document.getElementById('sedekah_campaign_id');
        document.querySelectorAll('[data-campaign-pick]').forEach((btn) => {
          btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-campaign-pick');
            if (select) select.value = id;
          });
        });
      })();
    </script>
  </div>
</x-front-layout>
