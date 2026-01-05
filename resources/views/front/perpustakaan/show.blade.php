<x-front-layout>
@php
  // ===== Data normalization =====
  $judul    = $perpustakaan->judul ?? 'Ebook Masjid';
  $penulis  = $perpustakaan->penulis ?? null;
  $kategori = $perpustakaan->kategori ?? optional($perpustakaan->category)->name ?? null;
  $tahun    = $perpustakaan->publish_year ?? $perpustakaan->tahun ?? null;
  $halaman  = $perpustakaan->pages ?? $perpustakaan->halaman ?? null;

  // ===== Helpers: extract drive id + normalize url =====
  $extractDriveId = function (?string $url): ?string {
    $url = trim((string) $url);
    if ($url === '') return null;

    if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) return $m[1];
    if (preg_match('~drive\.google\.com/open\?id=([^&]+)~', $url, $m)) return $m[1];
    if (str_contains($url, 'drive.google.com') && preg_match('~[?&]id=([^&]+)~', $url, $m)) return $m[1];

    return null;
  };

  $normalizeDriveThumb = function (?string $url) use ($extractDriveId): ?string {
    $url = trim((string) $url);
    if ($url === '') return null;

    // sudah thumbnail
    if (str_contains($url, 'drive.google.com/thumbnail')) return $url;

    $id = $extractDriveId($url);
    if ($id) {
      // opsi 1 (paling sering aman)
      return "https://drive.google.com/thumbnail?id={$id}&sz=w1200";
      // opsi 2 kalau mau coba ganti:
      // return "https://lh3.googleusercontent.com/d/{$id}=s1200";
    }

    return $url;
  };

  $normalizeDrivePdfPreview = function (?string $url) use ($extractDriveId): ?string {
    $url = trim((string) $url);
    if ($url === '') return null;

    if (str_contains($url, 'drive.google.com') && str_contains($url, '/preview')) return $url;

    $id = $extractDriveId($url);
    if ($id) return "https://drive.google.com/file/d/{$id}/preview";

    return $url;
  };

  $normalizeDriveDownload = function (?string $url) use ($extractDriveId): ?string {
    $url = trim((string) $url);
    if ($url === '') return null;

    $id = $extractDriveId($url);
    if ($id) return "https://drive.google.com/uc?export=download&id={$id}";

    return $url;
  };

  // ---- Cover URL (local vs http, + Drive normalize) ----
  $coverRaw = $perpustakaan->cover ?? $perpustakaan->thumbnail ?? $perpustakaan->gambar ?? null;

  if ($coverRaw && !str_starts_with($coverRaw, 'http')) {
    $coverUrl = Storage::url($coverRaw);
  } else {
    $coverUrl = $normalizeDriveThumb($coverRaw);
  }

  // ---- File URL (local vs http, + Drive PDF preview) ----
  $fileRaw = $perpustakaan->file_ebook ?? null;

  if ($fileRaw && !str_starts_with($fileRaw, 'http')) {
    $fileUrl = Storage::url($fileRaw);
  } else {
    $fileUrl = $normalizeDrivePdfPreview($fileRaw);
  }

  $desc = $perpustakaan->deskripsi ?? '';

  $viewCount     = (int) ($perpustakaan->view_count ?? 0);
  $downloadCount = (int) ($perpustakaan->download_count ?? 0);

  // ===== PDF detection =====
  $isPdf = (bool) ($fileUrl && (
      str_contains(strtolower($fileUrl), '.pdf')
      || str_contains($fileUrl, '/preview')
      || str_contains($fileUrl, 'drive.google.com/file/d/')
  ));

  // ===== Download url =====
  $downloadUrl = $normalizeDriveDownload($fileUrl) ?? $fileUrl;

  // ===== Theme =====
  $bg = '#13392f';
  $accent = '#E7B14B';
  $primary = '#0F4A3A';
  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ===== Icons =====
  $ico = [
    'home' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5l9-7 9 7"/><path d="M5 10v10h14V10"/></svg>',
    'library' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19a2 2 0 0 0 2 2h14"/><path d="M6 3h14v18H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>',
    'tag' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 13.4L13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>',
    'book' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19a2 2 0 0 0 2 2h14"/><path d="M6 3h14v18H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>',
    'eye' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>',
    'download' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>',
    'copy' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><rect x="2" y="2" width="13" height="13" rx="2"/></svg>',
    'open' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3h7v7"/><path d="M10 14L21 3"/><path d="M21 14v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6"/></svg>',
    'dot' => '<span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>',
  ];

  $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80';
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }
  .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }
</style>

