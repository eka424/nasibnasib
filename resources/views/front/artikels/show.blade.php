<x-front-layout>
@php
  $title = $artikel->title ?? 'Detail Artikel';
  $dateLabel = optional($artikel->created_at)->locale('id')->translatedFormat('d F Y') ?? '';
  $thumb = $artikel->thumbnail ? Storage::url($artikel->thumbnail) : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';
  $category = $artikel->category->name ?? ($artikel->kategori ?? 'Artikel Jamaah');
  $author = $artikel->author->name ?? ($artikel->penulis ?? 'Tim Redaksi');

  // Perkiraan waktu baca (kata/200)
  $plain = strip_tags($artikel->content ?? '');
  $words = str_word_count($plain);
  $readMin = max(1, (int) ceil($words / 200));
@endphp

<div class="min-h-screen overflow-x-hidden bg-gradient-to-br from-slate-950 via-emerald-950/50 to-slate-900 text-slate-100">
  {{-- Fonts + helpers --}}
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Amiri:wght@400;700&display=swap');
    body{font-family:Inter, ui-sans-serif, system-ui, -apple-system;}
    h1,h2,h3,.font-heading{font-family:Poppins, ui-sans-serif, system-ui, -apple-system;}
    .arabic{font-family:'Amiri',serif;}

    /* typography */
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
    .article-content a{color:#047857; text-decoration:underline;}

    /* line clamp */
    .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
  </style>

  {{-- Progress bar --}}
  <div class="fixed inset-x-0 top-0 z-50 h-1 bg-white/10">
    <div id="readProgress" class="h-1 bg-emerald-400 w-0"></div>
  </div>

  {{-- HERO --}}
  <header class="relative overflow-hidden pt-12 sm:pt-16">
    <div class="absolute inset-0 -z-10">
      <img src="{{ $thumb }}" alt="{{ $title }}"
        class="h-full w-full object-cover opacity-30"
        referrerpolicy="no-referrer"
        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';" />
      <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-emerald-950/55 to-slate-900/95"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
      <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200/40 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-100">
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
        {{ $category }}
      </div>

      <h1 class="mt-3 font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl">
        {{ $title }}
      </h1>

      <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-emerald-50/90">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">✍️ {{ $author }}</span>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">📅 {{ $dateLabel }}</span>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">⏱️ {{ $readMin }} menit baca</span>
      </div>
    </div>
  </header>

  {{-- MAIN --}}
  <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-12">
      {{-- CONTENT --}}
      <div class="lg:col-span-8">
        <article class="overflow-hidden rounded-3xl border border-white/10 bg-white/95 text-slate-900 shadow-xl shadow-emerald-500/10">
          <img src="{{ $thumb }}" alt="{{ $title }}"
            class="h-56 w-full object-cover sm:h-72"
            referrerpolicy="no-referrer"
            loading="lazy"
            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80';" />

          {{-- Controls --}}
          <div class="flex flex-col gap-3 border-b border-slate-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
            <div class="text-xs font-semibold text-slate-600">
              Mode baca nyaman • Inter (body) + Poppins (judul)
            </div>

            <div class="inline-flex items-center overflow-hidden rounded-2xl border border-slate-200 bg-white">
              <button type="button" data-font="1"
                class="px-3 py-2 text-xs font-semibold bg-emerald-600 text-white">
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
      </div>

      {{-- SIDEBAR --}}
      <aside class="lg:col-span-4">
        <div class="sticky top-6 space-y-4">
          {{-- TOC --}}
          <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-lg shadow-emerald-500/10 backdrop-blur">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-200/80">Daftar Isi</p>
            <div id="toc" class="mt-3 space-y-1"></div>
            <p id="tocEmpty" class="mt-2 hidden text-sm text-white/60">
              Tambahkan heading <b>H2</b>/<b>H3</b> agar daftar isi muncul.
            </p>
          </div>

          {{-- CTA Tanya Ustadz --}}
          <div class="rounded-3xl border border-emerald-200/20 bg-emerald-500/10 p-5 shadow-lg shadow-emerald-500/10 backdrop-blur">
            <p class="text-sm font-semibold text-emerald-50">💡 Tanya Ustadz</p>
            <p class="mt-1 text-xs text-emerald-100/80">Butuh penjelasan lebih lanjut? Kirim pertanyaan kamu.</p>
            <a href="{{ route('tanya-ustadz.index') }}"
              class="mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
              Tanya Ustadz →
            </a>
          </div>

          {{-- Share --}}
          <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-lg shadow-emerald-500/10 backdrop-blur">
            <p class="text-sm font-semibold text-white">🔗 Bagikan</p>
            <div class="mt-3 grid grid-cols-2 gap-2">
              <button type="button" id="btnCopy"
                class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                Salin Link
              </button>
              <a target="_blank"
                 href="https://wa.me/?text={{ urlencode($title.' - '.request()->fullUrl()) }}"
                 class="rounded-2xl bg-emerald-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-emerald-700">
                WhatsApp
              </a>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </main>

  {{-- SCRIPTS --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // ===== Progress bar =====
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

      // ===== Font scale controls =====
      const content = document.getElementById('articleContent');
      const fontBtns = document.querySelectorAll('[data-font]');
      fontBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const scale = btn.getAttribute('data-font') || '1';
          if (content) content.style.fontSize = scale + 'rem';
          fontBtns.forEach(b => b.classList.remove('bg-emerald-600','text-white'));
          fontBtns.forEach(b => b.classList.add('text-slate-700'));
          btn.classList.add('bg-emerald-600','text-white');
          btn.classList.remove('text-slate-700');
        });
      });

      // ===== TOC build from H2/H3 =====
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
            btn.className = 'w-full rounded-2xl px-3 py-2 text-left text-sm transition hover:bg-white/10 ' +
              (isH3 ? 'pl-7 text-white/70' : 'text-white/90');
            btn.textContent = text;
            btn.addEventListener('click', () => {
              document.getElementById(h.id)?.scrollIntoView({ behavior:'smooth', block:'start' });
            });
            tocWrap.appendChild(btn);
          });
        }
      }

      // ===== Copy link =====
      const btnCopy = document.getElementById('btnCopy');
      btnCopy?.addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(window.location.href);
          btnCopy.textContent = 'Tersalin ✓';
          setTimeout(() => btnCopy.textContent = 'Salin Link', 1400);
        } catch(e) {
          alert('Gagal menyalin link');
        }
      });
    });
  </script>
</div>
</x-front-layout>
