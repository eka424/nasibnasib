<x-front-layout>
@php
  $title = $artikel->title ?? 'Detail Artikel';
  $dateLabel = optional($artikel->created_at)->locale('id')->translatedFormat('d F Y') ?? '';
  $thumb = $artikel->thumbnail
      ? Storage::url($artikel->thumbnail)
      : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';
  $category = $artikel->category->name ?? ($artikel->kategori ?? 'Artikel Jamaah');
  $author = $artikel->author->name ?? ($artikel->penulis ?? 'Tim Redaksi');

  $plain = strip_tags($artikel->content ?? '');
  $words = str_word_count($plain);
  $readMin = max(1, (int) ceil($words / 200));

  // THEME (match front-layout)
  $bg = '#13392f';
  $accent = '#E7B14B';

  // ICONS: simple outline (flaticon-like common pack look)
  $ico = [
    'tag' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13l-7 7-11-11V2h7l11 11z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>',
    'user' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'calendar' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
    'clock' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
    'list' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>',
    'link' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.07 0l1.41-1.41a5 5 0 0 0-7.07-7.07L10 4.93"/><path d="M14 11a5 5 0 0 0-7.07 0L5.52 12.4a5 5 0 0 0 7.07 7.07L14 19.07"/></svg>',
    'copy' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>',
    'wa' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 11.5a8.5 8.5 0 0 1-12.7 7.4L3 20l1.2-4.5A8.5 8.5 0 1 1 20.5 11.5z"/><path d="M8.5 9.2c.2-1 .8-1.2 1.2-1.2.3 0 .6.1.8.5l.6 1c.2.4.1.8-.2 1.1l-.3.3c.5 1.1 1.4 2 2.5 2.5l.3-.3c.3-.3.7-.4 1.1-.2l1 .6c.4.2.5.5.5.8 0 .4-.2 1-1.2 1.2-1 .2-3.2-.4-4.8-2s-2.2-3.8-2-4.8z"/></svg>',
    'question' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 0 1 5 0c0 2-2.5 2-2.5 4"/><path d="M12 17h.01"/></svg>',
  ];
@endphp

