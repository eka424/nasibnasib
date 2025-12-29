<x-front-layout>
    @php
        $articlesCollection = $artikels->getCollection();
        $featuredArticles = $articlesCollection->take(2);

        $categories = ['Semua Kategori', 'Ibadah', 'Fiqih', 'Kehidupan Muslim', 'Pendidikan'];
        $sortOptions = [
            'latest' => 'Terbaru',
            'popular' => 'Terpopuler',
            'most-liked' => 'Paling Disukai',
        ];

        $q = request('q', '');
        $category = request('category', 'Semua Kategori');
        $sort = request('sort', 'latest');

        $resultCount = method_exists($artikels, 'total') ? $artikels->total() : $artikels->count();
        $glass = 'rounded-3xl border border-white/10 bg-white/5 backdrop-blur shadow-[0_18px_60px_-40px_rgba(0,0,0,0.6)]';
    @endphp

    <div id="top" class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">
        <style>
            .lc-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
            .lc-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden}
            .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
            .scrollbar-none::-webkit-scrollbar{display:none;}
        </style>

        {{-- HEADER (glass) --}}
        <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/55 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ url()->current() }}" class="flex items-center gap-3">
                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-lg shadow-emerald-500/30">
                            🕌
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Masjid Al’Ala</p>
                            <p class="text-xs text-emerald-100/70">Artikel & Kajian</p>
                        </div>
                    </a>

                    <div class="hidden items-center gap-2 sm:flex">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                            {{ number_format($resultCount) }} hasil
                        </span>

                        <button type="button" data-open-filter
                            class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                            ⚙️ Filter
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- HERO --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 -z-10">
                <img src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80"
                     alt="Hero" class="h-full w-full object-cover opacity-35" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-emerald-950/45 to-slate-900/90"></div>
                <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full bg-emerald-400/12 blur-3xl"></div>
                <div class="absolute -right-24 top-10 h-80 w-80 rounded-full bg-sky-400/10 blur-3xl"></div>
            </div>

            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold text-emerald-50 backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> Ruang Digital Jamaah
                        </div>

                        <h1 class="mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
                            Artikel Islami yang ringkas, jelas, dan nyaman dibaca di HP
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm text-emerald-100/85 sm:text-base">
                            Cari cepat, filter sederhana, tampilan glass modern. Fokus ke isi bacaan tanpa bikin mata capek.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a href="#artikel"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-400 via-lime-300 to-amber-300 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5 hover:brightness-110">
                                📚 Mulai Baca
                            </a>
                            <button type="button" data-open-filter
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/15">
                                🔎 Cari & Filter
                            </button>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2 text-xs">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-white/80">✅ Ringkas & terarah</span>
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-white/80">📱 User-friendly di mobile</span>
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-white/80">⚡ Pencarian cepat</span>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-emerald-100">Highlight hari ini</p>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/80">Live</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @foreach ($featuredArticles as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $tag = $a->category->name ?? $a->tag ?? 'Artikel';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                    @endphp
                                    <a href="{{ route('artikel.show', $a) }}" class="group flex gap-3 rounded-2xl border border-white/10 bg-white/5 p-3 transition hover:bg-white/10">
                                        <img src="{{ $cover }}" alt="{{ $a->title }}"
                                             class="h-16 w-20 flex-none rounded-xl object-cover opacity-90" referrerpolicy="no-referrer">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-white/60">
                                                <span class="rounded-full bg-emerald-500/15 px-2 py-0.5 font-semibold text-emerald-100">{{ $tag }}</span>
                                                <span>• {{ $minutes }} menit</span>
                                                <span>• {{ $date }}</span>
                                            </div>
                                            <p class="mt-1 lc-2 text-sm font-semibold text-white group-hover:text-emerald-200">{{ $a->title }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <a href="#artikel" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">Lihat Semua</a>
                                <button type="button" data-open-filter
                                    class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="artikel" class="h-6"></div>
            </div>
        </section>

        {{-- MOBILE STICKY SEARCH --}}
        <div class="lg:hidden sticky top-[72px] z-40 mx-auto max-w-7xl px-4 pt-3 sm:px-6">
            <div class="{{ $glass }} p-3">
                <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/40">🔎</span>
                        <input name="q" value="{{ $q }}" placeholder="Cari artikel..."
                               class="h-11 w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                    </div>
                    <button type="button" data-open-filter
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        ☰ Filter
                    </button>
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                </form>

                <div class="mt-3 flex gap-2 overflow-x-auto scrollbar-none">
                    @foreach ($categories as $c)
                        <a href="{{ url()->current() }}?q={{ urlencode($q) }}&category={{ urlencode($c) }}&sort={{ urlencode($sort) }}"
                           class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold transition ring-1 {{ $c === $category ? 'bg-emerald-500/15 text-emerald-100 ring-emerald-300/30' : 'bg-white/5 text-white/70 ring-white/10 hover:bg-white/10' }}">
                            {{ $c }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CONTENT --}}
        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-200/80">Artikel</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Jelajahi bacaan terbaru</h2>
                    <p class="mt-1 text-sm text-emerald-100/80">Semua panel pakai glass style.</p>
                </div>

                {{-- DESKTOP CONTROLS --}}
                <form method="GET" action="{{ url()->current() }}" class="hidden lg:flex items-center gap-2">
                    <div class="relative w-[360px]">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/40">🔎</span>
                        <input name="q" value="{{ $q }}" placeholder="Cari artikel..."
                               class="h-11 w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                    </div>
                    <select name="category" class="h-11 rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                        @foreach ($categories as $c)
                            <option value="{{ $c }}" @selected($c === $category) class="text-slate-900">{{ $c }}</option>
                        @endforeach
                    </select>
                    <select name="sort" class="h-11 rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                        @foreach ($sortOptions as $k => $label)
                            <option value="{{ $k }}" @selected($k === $sort) class="text-slate-900">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-xl bg-emerald-600 px-4 text-sm font-semibold text-white transition hover:bg-emerald-700">Terapkan</button>
                    <a href="{{ url()->current() }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 text-sm font-semibold text-white transition hover:bg-white/10">Reset</a>
                </form>
            </div>

            <div class="grid gap-8 lg:grid-cols-12">
                {{-- MAIN --}}
                <section class="space-y-8 lg:col-span-8">
                    {{-- FEATURED --}}
                    @if ($featuredArticles->isNotEmpty())
                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-extrabold text-white">Artikel Pilihan</h3>
                                <span class="text-xs font-semibold text-emerald-200/90">Highlight Jamaah</span>
                            </div>

                            {{-- Mobile carousel --}}
                            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-none snap-x snap-mandatory lg:hidden">
                                @foreach ($featuredArticles as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                    @endphp
                                    <a href="{{ route('artikel.show', $a) }}" class="group relative w-[86%] flex-none snap-start overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-md">
                                        <img src="{{ $cover }}" alt="{{ $a->title }}" class="h-52 w-full object-cover opacity-80 transition duration-700 group-hover:scale-[1.02] group-hover:opacity-95" referrerpolicy="no-referrer">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
                                        <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/15 backdrop-blur">
                                            <span class="text-emerald-200">Pilihan</span>
                                            <span class="text-white/70">• {{ $minutes }} menit</span>
                                        </div>
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <p class="text-xs text-white/70">{{ $date }}</p>
                                            <h4 class="mt-1 text-lg font-extrabold leading-snug lc-2 text-white">{{ $a->title }}</h4>
                                            <p class="mt-1 text-sm text-emerald-50/90 lc-2">{{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 120) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            {{-- Desktop grid --}}
                            <div class="hidden gap-6 lg:grid lg:grid-cols-2">
                                @foreach ($featuredArticles as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                        $tag = $a->category->name ?? $a->tag ?? 'Artikel';
                                    @endphp
                                    <article class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-md transition hover:-translate-y-1 hover:bg-white/10">
                                        <a href="{{ route('artikel.show', $a) }}" class="block">
                                            <div class="relative h-56">
                                                <img src="{{ $cover }}" alt="{{ $a->title }}" class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-[1.03]" referrerpolicy="no-referrer">
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent to-transparent"></div>
                                                <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/15 backdrop-blur">
                                                    Pilihan • {{ $minutes }} menit
                                                </div>
                                            </div>
                                            <div class="space-y-2 p-5">
                                                <p class="text-xs text-white/60">{{ $date }} • {{ $tag }}</p>
                                                <h4 class="text-lg font-extrabold text-white lc-2 group-hover:text-emerald-200">{{ $a->title }}</h4>
                                                <p class="text-sm text-white/70 lc-3">{{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 160) }}</p>
                                                <div class="pt-2 text-sm font-semibold text-emerald-200">Baca selengkapnya →</div>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ALL ARTICLES --}}
                    <div class="{{ $glass }} p-5 sm:p-6">
                        <div class="mb-5 flex items-center justify-between gap-3">
                            <h3 class="text-xl font-extrabold text-white">Semua Artikel <span class="text-sm font-semibold text-white/60">({{ number_format($resultCount) }})</span></h3>
                            <span class="hidden sm:inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> Glass UI
                            </span>
                        </div>

                        @if ($artikels->count() === 0)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 text-center">
                                <h4 class="text-lg font-semibold text-white">Tidak ada artikel ditemukan</h4>
                                <p class="mt-1 text-sm text-white/70">Coba ubah kata kunci atau kategori.</p>
                            </div>
                        @else
                            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($artikels as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $tag = $a->category->name ?? $a->tag ?? 'Artikel';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                        $views = $a->views ?? ($a->id * 17);
                                        $likes = $a->likes ?? max(5, $a->id * 3);
                                    @endphp
                                    <article class="group overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-md transition hover:-translate-y-1 hover:bg-white/10">
                                        <a href="{{ route('artikel.show', $a) }}" class="block">
                                            <div class="relative h-44 overflow-hidden">
                                                <img loading="lazy" src="{{ $cover }}" alt="{{ $a->title }}" class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-[1.05]" referrerpolicy="no-referrer">
                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/65 via-transparent to-transparent"></div>
                                                <div class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/15 backdrop-blur">{{ $date }}</div>
                                            </div>
                                            <div class="space-y-2 p-5">
                                                <div class="flex flex-wrap items-center gap-2 text-[11px] text-white/70">
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2 py-0.5 font-semibold text-emerald-100 ring-1 ring-emerald-300/20">⏱ {{ $minutes }} menit</span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/5 px-2 py-0.5 font-semibold text-white/70 ring-1 ring-white/10">👁 {{ number_format($views) }}</span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/5 px-2 py-0.5 font-semibold text-white/70 ring-1 ring-white/10">❤️ {{ number_format($likes) }}</span>
                                                </div>
                                                <h4 class="text-base font-extrabold text-white lc-2 group-hover:text-emerald-200">{{ $a->title }}</h4>
                                                <p class="text-sm text-white/70 lc-3">{{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 160) }}</p>
                                                <div class="pt-2 flex items-center justify-between text-xs text-white/60">
                                                    <span class="font-semibold text-emerald-200">{{ $tag }}</span>
                                                    <span class="font-semibold text-emerald-200">Baca →</span>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                            <div class="mt-8">
                                {{ $artikels->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </section>

                {{-- SIDEBAR --}}
                <aside class="space-y-6 lg:col-span-4">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-extrabold text-emerald-100">Kegiatan Mendatang</p>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">Kegiatan</span>
                            </div>
                            <div class="mt-4 space-y-3 text-sm">
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 transition hover:bg-white/10">
                                    <p class="font-semibold text-white">Kajian Tafsir Pekanan</p>
                                    <p class="text-white/70">Jumat, 19.30 WIB · Ruang Utama</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 transition hover:bg-white/10">
                                    <p class="font-semibold text-white">Pelatihan Tahsin</p>
                                    <p class="text-white/70">Ahad Pagi · Kelas Tahfidz</p>
                                </div>
                            </div>
                            <a href="{{ route('kegiatan.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Lihat Semua Kegiatan</a>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-emerald-900/35 via-slate-900/65 to-slate-950 p-6 shadow-lg shadow-emerald-500/10 backdrop-blur">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-extrabold text-emerald-100">Donasi & Infaq</p>
                                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-50 ring-1 ring-white/15">Donasi</span>
                            </div>
                            <p class="mt-3 text-base font-semibold text-white">Bantu program sosial masjid dan infaq rutin jamaah.</p>
                            <p class="mt-2 text-sm text-emerald-100/85">Aman, cepat, dan transparan.</p>
                            <a href="{{ route('donasi.index') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-emerald-400 via-lime-300 to-amber-300 px-4 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5 hover:brightness-110">Donasi Sekarang</a>
                        </div>

                        <div class="{{ $glass }} p-5 sm:p-6">
                            <p class="text-sm font-extrabold text-emerald-100">Tips</p>
                            <p class="mt-2 text-sm text-white/70">Di HP, tekan tombol <span class="font-semibold text-white">Filter</span> untuk buka bottom-sheet.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        {{-- MOBILE BOTTOM-SHEET FILTER --}}
        <div id="filterSheet" class="fixed inset-0 z-[80] lg:hidden pointer-events-none">
            <div id="filterOverlay" class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-300"></div>
            <div id="filterPanel" class="absolute inset-x-0 bottom-0 translate-y-full transition-transform duration-300">
                <div class="mx-auto max-w-2xl rounded-t-3xl border border-white/10 bg-slate-950/90 p-5 shadow-2xl backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-200/80">Filter</p>
                            <p class="mt-1 text-lg font-extrabold text-white">Atur Pencarian</p>
                        </div>
                        <button type="button" data-close-filter class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white transition hover:bg-white/10">✕</button>
                    </div>

                    <form method="GET" action="{{ url()->current() }}" class="mt-4 space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-white/70">Kata kunci</label>
                            <input name="q" value="{{ $q }}" placeholder="Contoh: wudhu, shalat..."
                                   class="mt-1 h-11 w-full rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="text-xs font-semibold text-white/70">Kategori</label>
                                <select name="category" class="mt-1 h-11 w-full rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                                    @foreach ($categories as $c)
                                        <option value="{{ $c }}" @selected($c === $category) class="text-slate-900">{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-white/70">Urutkan</label>
                                <select name="sort" class="mt-1 h-11 w-full rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60">
                                    @foreach ($sortOptions as $k => $label)
                                        <option value="{{ $k }}" @selected($k === $sort) class="text-slate-900">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <a href="{{ url()->current() }}" class="inline-flex flex-1 items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Reset</a>
                            <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Terapkan</button>
                        </div>
                        <p class="mt-2 text-xs text-white/50">Bottom-sheet ini dibuat supaya enak dipakai satu tangan di mobile.</p>
                    </form>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <footer class="border-t border-white/10 bg-slate-950/50 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-slate-300 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="font-semibold text-white">Masjid Al’Ala</p>
                        <p class="text-white/60">Islami • Modern • Responsif</p>
                    </div>
                    <p class="text-white/60">© {{ now()->year }} Masjid Al’Ala</p>
                </div>
            </div>
        </footer>
    </div>

    {{-- JS: open/close bottom-sheet filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sheet = document.getElementById('filterSheet');
            const overlay = document.getElementById('filterOverlay');
            const panel = document.getElementById('filterPanel');
            const openBtns = document.querySelectorAll('[data-open-filter]');
            const closeBtns = document.querySelectorAll('[data-close-filter]');

            const open = () => {
                if (!sheet) return;
                sheet.classList.remove('pointer-events-none');
                overlay.classList.remove('opacity-0'); overlay.classList.add('opacity-100');
                panel.classList.remove('translate-y-full');
                document.body.style.overflow = 'hidden';
            };
            const close = () => {
                if (!sheet) return;
                overlay.classList.add('opacity-0'); overlay.classList.remove('opacity-100');
                panel.classList.add('translate-y-full');
                document.body.style.overflow = '';
                setTimeout(() => sheet.classList.add('pointer-events-none'), 250);
            };

            openBtns.forEach(btn => btn.addEventListener('click', open));
            closeBtns.forEach(btn => btn.addEventListener('click', close));
            overlay?.addEventListener('click', close);
            document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
        });
    </script>
</x-front-layout>