<div class="min-h-screen text-white pb-[calc(92px+env(safe-area-inset-bottom))]" style="background: var(--bg);">

  {{-- HERO --}}
  <header class="relative overflow-hidden">
    <div aria-hidden class="absolute inset-0 -z-10">
      <img src="{{ $heroBg }}" alt="Masjid" class="h-full w-full object-cover opacity-30" referrerpolicy="no-referrer"/>
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-black/70"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
      {{-- Breadcrumb --}}
      <nav class="text-xs text-white/70">
        <ol class="flex flex-wrap items-center gap-2">
          <li>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:text-white">
              {!! $ico['home'] !!} Beranda
            </a>
          </li>
          <li class="text-white/40">/</li>
          <li>
            <a href="{{ route('perpustakaan.index') }}" class="inline-flex items-center gap-2 hover:text-white">
              {!! $ico['library'] !!} Perpustakaan
            </a>
          </li>
          <li class="text-white/40">/</li>
          <li class="font-semibold text-white/95 line-clamp-2">Detail Ebook</li>
        </ol>
      </nav>

      <div class="mt-4 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 text-xs font-semibold text-white/90">
          {!! $ico['dot'] !!} Ebook Masjid
        </span>

        @if($kategori)
          <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/8 px-3 py-1 text-xs font-semibold text-white/90">
            {!! $ico['tag'] !!} {{ $kategori }}
          </span>
        @endif

        @if($fileUrl)
          <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold text-[#13392f]" style="background: var(--accent);">
            {{ $isPdf ? 'PDF' : 'FILE' }}
          </span>
        @endif
      </div>

      <h1 class="mt-3 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
        {{ $judul }}
      </h1>

      @if($penulis)
        <p class="mt-2 text-sm text-white/80">
          oleh <span class="font-semibold text-white">{{ $penulis }}</span>
        </p>
      @endif
    </div>
  </header>

  {{-- CONTENT --}}
  <main class="mx-auto max-w-6xl px-4 pb-10 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-12">

      {{-- LEFT --}}
      <section class="lg:col-span-8 space-y-6">

        <div class="overflow-hidden rounded-[28px] border border-white/12 bg-white/95 text-slate-900 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)]">

          {{-- TOP: cover + meta --}}
          <div class="grid gap-0 sm:grid-cols-[220px_1fr]">
            <div class="relative">
              @if($coverUrl)
                <img
                  src="{{ $coverUrl }}"
                  alt="{{ $judul }}"
                  class="h-64 w-full object-cover sm:h-full sm:min-h-[320px]"
                  loading="lazy"
                  referrerpolicy="no-referrer"
                />
              @else
                <div class="grid h-64 w-full place-items-center bg-gradient-to-br from-emerald-100 to-emerald-200 sm:h-full sm:min-h-[320px]">
                  <span class="text-5xl">📚</span>
                </div>
              @endif

              <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
            </div>

            <div class="p-5 sm:p-7">
              <div class="flex flex-wrap gap-2">
                <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                  {!! $ico['eye'] !!}
                  <span class="text-slate-500">Dilihat</span>
                  <span class="font-semibold text-slate-900">{{ number_format($viewCount, 0, ',', '.') }}</span>
                </div>

                <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                  {!! $ico['download'] !!}
                  <span class="text-slate-500">Diunduh</span>
                  <span class="font-semibold text-slate-900">{{ number_format($downloadCount, 0, ',', '.') }}</span>
                </div>

                <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                  <span class="font-semibold text-slate-500">Hal</span>
                  <span class="font-semibold text-slate-900">{{ $halaman ?? '-' }}</span>
                </div>

                <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                  <span class="font-semibold text-slate-500">Tahun</span>
                  <span class="font-semibold text-slate-900">{{ $tahun ?? '-' }}</span>
                </div>
              </div>

              <div class="mt-5">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Ringkasan</p>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                  {{ $desc ?: 'Belum ada deskripsi.' }}
                </p>
              </div>

              {{-- Desktop actions --}}
              <div class="mt-6 hidden flex-wrap gap-2 sm:flex">
                <a href="{{ $downloadUrl ?: '#' }}" target="_blank" rel="noreferrer"
                   class="{{ $fileUrl ? 'text-[#13392f]' : 'cursor-not-allowed bg-slate-200 text-slate-500' }}
                          inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-extrabold shadow-sm transition hover:brightness-105"
                   style="{{ $fileUrl ? 'background: var(--accent);' : '' }}">
                  {!! $ico['download'] !!} Unduh
                </a>

                @if($fileUrl)
                  <a href="{{ $fileUrl }}" target="_blank" rel="noreferrer"
                     class="inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-extrabold text-emerald-700 shadow-sm transition hover:bg-emerald-100">
                    {!! $ico['open'] !!} Baca Online
                  </a>
                @endif

                <button type="button" data-copy-link
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-800 shadow-sm transition hover:bg-slate-50">
                  {!! $ico['copy'] !!} Salin Link
                </button>
              </div>
            </div>
          </div>

          {{-- PREVIEW --}}
          <div class="border-t border-slate-200 p-5 sm:p-7">
            <div class="flex items-end justify-between gap-3">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Preview</p>
                <p class="mt-1 text-sm text-slate-600">
                  Preview tampil otomatis jika file PDF (Google Drive / link PDF).
                </p>
              </div>
            </div>

            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
              @if($fileUrl && $isPdf)
                <iframe
                  title="Preview PDF"
                  src="{{ $fileUrl }}"
                  class="h-[70vh] w-full"
                  loading="lazy"
                  referrerpolicy="no-referrer"
                ></iframe>
              @else
                <div class="p-6 text-sm text-slate-600">
                  Preview tersedia untuk PDF. Silakan klik <span class="font-semibold text-slate-800">Baca Online</span> untuk membuka file.
                </div>
              @endif
            </div>

            @if($fileUrl)
              <a href="{{ $fileUrl }}" target="_blank" rel="noreferrer"
                 class="mt-3 inline-block text-sm font-semibold text-emerald-700 hover:underline">
                Buka di tab baru →
              </a>
            @endif
          </div>
        </div>
      </section>

      {{-- RIGHT --}}
      <aside class="lg:col-span-4">
        <div class="sticky top-6 space-y-4">

          <div class="{{ $glass }} p-5">
            <p class="text-sm font-semibold text-white">Aksi Cepat</p>
            <p class="mt-1 text-xs text-white/70">Unduh atau simpan link untuk dibaca nanti.</p>

            <div class="mt-4 grid gap-2">
              <a href="{{ $downloadUrl ?: '#' }}" target="_blank" rel="noreferrer"
                 class="{{ $fileUrl ? 'text-[#13392f]' : 'cursor-not-allowed bg-white/20 text-white/50' }}
                        inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-extrabold shadow-sm transition hover:brightness-105"
                 style="{{ $fileUrl ? 'background: var(--accent);' : '' }}">
                {!! $ico['download'] !!} Unduh Ebook
              </a>

              @if($fileUrl)
                <a href="{{ $fileUrl }}" target="_blank" rel="noreferrer"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-extrabold text-emerald-700 hover:bg-emerald-50">
                  {!! $ico['open'] !!} Baca Online
                </a>
              @endif

              <button type="button" data-copy-link
                      class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/14 bg-white/6 px-4 py-3 text-sm font-extrabold text-white hover:bg-white/10">
                {!! $ico['copy'] !!} Salin Link
              </button>
            </div>
          </div>

          <div class="{{ $glass }} p-5">
            <p class="text-sm font-semibold text-white">Catatan</p>
            <ul class="mt-3 space-y-2 text-sm text-white/80">
              <li class="flex gap-2"><span class="mt-0.5">•</span> Aktifkan mode baca malam agar nyaman.</li>
              <li class="flex gap-2"><span class="mt-0.5">•</span> Bagikan link agar jamaah lain ikut belajar.</li>
              <li class="flex gap-2"><span class="mt-0.5">•</span> Pastikan koneksi stabil saat unduh.</li>
            </ul>
          </div>

        </div>
      </aside>
    </div>
  </main>

  {{-- MOBILE STICKY CTA --}}
  <div class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-slate-950/75 p-3 backdrop-blur lg:hidden">
    <div class="mx-auto max-w-6xl px-1">
      <div class="grid grid-cols-2 gap-2">
        <a href="{{ $downloadUrl ?: '#' }}" target="_blank" rel="noreferrer"
           class="{{ $fileUrl ? 'text-[#13392f]' : 'cursor-not-allowed bg-white/20 text-white/50' }}
                  w-full rounded-2xl px-4 py-3 text-center text-sm font-extrabold transition hover:brightness-105"
           style="{{ $fileUrl ? 'background: var(--accent);' : '' }}">
          Unduh
        </a>

        <button type="button" data-copy-link
                class="w-full rounded-2xl bg-white px-4 py-3 text-center text-sm font-extrabold text-emerald-700 hover:bg-emerald-50">
          Share
        </button>
      </div>
    </div>
  </div>

  {{-- Copy link toast --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toast = (text) => {
        const t = document.createElement('div');
        t.textContent = text;
        t.className =
          'fixed left-1/2 top-6 z-[80] -translate-x-1/2 rounded-2xl border border-white/10 bg-slate-950/90 px-4 py-2 text-sm font-semibold text-white shadow-xl backdrop-blur';
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 1300);
      };

      const copy = async () => {
        const url = window.location.href;
        try {
          await navigator.clipboard.writeText(url);
          toast('Link tersalin ✅');
        } catch (e) {
          const tmp = document.createElement('input');
          tmp.value = url;
          document.body.appendChild(tmp);
          tmp.select();
          document.execCommand('copy');
          document.body.removeChild(tmp);
          toast('Link tersalin ✅');
        }
      };

      document.querySelectorAll('[data-copy-link]').forEach(btn => btn.addEventListener('click', copy));
    });
  </script>

</div>
</x-front-layout>
