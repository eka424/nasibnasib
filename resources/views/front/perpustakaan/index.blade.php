<x-front-layout>
@php
  $search = request('search');
  $selectedCategory = request('category');
  $viewMode = request('view', 'grid'); // grid|list
  $activeTab = request('tab', 'all'); // all|featured

  $booksCollection = $perpustakaans instanceof \Illuminate\Pagination\AbstractPaginator
    ? collect($perpustakaans->items())
    : collect($perpustakaans);

  $stats = [
    'books' => $totalBooks ?? $booksCollection->count(),
    'categories' => $totalCategories ?? ($categories->count() ?? 0),
    'views' => $totalViews ?? 0,
    'downloads' => $totalDownloads ?? 0,
  ];

  $categoryList = ($categories ?? collect())
    ->filter(fn ($cat) => ! empty($cat->kategori ?? $cat->name))
    ->map(fn ($cat) => $cat->kategori ?? $cat->name)
    ->unique()
    ->values();

  // ===== Theme (match front) =====
  $bg = '#13392f';
  $accent = '#E7B14B'; // gold
  $primary = '#0F4A3A';

  $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';
  $fallbackCover = 'https://images.unsplash.com/photo-1541417904950-b855846fe074?auto=format&fit=crop&w=1400&q=80';

  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ===== Helpers: Drive normalize (cover + pdf preview + download) =====
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

    // kalau sudah thumbnail / googleusercontent
    if (str_contains($url, 'drive.google.com/thumbnail')) return $url;
    if (str_contains($url, 'googleusercontent.com')) return $url;

    $id = $extractDriveId($url);
    if ($id) {
      return "https://drive.google.com/thumbnail?id={$id}&sz=w1200";
      // alternatif:
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

  // ===== Icons (simple outline / flaticon-like) =====
  $ico = [
    'book' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19a2 2 0 0 0 2 2h14"/><path d="M6 3h14v18H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>',
    'search' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>',
    'filter' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9v7l4 2v-9l8-9z"/></svg>',
    'grid' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="2"/><rect x="13" y="3" width="8" height="8" rx="2"/><rect x="3" y="13" width="8" height="8" rx="2"/><rect x="13" y="13" width="8" height="8" rx="2"/></svg>',
    'list' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>',
    'eye' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>',
    'download' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>',
    'star' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 7 7 3-7 3-3 7-3-7-7-3 7-3 3-7z"/></svg>',
    'chev' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>',
    'kid' => '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7 3.582 7 8 7Z"/><path d="M9 13.2h.01M15 13.2h.01"/><path d="M8.6 16.2s1.4 1.5 3.4 1.5 3.4-1.5 3.4-1.5"/><path d="M7.5 11.2c.6-2 2.4-3.4 4.5-3.4s3.9 1.4 4.5 3.4"/></svg>',
  ];
@endphp

<style>
  :root { --bg: {{ $bg }}; --accent: {{ $accent }}; --primary: {{ $primary }}; }

  .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
  .scrollbar-none::-webkit-scrollbar{display:none;}

  .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
  .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}

  svg{ stroke: currentColor; }
  svg *{ vector-effect: non-scaling-stroke; }

  select option{ color:#0f172a; background:#ffffff; }
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">

  {{-- TOP BAR --}}
  <header class="sticky top-0 z-50 border-b border-white/12 bg-[#13392f]/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white/95">Perpustakaan Digital</p>
          <p class="text-xs text-white/65">Koleksi literasi islami untuk jamaah</p>
        </div>

        <span class="hidden sm:inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-3 py-1 text-xs font-semibold text-white/85">
          {!! $ico['book'] !!} Koleksi Buku
        </span>
      </div>
    </div>
  </header>

  {{-- HERO --}}
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10" aria-hidden="true">
      <img src="{{ $heroBg }}" alt="Perpustakaan" class="h-full w-full object-cover opacity-30" referrerpolicy="no-referrer">
      <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/25 to-black/65"></div>
      <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
      <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 text-center sm:px-6 lg:px-8 lg:py-12">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/12 bg-white/8 shadow-lg shadow-black/20">
        {!! $ico['book'] !!}
      </div>

      <h1 class="text-3xl font-extrabold text-white sm:text-5xl">Perpustakaan Digital</h1>
      <p class="mx-auto mt-3 max-w-2xl text-sm text-white/80 sm:text-base">
        Koleksi buku Islam dan literasi keagamaan untuk meningkatkan ilmu dan wawasan.
      </p>

      {{-- HIGHLIGHT HERO --}}
      <div class="mt-6 flex flex-col items-center justify-center gap-2 sm:flex-row">
        <a href="{{ route('perpustakaan.ramah-anak') }}"
           class="group inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-extrabold text-[#13392f]
                  shadow-lg transition hover:-translate-y-0.5 hover:brightness-105"
           style="background: linear-gradient(135deg, var(--accent) 0%, rgba(231,177,75,0.75) 55%, rgba(255,255,255,0.10) 100%);">
          <span class="grid h-9 w-9 place-content-center rounded-xl bg-[#13392f]/10">
            {!! $ico['kid'] !!}
          </span>
          Jelajahi Ramah Anak
          <svg viewBox="0 0 24 24" class="h-4 w-4 opacity-90 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>
          </svg>
        </a>

        <span class="text-xs text-white/70">
          Video animasi • Dongeng PDF • Kata-kata islami untuk anak
        </span>
      </div>

      {{-- Quick Stats --}}
      <div class="mx-auto mt-6 max-w-4xl {{ $glass }} p-4 sm:p-5">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
          <div class="rounded-2xl border border-white/12 bg-white/6 p-3 text-left">
            <p class="text-[11px] text-white/60">Total Buku</p>
            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['books'], 0, ',', '.') }}</p>
          </div>
          <div class="rounded-2xl border border-white/12 bg-white/6 p-3 text-left">
            <p class="text-[11px] text-white/60">Kategori</p>
            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['categories'], 0, ',', '.') }}</p>
          </div>
          <div class="rounded-2xl border border-white/12 bg-white/6 p-3 text-left">
            <p class="text-[11px] text-white/60">Total Dibaca</p>
            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['views'], 0, ',', '.') }}</p>
          </div>
          <div class="rounded-2xl border border-white/12 bg-white/6 p-3 text-left">
            <p class="text-[11px] text-white/60">Total Diunduh</p>
            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['downloads'], 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CONTENT --}}
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- SEARCH + FILTER --}}
    <section class="mb-6 {{ $glass }} p-4 sm:p-6">
      <form method="GET" class="grid gap-3 md:grid-cols-12">
        {{-- search --}}
        <div class="md:col-span-6">
          <div class="relative">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/70">
              {!! $ico['search'] !!}
            </span>
            <input
              name="search"
              value="{{ $search }}"
              placeholder="Cari buku (judul, penulis, deskripsi)..."
              class="h-11 w-full rounded-2xl border border-white/14 bg-white pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]" />
          </div>
        </div>

        {{-- category --}}
        <div class="md:col-span-4">
          <div class="relative">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/70">
              {!! $ico['filter'] !!}
            </span>
            <select
              name="category"
              class="h-11 w-full appearance-none rounded-2xl border border-white/14 bg-white pl-10 pr-10 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]">
              <option value="">Semua Kategori</option>
              @foreach ($categoryList as $cat)
                <option value="{{ $cat }}" @selected((string) $selectedCategory === (string) $cat)>
                  {{ $cat }}
                </option>
              @endforeach
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white/70">
              {!! $ico['chev'] !!}
            </span>
          </div>
        </div>

        {{-- view buttons --}}
        <div class="md:col-span-2 flex items-center justify-end gap-2">
          <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
             class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border transition
             {{ $viewMode === 'grid' ? 'border-white/18 bg-white/10 text-white' : 'border-white/12 bg-white/6 text-white/80 hover:bg-white/10' }}"
             aria-label="Tampilan grid">
            {!! $ico['grid'] !!}
          </a>
          <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
             class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border transition
             {{ $viewMode === 'list' ? 'border-white/18 bg-white/10 text-white' : 'border-white/12 bg-white/6 text-white/80 hover:bg-white/10' }}"
             aria-label="Tampilan list">
            {!! $ico['list'] !!}
          </a>
        </div>

        <input type="hidden" name="view" value="{{ $viewMode }}">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
      </form>

      {{-- tabs --}}
      <div class="mt-4 flex justify-center">
        <div class="inline-grid grid-cols-2 overflow-hidden rounded-full border border-white/14 bg-white/6">
          <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}"
             class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold transition
             {{ $activeTab === 'all' ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
             style="{{ $activeTab === 'all' ? 'background: var(--accent);' : '' }}">
            {!! $ico['book'] !!} Semua
          </a>
          <a href="{{ request()->fullUrlWithQuery(['tab' => 'featured']) }}"
             class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold transition
             {{ $activeTab === 'featured' ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
             style="{{ $activeTab === 'featured' ? 'background: var(--accent);' : '' }}">
            {!! $ico['star'] !!} Pilihan
          </a>
        </div>
      </div>

      {{-- result info --}}
      <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-white/70">
          Menampilkan <span class="font-semibold text-white">{{ $booksCollection->count() }}</span> buku
          @if ($selectedCategory)
            pada kategori <span class="font-semibold text-white">{{ $selectedCategory }}</span>
          @endif
          @if ($search)
            dengan kata kunci "<span class="font-semibold text-white">{{ $search }}</span>"
          @endif
        </p>
        <span class="text-[11px] text-white/60">Tip: Klik “Detail” untuk halaman detail buku.</span>
      </div>
    </section>

    @php
      $filteredBooks = $booksCollection->filter(function ($book) use ($activeTab, $selectedCategory, $search) {
        if ($activeTab === 'featured' && empty($book->is_featured ?? $book->isFeatured)) return false;
        if ($selectedCategory && ($book->kategori ?? $book->category?->name ?? '') !== $selectedCategory) return false;

        if ($search) {
          $haystack = strtolower(
            ($book->judul ?? '') . ' ' .
            ($book->penulis ?? '') . ' ' .
            ($book->deskripsi ?? '') . ' ' .
            ($book->kategori ?? $book->category?->name ?? '')
          );
          if (! str_contains($haystack, strtolower($search))) return false;
        }
        return true;
      });
    @endphp

    {{-- EMPTY --}}
    @if ($filteredBooks->isEmpty())
      <div class="{{ $glass }} p-10 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
          {!! $ico['book'] !!}
        </div>
        <h3 class="text-lg font-extrabold text-white">Tidak ada buku ditemukan</h3>
        <p class="mt-1 text-sm text-white/75">Coba ubah kata kunci pencarian atau filter kategori.</p>
      </div>
    @else

      <div class="grid gap-6 lg:grid-cols-12">

        {{-- SIDEBAR --}}
        <aside class="lg:col-span-3">
          <div class="{{ $glass }} p-5">
            <div class="flex items-center gap-3">
              <div class="h-11 w-11 rounded-2xl grid place-items-center text-[#13392f]" style="background: var(--accent);">
                {!! $ico['kid'] !!}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-extrabold text-white">Ramah Anak</p>
                <p class="text-[11px] text-white/70">Aman & dikurasi admin</p>
              </div>
            </div>

            <div class="mt-4 space-y-2 text-xs text-white/75">
              <div class="flex items-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-2">
                <span class="grid h-7 w-7 place-items-center rounded-xl bg-white/10">🎬</span>
                <span>Video animasi islami</span>
              </div>
              <div class="flex items-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-2">
                <span class="grid h-7 w-7 place-items-center rounded-xl bg-white/10">📖</span>
                <span>Dongeng PDF</span>
              </div>
              <div class="flex items-center gap-2 rounded-2xl border border-white/12 bg-white/6 px-3 py-2">
                <span class="grid h-7 w-7 place-items-center rounded-xl bg-white/10">💬</span>
                <span>Kata-kata islami</span>
              </div>
            </div>

            <a href="{{ route('perpustakaan.ramah-anak') }}"
               class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-extrabold text-[#13392f]
                      hover:brightness-105 transition"
               style="background: var(--accent);">
              Jelajahi Ramah Anak
              <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>
              </svg>
            </a>

            <p class="mt-3 text-[11px] text-white/60">
              Cocok untuk anak TK–SD • Konten aman & mendidik.
            </p>
          </div>
        </aside>

        {{-- BOOK LIST --}}
        <div class="lg:col-span-9">
          <section class="{{ $viewMode === 'grid' ? 'grid gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'space-y-4' }}">
            @foreach ($filteredBooks as $book)
              @php
                // ===== Cover normalize (local vs http vs drive)
                $coverRaw = $book->cover ?? $book->thumbnail ?? $book->gambar ?? null;

                if ($coverRaw && ! str_starts_with($coverRaw, 'http')) {
                  $cover = Storage::url($coverRaw);
                } else {
                  $cover = $normalizeDriveThumb($coverRaw);
                }
                $cover = $cover ?: $fallbackCover;

                // ===== File normalize (for "Baca")
                $fileRaw = $book->file_ebook ?? null;
                if ($fileRaw && ! str_starts_with($fileRaw, 'http')) {
                  $fileUrl = Storage::url($fileRaw);
                } else {
                  $fileUrl = $normalizeDrivePdfPreview($fileRaw);
                }

                $categoryLabel = $book->kategori ?? optional($book->category)->name;
                $isAvailable = $book->stok_tersedia ?? $book->is_available ?? $book->isAvailable ?? true;

                $viewCount = $book->view_count ?? $book->views ?? 0;
                $downloadCount = $book->download_count ?? $book->downloads ?? 0;
                $isFeatured = ! empty($book->is_featured ?? $book->isFeatured);
              @endphp

              @if ($viewMode === 'grid')
                <article class="group overflow-hidden rounded-[24px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)] backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/10">
                  <div class="relative">
                    <img src="{{ $cover }}" alt="{{ $book->judul }}"
                      class="h-56 w-full object-cover"
                      loading="lazy" referrerpolicy="no-referrer"
                      onerror="this.onerror=null;this.src='{{ $fallbackCover }}';">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>

                    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                      @if ($categoryLabel)
                        <span class="inline-flex items-center rounded-full border border-white/14 bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-white/90">
                          {{ $categoryLabel }}
                        </span>
                      @endif
                      <span class="inline-flex items-center rounded-full border border-white/14 bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-white/90">
                        {{ $isAvailable ? 'Tersedia' : 'Tidak tersedia' }}
                      </span>
                    </div>

                    @if ($isFeatured)
                      <div class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-extrabold text-[#13392f]"
                           style="background: var(--accent);">
                        {!! $ico['star'] !!} PILIHAN
                      </div>
                    @endif
                  </div>

                  <div class="p-4">
                    <h3 class="text-base font-extrabold text-white line-clamp-2 group-hover:text-white/90">
                      {{ $book->judul }}
                    </h3>
                    @if ($book->penulis)
                      <p class="mt-1 text-xs text-white/70">oleh {{ $book->penulis }}</p>
                    @endif

                    <p class="mt-2 text-sm text-white/75 line-clamp-2">
                      {{ \Illuminate\Support\Str::limit(strip_tags($book->deskripsi), 140) }}
                    </p>

                    <div class="mt-3 flex items-center justify-between text-[11px] text-white/65">
                      <span class="inline-flex items-center gap-1">{!! $ico['eye'] !!} {{ number_format($viewCount, 0, ',', '.') }}</span>
                      <span class="inline-flex items-center gap-1">{!! $ico['download'] !!} {{ number_format($downloadCount, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-3 flex gap-2">
                      <a href="{{ route('perpustakaan.show', $book) }}"
                         class="inline-flex flex-1 items-center justify-center rounded-2xl px-3 py-2.5 text-xs font-extrabold text-[#13392f] hover:brightness-105"
                         style="background: var(--accent);">
                        Detail
                      </a>

                      @if ($fileUrl)
                        <a href="{{ $fileUrl }}" target="_blank" rel="noreferrer"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/14 bg-white/6 px-3 py-2.5 text-xs font-extrabold text-white/85 hover:bg-white/10">
                          Baca
                        </a>
                      @endif
                    </div>
                  </div>
                </article>
              @else
                <article class="rounded-[24px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)] backdrop-blur transition hover:bg-white/10">
                  <div class="flex gap-4 p-4">
                    <img src="{{ $cover }}" alt="{{ $book->judul }}"
                      class="h-28 w-20 rounded-2xl object-cover"
                      loading="lazy" referrerpolicy="no-referrer"
                      onerror="this.onerror=null;this.src='{{ $fallbackCover }}';">

                    <div class="min-w-0 flex-1">
                      <div class="flex flex-wrap items-center gap-2">
                        @if ($categoryLabel)
                          <span class="inline-flex items-center rounded-full border border-white/14 bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-white/90">
                            {{ $categoryLabel }}
                          </span>
                        @endif
                        @if ($isFeatured)
                          <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-extrabold text-[#13392f]"
                                style="background: var(--accent);">
                            {!! $ico['star'] !!} Pilihan
                          </span>
                        @endif
                        <span class="inline-flex items-center rounded-full border border-white/14 bg-white/10 px-2.5 py-1 text-[10px] font-semibold text-white/90">
                          {{ $isAvailable ? 'Tersedia' : 'Tidak tersedia' }}
                        </span>
                      </div>

                      <h3 class="mt-2 text-lg font-extrabold text-white line-clamp-2">
                        {{ $book->judul }}
                      </h3>

                      @if ($book->penulis)
                        <p class="text-xs text-white/70">oleh {{ $book->penulis }}</p>
                      @endif

                      <p class="mt-2 text-sm text-white/75 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit(strip_tags($book->deskripsi), 160) }}
                      </p>

                      <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-white/65">
                        <span class="inline-flex items-center gap-1">{!! $ico['eye'] !!} {{ number_format($viewCount, 0, ',', '.') }} dilihat</span>
                        <span class="inline-flex items-center gap-1">{!! $ico['download'] !!} {{ number_format($downloadCount, 0, ',', '.') }} diunduh</span>
                      </div>

                      <div class="mt-3 flex gap-2">
                        <a href="{{ route('perpustakaan.show', $book) }}"
                           class="inline-flex items-center justify-center rounded-2xl px-3 py-2.5 text-xs font-extrabold text-[#13392f] hover:brightness-105"
                           style="background: var(--accent);">
                          Detail
                        </a>

                        @if ($fileUrl)
                          <a href="{{ $fileUrl }}" target="_blank" rel="noreferrer"
                             class="inline-flex items-center justify-center rounded-2xl border border-white/14 bg-white/6 px-3 py-2.5 text-xs font-extrabold text-white/85 hover:bg-white/10">
                            Baca
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                </article>
              @endif
            @endforeach
          </section>

          {{-- PAGINATION --}}
          @if ($perpustakaans instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="mt-8">
              {{ $perpustakaans->appends(request()->query())->links() }}
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>
</div>
</x-front-layout>
