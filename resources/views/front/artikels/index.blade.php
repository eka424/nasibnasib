<x-front-layout>
    @php
        $articlesCollection = $artikels->getCollection();
        $featuredArticles = $articlesCollection->take(2);

        $q = request('q', '');
        $resultCount = method_exists($artikels, 'total') ? $artikels->total() : $artikels->count();

        // ====== THEME (match front-layout) ======
        $bg      = '#13392f';
        $accent  = '#E7B14B';
        $darkBtn = '#0F4A3A';

        // ====== COMPONENT TOKENS ======
        $glass = 'rounded-[28px] border border-white/14 bg-white/7 backdrop-blur-[14px] shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)]';
        $soft  = 'rounded-2xl border border-white/14 bg-white/6';
        $btnPrimary = 'rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-[#13392f] transition hover:brightness-95 active:scale-[0.99]';
        $btnAccent  = 'rounded-2xl bg-[var(--accent)] px-5 py-3 text-sm font-semibold text-[#13392f] transition hover:brightness-105 active:scale-[0.99]';
        $btnGhost   = 'rounded-2xl border border-white/14 bg-white/6 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 active:scale-[0.99]';
        $inputBase  = 'h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white placeholder:text-white/55 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/70';

        // ====== ICONS (simple outline, "flaticon-like" common pack look) ======
        $svg = [
            'search' => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>',
            'spark'  => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l1.2 4.2L17.4 7.4l-4.2 1.2L12 12l-1.2-3.4L6.6 7.4l4.2-1.2L12 2z"/><path d="M19 13l.6 2.1L22 16l-2.4.9L19 19l-.6-2.1L16 16l2.4-.9L19 13z"/></svg>',
            'tag'    => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13l-7 7-11-11V2h7l11 11z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>',
            'clock'  => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
            'arrow'  => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h12"/><path d="m13 6 6 6-6 6"/></svg>',
            'note'   => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5a2 2 0 0 1 2-2h10l4 4v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M14 3v4h4"/><path d="M8 13h8"/><path d="M8 17h6"/><path d="M8 9h4"/></svg>',
            'calendar'=> '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3M16 3v3"/><rect x="4" y="6" width="16" height="15" rx="2"/><path d="M4 10h16"/></svg>',
            'heart'  => '<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
        ];

        $lc2 = 'display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden';
        $lc3 = 'display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden';

        $scrollbarNone = '-ms-overflow-style:none; scrollbar-width:none;';
    @endphp

    <div class="min-h-screen text-white" style="background: {{ $bg }}; --accent: {{ $accent }};">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap');
            body{font-family:Inter,ui-sans-serif,system-ui,-apple-system}
            h1,h2,h3,.heading{font-family:Poppins,ui-sans-serif,system-ui,-apple-system}
            .lc-2{ {!! $lc2 !!} }
            .lc-3{ {!! $lc3 !!} }
            .scrollbar-none{ {!! $scrollbarNone !!} }
            .scrollbar-none::-webkit-scrollbar{display:none;}
            .btn:active{transform:scale(.99)}
        </style>

        {{-- Top bar --}}
        <header class="sticky top-0 z-50 border-b border-white/10 bg-[#13392f]/90 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white">Artikel</p>
                        <p class="text-xs text-white/65">Bacaan Islami untuk jamaah</p>
                    </div>

                    <div class="hidden items-center gap-2 sm:flex">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/6 px-3 py-1 text-xs font-semibold text-white/90">
                            {!! $svg['note'] !!} {{ number_format($resultCount) }} hasil
                        </span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Hero (clean, no heavy gradients) --}}
        <section class="relative">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
                <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-7">
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/14 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90">
                            {!! $svg['spark'] !!} Ruang Digital Jamaah
                        </p>

                        <h1 class="heading mt-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
                            Artikel Islami yang ringkas, jelas, dan nyaman dibaca
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm text-white/75 sm:text-base">
                            Cari bacaan terbaru, highlight pilihan, dan temukan topik dengan cepat.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a href="#artikel" class="btn {{ $btnAccent }} inline-flex items-center justify-center gap-2">
                                Mulai Baca {!! $svg['arrow'] !!}
                            </a>
                            <a href="{{ route('kegiatan.index') }}" class="btn {{ $btnGhost }} inline-flex items-center justify-center gap-2">
                                {!! $svg['calendar'] !!} Kegiatan
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-white">Highlight</p>
                                <span class="rounded-full border border-white/14 bg-white/6 px-3 py-1 text-xs font-semibold text-white/80">Hari ini</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @foreach ($featuredArticles as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $tag = $a->category->name ?? $a->tag ?? 'Artikel';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                    @endphp

                                    <a href="{{ route('artikel.show', $a) }}"
                                       class="group flex gap-3 rounded-2xl border border-white/14 bg-white/6 p-3 transition hover:bg-white/10">
                                        <img src="{{ $cover }}" alt="{{ $a->title }}"
                                             class="h-16 w-20 flex-none rounded-xl object-cover opacity-90 ring-1 ring-white/10"
                                             referrerpolicy="no-referrer">

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-white/65">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-[var(--accent)]/15 px-2 py-0.5 font-semibold text-[var(--accent)] ring-1 ring-[var(--accent)]/15">
                                                    {!! $svg['tag'] !!} {{ $tag }}
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    {!! $svg['clock'] !!} {{ $minutes }} menit
                                                </span>
                                                <span>{{ $date }}</span>
                                            </div>
                                            <p class="mt-1 lc-2 text-sm font-semibold text-white group-hover:text-[var(--accent)]">
                                                {{ $a->title }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <a href="#artikel" class="btn {{ $btnPrimary }} block text-center">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="artikel" class="h-6"></div>
            </div>
        </section>

        {{-- Search --}}
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-4">
            <div class="{{ $glass }} p-4">
                <form method="GET" action="{{ url()->current() }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-white/60">
                            {!! $svg['search'] !!}
                        </span>
                        <input name="q" value="{{ $q }}" placeholder="Cari artikel..." class="{{ $inputBase }} pl-10">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn {{ $btnAccent }} inline-flex items-center justify-center gap-2">
                            {!! $svg['search'] !!} Cari
                        </button>
                        <a href="{{ url()->current() }}" class="btn {{ $btnGhost }}">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Content --}}
        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Artikel</p>
                <h2 class="heading mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">
                    Jelajahi bacaan terbaru
                </h2>
                <p class="mt-1 text-sm text-white/75">Tampilan minimalis, rapi, dan nyaman dibaca.</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-12">
                {{-- Main --}}
                <section class="space-y-8 lg:col-span-8">

                    {{-- Featured --}}
                    @if ($featuredArticles->isNotEmpty())
                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="heading text-lg font-extrabold text-white">Pilihan</h3>
                                <span class="rounded-full border border-white/14 bg-white/6 px-3 py-1 text-xs font-semibold text-white/75">Highlight</span>
                            </div>

                            {{-- Mobile carousel --}}
                            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-none snap-x snap-mandatory lg:hidden">
                                @foreach ($featuredArticles as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                    @endphp
                                    <a href="{{ route('artikel.show', $a) }}"
                                       class="group relative w-[86%] flex-none snap-start overflow-hidden rounded-[24px] border border-white/14 bg-white/6 shadow-md">
                                        <img src="{{ $cover }}" alt="{{ $a->title }}"
                                             class="h-52 w-full object-cover opacity-90"
                                             referrerpolicy="no-referrer">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                                        <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/15 backdrop-blur">
                                            {!! $svg['clock'] !!} {{ $minutes }} menit
                                        </div>

                                        <div class="absolute bottom-4 left-4 right-4">
                                            <p class="text-xs text-white/75">{{ $date }}</p>
                                            <h4 class="heading mt-1 text-lg font-extrabold leading-snug lc-2 text-white">
                                                {{ $a->title }}
                                            </h4>
                                            <p class="mt-1 text-sm text-white/85 lc-2">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 120) }}
                                            </p>
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
                                    <article class="group overflow-hidden rounded-[24px] border border-white/14 bg-white/6 shadow-md transition hover:-translate-y-1 hover:bg-white/10">
                                        <a href="{{ route('artikel.show', $a) }}" class="flex h-full flex-col">
                                            <div class="relative h-56">
                                                <img src="{{ $cover }}" alt="{{ $a->title }}"
                                                     class="h-full w-full object-cover opacity-90"
                                                     referrerpolicy="no-referrer">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent"></div>
                                                <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/15 backdrop-blur">
                                                    {!! $svg['tag'] !!} <span>{{ $tag }}</span>
                                                    <span class="text-white/60">•</span>
                                                    {!! $svg['clock'] !!} <span>{{ $minutes }} menit</span>
                                                </div>
                                            </div>

                                            <div class="flex h-full flex-col space-y-2 p-5">
                                                <p class="text-xs text-white/65">{{ $date }}</p>
                                                <h4 class="heading text-lg font-extrabold text-white lc-2 group-hover:text-[var(--accent)]">
                                                    {{ $a->title }}
                                                </h4>
                                                <p class="text-sm text-white/75 lc-3">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 160) }}
                                                </p>
                                                <div class="mt-auto pt-2 inline-flex items-center gap-2 text-sm font-semibold text-[var(--accent)]">
                                                    Baca selengkapnya {!! $svg['arrow'] !!}
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- All --}}
                    <div class="{{ $glass }} p-5 sm:p-6">
                        <div class="mb-5 flex items-center justify-between gap-3">
                            <h3 class="heading text-xl font-extrabold text-white">
                                Semua Artikel
                                <span class="text-sm font-semibold text-white/65">({{ number_format($resultCount) }})</span>
                            </h3>
                        </div>

                        @if ($artikels->count() === 0)
                            <div class="rounded-[24px] border border-white/14 bg-white/6 p-8 text-center">
                                <h4 class="heading text-lg font-extrabold text-white">Tidak ada artikel ditemukan</h4>
                                <p class="mt-1 text-sm text-white/75">Coba ubah kata kunci pencarian.</p>
                            </div>
                        @else
                            <div class="grid items-stretch gap-5 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($artikels as $a)
                                    @php
                                        $cover = $a->thumbnail ?? $a->cover ?? 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
                                        $tag = $a->category->name ?? $a->tag ?? 'Artikel';
                                        $minutes = max(1, ceil(str_word_count(strip_tags($a->content ?? '')) / 200));
                                        $date = optional($a->created_at)->translatedFormat('d M Y') ?? '';
                                    @endphp

                                    <article class="group flex h-full overflow-hidden rounded-[24px] border border-white/14 bg-white/6 shadow-md transition hover:-translate-y-1 hover:bg-white/10">
                                        <a href="{{ route('artikel.show', $a) }}" class="flex h-full w-full flex-col">
                                            <div class="relative h-44 overflow-hidden">
                                                <img loading="lazy" src="{{ $cover }}" alt="{{ $a->title }}"
                                                     class="h-full w-full object-cover opacity-90"
                                                     referrerpolicy="no-referrer">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>

                                                <div class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/15 backdrop-blur">
                                                    {!! $svg['calendar'] !!} {{ $date }}
                                                </div>
                                            </div>

                                            <div class="flex h-full flex-col space-y-2 p-5">
                                                <div class="flex flex-wrap items-center gap-2 text-[11px] text-white/75">
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-[var(--accent)]/15 px-2 py-0.5 font-semibold text-[var(--accent)] ring-1 ring-[var(--accent)]/15">
                                                        {!! $svg['clock'] !!} {{ $minutes }} menit
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2 py-0.5 font-semibold text-white/85 ring-1 ring-white/15">
                                                        {!! $svg['tag'] !!} {{ $tag }}
                                                    </span>
                                                </div>

                                                <h4 class="heading text-base font-extrabold text-white lc-2 group-hover:text-[var(--accent)]">
                                                    {{ $a->title }}
                                                </h4>

                                                <p class="text-sm text-white/75 lc-3">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($a->content ?? ''), 160) }}
                                                </p>

                                                <div class="mt-auto pt-2 flex items-center justify-between text-xs text-white/65">
                                                    <span class="font-semibold text-white/80">Selengkapnya</span>
                                                    <span class="inline-flex items-center gap-1 font-semibold text-[var(--accent)]">
                                                        Buka {!! $svg['arrow'] !!}
                                                    </span>
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

                {{-- Sidebar --}}
                <aside class="space-y-6 lg:col-span-4">
                    <div class="lg:sticky lg:top-24 space-y-6">

                        <div class="{{ $glass }} p-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <p class="heading text-sm font-extrabold text-white">Kegiatan Mendatang</p>
                                <span class="rounded-full border border-white/14 bg-white/6 px-3 py-1 text-xs font-semibold text-white/75">Info</span>
                            </div>

                            <div class="mt-4 space-y-3 text-sm">
                                <div class="rounded-2xl border border-white/14 bg-white/6 px-4 py-3 transition hover:bg-white/10">
                                    <p class="font-semibold text-white">Kajian Tafsir Pekanan</p>
                                    <p class="text-white/70">Jumat, 19.30 WIB · Ruang Utama</p>
                                </div>
                                <div class="rounded-2xl border border-white/14 bg-white/6 px-4 py-3 transition hover:bg-white/10">
                                    <p class="font-semibold text-white">Pelatihan Tahsin</p>
                                    <p class="text-white/70">Ahad Pagi · Kelas Tahfidz</p>
                                </div>
                            </div>

                            <a href="{{ route('kegiatan.index') }}" class="btn mt-4 inline-flex w-full items-center justify-center gap-2 {{ $btnGhost }}">
                                {!! $svg['calendar'] !!} Lihat Semua Kegiatan
                            </a>
                        </div>

                        <div class="rounded-[28px] border border-white/14 bg-white/6 p-6 shadow-lg shadow-black/10 backdrop-blur">
                            <p class="heading text-sm font-extrabold text-white">Donasi & Infaq</p>
                            <p class="mt-3 text-base font-semibold text-white">Dukung program sosial masjid dan infaq rutin.</p>
                            <p class="mt-2 text-sm text-white/75">Cepat, aman, dan transparan.</p>
                            <a href="{{ route('donasi.index') }}" class="btn mt-4 inline-flex w-full items-center justify-center gap-2 {{ $btnAccent }}">
                                {!! $svg['heart'] !!} Donasi Sekarang
                            </a>
                        </div>

                        <div class="{{ $glass }} p-5 sm:p-6">
                            <p class="heading text-sm font-extrabold text-white">Catatan</p>
                            <p class="mt-2 text-sm text-white/75">Gunakan pencarian untuk menemukan artikel.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-white/10 bg-[#13392f]/85 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-white/75 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <p class="font-semibold text-white">Masjid Agung Al-A’la</p>
                        <p class="text-white/60">Portal Jamaah</p>
                    </div>
                    <p class="text-white/60">© {{ now()->year }} Masjid Agung Al-A’la</p>
                </div>
            </div>
        </footer>
    </div>
</x-front-layout>
