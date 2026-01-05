<x-front-layout>
@php
  // ========= THEME (match your front) =========
  $bg = '#13392f';
  $accent = '#E7B14B';
  $primary = '#0F4A3A';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ========= ICONS (simple outline / flaticon-like) =========
  $ico = [
    'arrow' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>',
    'question' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"/><path d="M12 17h.01"/><circle cx="12" cy="12" r="9"/></svg>',
    'user' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>',
    'check' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
    'tag' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.2l-7.4 7.4a2 2 0 0 1-2.8 0L3 13.2V3h10.2l7.4 7.4a2 2 0 0 1 0 2.8z"/><path d="M7.5 7.5h.01"/></svg>',
    'calendar' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
    'shield' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4z"/></svg>',
    'send' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4z"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }
  .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
  .scrollbar-none::-webkit-scrollbar{display:none;}

  /* IMPORTANT: make select/textarea readable (native dropdown often white) */
  .form-dark {
    background: rgba(255,255,255,0.94);
    color: #0f172a;
    border-color: rgba(15,23,42,0.10);
  }
  .form-dark::placeholder { color: rgba(15,23,42,0.45); }
  /* highlight card saat klik ajukan pertanyaan */
  /* kebyet-kebyet highlight */
@keyframes kebyet {
  0%   { box-shadow: 0 0 0 0 rgba(231,177,75,0), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(0); }
  15%  { box-shadow: 0 0 0 3px rgba(231,177,75,0.70), 0 0 30px rgba(231,177,75,0.35), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(-1px); }
  30%  { box-shadow: 0 0 0 0 rgba(231,177,75,0), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(0); }
  45%  { box-shadow: 0 0 0 3px rgba(231,177,75,0.70), 0 0 34px rgba(231,177,75,0.40), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(-1px); }
  60%  { box-shadow: 0 0 0 0 rgba(231,177,75,0), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(0); }
  100% { box-shadow: 0 0 0 0 rgba(231,177,75,0), 0 18px 60px -45px rgba(0,0,0,0.55); transform: translateY(0); }
}

[data-ask-card].is-focus {
  border-color: rgba(231,177,75,0.65) !important;
  animation: kebyet 0.85s ease-out 1;
}


  /* modal popup */
  .modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 16px;
  }
  .modal-backdrop.show { display: flex; }

  .modal-box {
    width: 100%;
    max-width: 420px;
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.14);
    background: rgba(19,57,47,0.92);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: 0 20px 70px -40px rgba(0,0,0,0.75);
    color: #fff;
    padding: 18px;
  }
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">

  {{-- HERO --}}
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10">
      <img
        src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=80"
        alt="Masjid"
        class="h-full w-full object-cover opacity-30"
        referrerpolicy="no-referrer"
      >
      <div class="absolute inset-0 bg-gradient-to-b from-black/65 via-black/35 to-black/70"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 pt-10 pb-10 sm:pt-12 sm:pb-12">
      <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">

        <div class="space-y-3 md:max-w-3xl">
          <p class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90">
            {!! $ico['question'] !!} Konsultasi Jamaah
          </p>

          <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight">
            Tanya Ustadz
          </h1>

          <p class="text-white/75 max-w-2xl">
            Ajukan pertanyaan seputar aqidah, fiqih, akhlak, hingga muamalah. Ustadz kami akan menjawab dengan rujukan yang insyaAllah shahih.
          </p>

          <div class="flex flex-col gap-2 pt-3 sm:flex-row sm:flex-wrap sm:gap-3">
            <a href="#ajukan-pertanyaan"
  id="btnAjukanPertanyaan"
  data-auth="{{ auth()->check() ? 1 : 0 }}"
  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold text-[#13392f] transition hover:brightness-105 sm:w-auto"
  style="background: var(--accent);">
  {!! $ico['send'] !!} Ajukan Pertanyaan {!! $ico['arrow'] !!}
</a>
<span id="authState" class="hidden" data-auth="{{ auth()->check() ? 1 : 0 }}"></span>

            @auth
              <a href="{{ route('tanya-ustadz.my') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold border border-white/12 bg-white/6 text-white hover:bg-white/10 sm:w-auto">
                {!! $ico['user'] !!} Pertanyaan Saya
              </a>
            @else
              <a href="{{ route('login') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold border border-white/12 bg-white/6 text-white hover:bg-white/10 sm:w-auto">
                {!! $ico['user'] !!} Masuk untuk bertanya
              </a>
            @endauth
          </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-3 w-full md:w-[340px]">
          <div class="{{ $glass }} p-4">
            <p class="text-xs text-white/70">Pertanyaan dijawab</p>
            <p class="text-2xl font-extrabold text-white mt-1">
              {{ number_format($pertanyaans->total()) }}
            </p>
            <p class="text-[11px] text-white/65">Ditampilkan untuk jamaah</p>
          </div>

          <div class="{{ $glass }} p-4">
            <p class="text-xs text-white/70">Topik</p>
            <p class="text-2xl font-extrabold text-white mt-1">{{ count($categories) }}</p>
            <p class="text-[11px] text-white/65">Aqidah • Fiqih • Akhlak</p>
          </div>

          <div class="col-span-2 {{ $glass }} p-4">
            <p class="text-xs text-white/70">Respon ustadz</p>
            <div class="flex items-center gap-2 mt-2">
              <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white/90">
                {!! $ico['shield'] !!}
              </span>
              <div>
                <p class="text-sm font-semibold text-white">Ustadz terverifikasi</p>
                <p class="text-[11px] text-white/65">Jawaban dicek sebelum tampil</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- CONTENT --}}
  <main class="max-w-6xl mx-auto px-4 pb-16 -mt-6 space-y-6">

    @if (session('success'))
      <div class="rounded-2xl border border-emerald-200/60 bg-emerald-500/15 px-4 py-3 text-sm text-emerald-50">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="rounded-2xl border border-rose-200/60 bg-rose-500/15 px-4 py-3 text-sm text-rose-50">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">

      {{-- LIST --}}
      <div class="lg:col-span-2 space-y-4">
        @forelse ($pertanyaans as $pertanyaan)
          @php
            $categoryLabel = $categories[$pertanyaan->kategori] ?? ucfirst($pertanyaan->kategori ?? 'Umum');
            $ustadzName = $pertanyaan->ustadz->name ?? 'Belum ditetapkan';
            $penanya = $pertanyaan->penanya->name ?? 'Jamaah';
            $initial = strtoupper(mb_substr($penanya, 0, 1));
            $tanggal = optional($pertanyaan->created_at)->translatedFormat('d M Y');
          @endphp

          <article class="{{ $glass }} p-5 transition hover:-translate-y-0.5 hover:bg-white/10">
            <div class="flex items-center gap-3 text-xs text-white/70">
              <div class="h-10 w-10 rounded-2xl border border-white/12 bg-white/6 grid place-content-center font-extrabold text-white">
                {{ $initial }}
              </div>

              <div class="min-w-0">
                <p class="font-semibold text-white">{{ $penanya }}</p>
                <p class="text-white/60">{{ $tanggal }}</p>
              </div>

              <div class="ml-auto flex items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-[11px] font-semibold text-white/90">
                  {!! $ico['tag'] !!} {{ $categoryLabel }}
                </span>

                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold text-[#13392f]"
                  style="background: var(--accent);">
                  {!! $ico['check'] !!} Dijawab
                </span>
              </div>
            </div>

            <h3 class="mt-3 text-lg md:text-xl font-extrabold text-white leading-snug">
              {{ $pertanyaan->pertanyaan }}
            </h3>

            <p class="mt-1 text-sm text-white/75">
              Dijawab oleh <span class="font-semibold text-white">{{ $ustadzName }}</span>
            </p>

            @if ($pertanyaan->jawaban)
              <div class="mt-4 rounded-2xl border border-white/12 bg-white/6 p-4 text-sm text-white/85 leading-relaxed">
                <p class="text-xs font-semibold text-white/70 uppercase tracking-wide">Jawaban</p>
                <div class="mt-2 space-y-2 leading-relaxed">
                  {!! nl2br(e($pertanyaan->jawaban)) !!}
                </div>
              </div>
            @else
              <p class="mt-3 text-sm text-amber-200/90">Jawaban sedang diproses oleh ustadz.</p>
            @endif
          </article>
        @empty
          <div class="{{ $glass }} p-8 text-center space-y-2">
            <p class="text-lg font-extrabold text-white">Belum ada pertanyaan dijawab.</p>
            <p class="text-white/70 text-sm">Jadilah yang pertama mengajukan pertanyaan untuk ustadz.</p>
            <a href="#ajukan-pertanyaan"
              class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:brightness-105"
              style="background: var(--accent);">
              {!! $ico['send'] !!} Ajukan Pertanyaan
            </a>
          </div>
        @endforelse

        @if (method_exists($pertanyaans, 'links'))
          <div class="pt-2">
            {{ $pertanyaans->links() }}
          </div>
        @endif
      </div>

      {{-- FORM --}}
      <div class="space-y-4">
        <div id="ajukan-pertanyaan" data-ask-card class="{{ $glass }} p-6">
          <h2 class="text-lg font-extrabold text-white">Ajukan Pertanyaan</h2>
          <p class="mt-1 text-xs text-white/70">Pertanyaan akan ditinjau sebelum dijawab dan ditampilkan.</p>

          @auth
            <form action="{{ route('tanya-ustadz.store') }}" method="POST" class="mt-4 space-y-3">
              @csrf

              <div>
                <label class="text-xs font-semibold text-white/70">Kategori</label>
                <select name="kategori"
                  class="form-dark mt-1 w-full rounded-2xl border px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                  style="--tw-ring-color: rgba(231,177,75,0.55);">
                  @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(old('kategori', 'umum') === $value)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="text-xs font-semibold text-white/70">Pertanyaan</label>
                <textarea
                  name="pertanyaan"
                  rows="6"
                  class="form-dark mt-1 w-full rounded-2xl border px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                  style="--tw-ring-color: rgba(231,177,75,0.55);"
                  placeholder="Tulis pertanyaan Anda..."
                  required>{{ old('pertanyaan') }}</textarea>
              </div>

              <div class="text-[11px] text-white/65">
                Pertanyaan yang sudah diajukan akan muncul di halaman ini setelah dijawab.
              </div>

              <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:brightness-105"
                style="background: var(--accent);">
                {!! $ico['send'] !!} Kirim Pertanyaan
              </button>
            </form>
          @else
            <div class="mt-4 space-y-3 text-sm text-white/70">
              <p>Masuk terlebih dahulu untuk mengajukan pertanyaan.</p>
              <div class="flex flex-col gap-2">
                <a href="{{ route('login') }}"
                  class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2.5 font-semibold text-white hover:bg-white/10">
                  {!! $ico['user'] !!} Masuk
                </a>
                <a href="{{ route('register') }}"
                  class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 font-semibold text-[#13392f] hover:brightness-105"
                  style="background: var(--accent);">
                  {!! $ico['arrow'] !!} Daftar
                </a>
              </div>
            </div>
          @endauth
        </div>

        <div class="{{ $glass }} p-6 space-y-3">
          <h3 class="text-sm font-extrabold text-white uppercase tracking-wide">Panduan Singkat</h3>
          <ul class="space-y-2 text-sm text-white/75 list-disc list-inside">
            <li>Tulis pertanyaan dengan jelas agar ustadz mudah menjawab.</li>
            <li>Pilih kategori paling relevan untuk topik Anda.</li>
            <li>Jawaban ditampilkan setelah diverifikasi.</li>
          </ul>
        </div>
      </div>

    </div>
  </main>
</div>
<div id="loginPopup" class="modal-backdrop" aria-hidden="true">
  <div class="modal-box">
    <div class="flex items-start justify-between gap-3">
      <div>
        <p class="text-sm font-extrabold">Login dulu ya</p>
        <p class="mt-1 text-xs text-white/75">
          Kamu harus <span class="font-semibold text-white">login</span> / <span class="font-semibold text-white">daftar</span> dulu jika ingin bertanya.
        </p>
      </div>
      <button type="button" data-close-modal
        class="grid h-10 w-10 place-items-center rounded-2xl border border-white/12 bg-white/6 text-white hover:bg-white/10">
        ✕
      </button>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-2">
      <a href="{{ route('login') }}"
        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
        {!! $ico['user'] !!} Masuk
      </a>
      <a href="{{ route('register') }}"
        class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:brightness-105"
        style="background: var(--accent);">
        {!! $ico['arrow'] !!} Daftar
      </a>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnAjukanPertanyaan');
    const modal = document.getElementById('loginPopup');
    const askCard = document.querySelector('[data-ask-card]');
    const textarea = document.querySelector('textarea[name="pertanyaan"]');
    const authEl = document.getElementById('authState');

    if (!btn) return;

    function getIsAuth() {
      // ambil dari marker dulu (lebih aman), fallback ke data-auth tombol
      const v = (authEl?.dataset.auth ?? btn.dataset.auth ?? '0').toString().trim();
      return v === '1' || v === 'true';
    }

    function openModal() {
      if (!modal) return;
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      if (!modal) return;
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    // close modal actions
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
      });

      modal.querySelectorAll('[data-close-modal]').forEach(el => {
        el.addEventListener('click', closeModal);
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });

    btn.addEventListener('click', (e) => {
      e.preventDefault();

      const isAuth = getIsAuth();

      if (!isAuth) {
        openModal();
        return;
      }

      // kalau login: scroll + highlight + focus input
      const target = document.getElementById('ajukan-pertanyaan');
      target?.scrollIntoView({ behavior: 'smooth', block: 'start' });

      if (askCard) {
  // restart animasi tiap klik
  askCard.classList.remove('is-focus');
  void askCard.offsetWidth; // paksa reflow biar animasi ke-reset
  askCard.classList.add('is-focus');

  setTimeout(() => askCard.classList.remove('is-focus'), 900);
}


      setTimeout(() => {
        textarea?.focus({ preventScroll: true });
      }, 450);
    });
  });
</script>


</x-front-layout>