<div class="min-h-screen overflow-x-hidden text-white" style="background: {{ $bg }}; --accent: {{ $accent }};">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap');
    body{font-family:Inter,ui-sans-serif,system-ui,-apple-system;}
    h1,h2,h3,.font-heading{font-family:Poppins,ui-sans-serif,system-ui,-apple-system;}
    section[id]{ scroll-margin-top: 96px; }

    /* Content typography (inside white card) */
    .article-content{color:#0f172a; line-height:1.9;}
    .article-content p{margin:.9rem 0; color:#334155;}
    .article-content strong{color:#0f172a;}
    .article-content h2{
      font-family:Poppins, ui-sans-serif, system-ui;
      font-weight:800; font-size:1.55rem;
      margin-top:2rem; margin-bottom:.75rem;
      color:#0f172a; letter-spacing:-.02em;
    }
    .article-content h3{
      font-family:Poppins, ui-sans-serif, system-ui;
      font-weight:800; font-size:1.15rem;
      margin-top:1.25rem; margin-bottom:.5rem;
      color:#0f172a;
    }
    .article-content ul{margin:.75rem 0 .75rem 1.25rem;}
    .article-content li{margin:.4rem 0; color:#334155;}
    .article-content a{color:#0F4A3A; text-decoration:underline; text-underline-offset: 3px;}

    .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
    .scrollbar-none::-webkit-scrollbar{display:none;}
    svg{ stroke: currentColor; }
    svg *{ vector-effect: non-scaling-stroke; }
  </style>

  {{-- Progress bar (clean + brand accent) --}}
  <div class="fixed inset-x-0 top-0 z-50 h-1 bg-white/10">
    <div id="readProgress" class="h-1 w-0" style="background: var(--accent);"></div>
  </div>

  {{-- HERO --}}
  <header class="relative overflow-hidden pt-10 sm:pt-14">
    <div class="absolute inset-0 -z-10">
      <img src="{{ $thumb }}" alt="{{ $title }}"
        class="h-full w-full object-cover opacity-20"
        referrerpolicy="no-referrer"
        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';" />
      {{-- subtle overlays (not too gradient-heavy) --}}
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/25 to-black/65"></div>
      <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-10 h-80 w-80 rounded-full bg-white/8 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
      <div class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 text-xs font-semibold text-white/90 backdrop-blur">
        <span class="inline-flex text-white/85">{!! $ico['tag'] !!}</span>
        {{ $category }}
      </div>

      <h1 class="mt-3 font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl">
        {{ $title }}
      </h1>

      <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-white/85">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 backdrop-blur">
          <span class="inline-flex">{!! $ico['user'] !!}</span> {{ $author }}
        </span>
        <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 backdrop-blur">
          <span class="inline-flex">{!! $ico['calendar'] !!}</span> {{ $dateLabel }}
        </span>
        <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 backdrop-blur">
          <span class="inline-flex">{!! $ico['clock'] !!}</span> {{ $readMin }} menit baca
        </span>
      </div>
    </div>
  </header>

  {{-- MAIN --}}
  <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-12">
      {{-- CONTENT --}}
      <div class="lg:col-span-8">
        <article class="overflow-hidden rounded-[28px] border border-white/12 bg-white text-slate-900 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.45)]">
          @php
            $artikelData = $artikel ?? $artikelDetail ?? $post ?? null;
            $thumb = $artikelData->thumbnail ?? null;
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
              $thumbUrl = 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';
            }
          @endphp
          <img src="{{ $thumbUrl }}" alt="{{ $title }}"
            class="h-56 w-full object-cover sm:h-72"
            referrerpolicy="no-referrer"
            loading="lazy"
            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';" />

          {{-- Controls --}}
          <div class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
            <div class="text-xs font-semibold text-slate-600">
              Mode baca nyaman • Inter (body) + Poppins (judul)
            </div>

            <div class="inline-flex items-center overflow-hidden rounded-2xl border border-slate-200 bg-white">
              <button type="button" data-font="1"
                class="px-3 py-2 text-xs font-semibold bg-[#13392f] text-white">
                A
              </button>
              <button type="button" data-font="1.06"
                class="px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                A+
              </button>
              <button type="button" data-font="1.12"
                class="px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                A++
              </button>
            </div>
          </div>

          {{-- Content --}}
          <div class="px-5 py-6 sm:px-7">
            <div id="articleContent" class="article-content" style="font-size:1rem;">
              {!! $artikel->content !!}
            </div>
          </div>
        </article>

        {{-- Bottom actions (mobile-friendly) --}}
        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
          <a href="{{ route('artikel.index') }}"
             class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-white/14 bg-white/6 px-4 py-3 text-sm font-semibold text-white backdrop-blur hover:bg-white/10">
            {!! $ico['list'] !!} Kembali ke Artikel
          </a>
          <a href="{{ route('tanya-ustadz.index') }}"
             class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f]"
             style="background: var(--accent);">
            {!! $ico['question'] !!} Tanya Ustadz
          </a>
        </div>
      </div>

      {{-- SIDEBAR --}}
      <aside class="lg:col-span-4">
        <div class="sticky top-6 space-y-4">

          {{-- TOC --}}
          <div class="rounded-[28px] border border-white/12 bg-white/6 p-5 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur">
            <div class="flex items-center justify-between">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/75">Daftar Isi</p>
              <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/80">
                {!! $ico['list'] !!} TOC
              </span>
            </div>

            <div id="toc" class="mt-3 space-y-1"></div>

            <p id="tocEmpty" class="mt-2 hidden text-sm text-white/60">
              Tambahkan heading <b>H2</b>/<b>H3</b> agar daftar isi muncul.
            </p>
          </div>

          {{-- Share --}}
          <div class="rounded-[28px] border border-white/12 bg-white/6 p-5 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur">
            <p class="text-sm font-semibold text-white">Bagikan</p>
            <p class="mt-1 text-xs text-white/65">Sebarkan manfaatnya ke jamaah lain.</p>

            <div class="mt-3 grid grid-cols-2 gap-2">
              <button type="button" id="btnCopy"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                {!! $ico['copy'] !!} Salin
              </button>

              <a target="_blank"
                 href="https://wa.me/?text={{ urlencode($title.' - '.request()->fullUrl()) }}"
                 class="inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-center text-sm font-semibold text-[#13392f]"
                 style="background: var(--accent);">
                {!! $ico['wa'] !!} WhatsApp
              </a>
            </div>

            <div class="mt-3 rounded-2xl border border-white/12 bg-white/5 p-3 text-xs text-white/70">
              <span class="inline-flex items-center gap-2">
                {!! $ico['link'] !!} <span class="line-clamp-1">{{ request()->fullUrl() }}</span>
              </span>
            </div>
          </div>

          {{-- CTA Tanya Ustadz --}}
          <div class="rounded-[28px] border border-white/12 p-5 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur"
               style="background: rgba(231,177,75,0.12);">
            <p class="text-sm font-semibold text-white inline-flex items-center gap-2">
              {!! $ico['question'] !!} Tanya Ustadz
            </p>
            <p class="mt-1 text-xs text-white/70">Butuh penjelasan lebih lanjut? Kirim pertanyaan kamu.</p>

            <a href="{{ route('tanya-ustadz.index') }}"
              class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold text-[#13392f]"
              style="background: var(--accent);">
              Mulai Tanya →
            </a>
          </div>
        </div>
      </aside>
    </div>
  </main>

  {{-- SCRIPTS --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Progress bar
      const bar = document.getElementById('readProgress');
      const onScroll = () => {
        const doc = document.documentElement;
        const sc = doc.scrollTop || document.body.scrollTop;
        const height = doc.scrollHeight - doc.clientHeight;
        const p = height > 0 ? Math.round((sc / height) * 100) : 0;
        bar.style.width = p + '%';
      };
      window.addEventListener('scroll', onScroll, { passive:true });
      onScroll();

      // Font scale controls
      const content = document.getElementById('articleContent');
      const fontBtns = document.querySelectorAll('[data-font]');
      fontBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const scale = btn.getAttribute('data-font') || '1';
          if (content) content.style.fontSize = scale + 'rem';

          fontBtns.forEach(b => {
            b.classList.remove('bg-[#13392f]','text-white');
            b.classList.add('text-slate-700');
          });
          btn.classList.add('bg-[#13392f]','text-white');
          btn.classList.remove('text-slate-700');
        });
      });

      // TOC build from H2/H3
      const tocWrap = document.getElementById('toc');
      const tocEmpty = document.getElementById('tocEmpty');

      if (content && tocWrap) {
        const hs = Array.from(content.querySelectorAll('h2, h3'));
        if (!hs.length) {
          tocEmpty?.classList.remove('hidden');
        } else {
          hs.forEach(h => {
            const text = (h.textContent || '').trim();
            if (!text) return;

            if (!h.id) {
              h.id = text.toLowerCase()
                .replace(/[^\w\s-]/g,'')
                .replace(/\s+/g,'-')
                .slice(0, 60);
            }

            const isH3 = h.tagName.toLowerCase() === 'h3';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className =
              'w-full rounded-2xl px-3 py-2 text-left text-sm transition hover:bg-white/10 ' +
              (isH3 ? 'pl-7 text-white/70' : 'text-white/90');

            btn.textContent = text;
            btn.addEventListener('click', () => {
              document.getElementById(h.id)?.scrollIntoView({ behavior:'smooth', block:'start' });
            });
            tocWrap.appendChild(btn);
          });
        }
      }

      // Copy link
      const btnCopy = document.getElementById('btnCopy');
      btnCopy?.addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(window.location.href);
          btnCopy.innerHTML = `{!! addslashes($ico['copy']) !!} Tersalin ✓`;
          setTimeout(() => btnCopy.innerHTML = `{!! addslashes($ico['copy']) !!} Salin`, 1400);
        } catch(e) {
          alert('Gagal menyalin link');
        }
      });
    });
  </script>
</div>
</x-front-layout>
