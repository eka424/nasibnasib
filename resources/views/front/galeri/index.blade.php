<x-front-layout>
@php
    // ========= THEME (NYATU DENGAN FRONT) =========
    $bg     = '#13392f';
    $accent = '#E7B14B';
    $glass  = 'rounded-[26px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

    $heroBg     = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=80';
    $fallbackImg = 'https://images.unsplash.com/photo-1496302662116-35cc4f36df92?auto=format&fit=crop&w=1000&q=80';

    // ========= FILTER HIERARCHY =========
    // Tab utama (hapus "semua" sesuai request)
    $departments = [
        'idarah' => [
            'label' => 'Idarah',
            'sections' => [
                'SEKSI DOKUMENTASI, PERPUSTAKAAN DAN PENERBITAN',
                'SEKSI HUMAS, INFORMASI DAN KOMUNIKASI',
                'SEKSI PERIBADATAN',
                'Lainnya',
            ],
        ],
        'imarah' => [
            'label' => 'Imarah',
            'sections' => [
                'SEKSI PENDIDIKAN, PELATIHAN DAN KADERISASI',
                'SEKSI PEMBERDAYAAN PEREMPUAN DAN SOSIAL',
                'SEKSI KESEHATAN',
                'SEKSI PEREKONOMIAN',
                'Lainnya',
            ],
        ],
        'riayah' => [
            'label' => 'Riayah',
            'sections' => [
                'SEKSI PEMBANGUNAN, PEMELIHARAAN DAN PERAWATAN',
                'SEKSI KEAMANAN, KETERTIBAN DAN KEBERSIHAN',
                'Lainnya',
            ],
        ],
    ];

    // ========= NORMALISASI DATA =========
    // Asumsi field yang mungkin ada:
    // - $item->kategori: bisa "idarah", "imarah", "riayah"
    // - $item->seksi / $item->sub_kategori / $item->subkategori: nama seksi
    // Kalau belum ada, akan fallback "Lainnya"
    $galleryItems = $galeris->map(function ($item) use ($departments) {
        $url  = $item->url_file;
        $type = $item->tipe ?? 'image';

        // Normalisasi URL file (kalau path storage lokal)
        if ($url && !str_starts_with($url, 'http')) {
            // NOTE: pakai fully qualified name (tanpa "use") agar Blade tidak error
            $url = \Illuminate\Support\Facades\Storage::url($url);
        }

        // Dept
        $deptRaw = strtolower(trim((string)($item->kategori ?? '')));
        $dept = in_array($deptRaw, ['idarah','imarah','riayah'], true) ? $deptRaw : 'idarah';

        // Section
        $sectionRaw = (string)($item->seksi ?? $item->sub_kategori ?? $item->subkategori ?? $item->kategori_detail ?? '');
        $sectionRaw = trim($sectionRaw);

        $sectionsAllowed = $departments[$dept]['sections'] ?? ['Lainnya'];
        $section = in_array($sectionRaw, $sectionsAllowed, true) ? $sectionRaw : 'Lainnya';

        return [
            'type' => $type, // image|video
            'dept' => $dept, // idarah|imarah|riayah
            'section' => $section,
            'title' => $item->judul ?? 'Dokumentasi',
            'desc' => $item->deskripsi ?? "Kegiatan di Masjid Agung Al-A'la",
            'date' => optional($item->created_at)->format('Y-m-d'),
            'thumb' => $url,
            'src' => $url,
        ];
    });

    // Default filter (boleh ubah)
    $activeDept = request('dept', 'idarah'); // idarah|imarah|riayah
    $activeType = request('type', 'all');    // all|image|video
    $activeSection = request('section', ''); // kosong = semua seksi
@endphp

<style>
    :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }

    .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
    .scrollbar-none::-webkit-scrollbar{display:none;}

    .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
    .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}

    /* select dropdown readability */
    select, option { color:#0f172a; }
</style>

<div class="min-h-screen text-slate-100" style="background: var(--bg);">
    {{-- HERO --}}
    <header class="relative overflow-hidden">
        <div class="absolute inset-0" aria-hidden="true">
            <img src="{{ $heroBg }}" alt="Galeri Masjid" class="h-full w-full object-cover opacity-25" referrerpolicy="no-referrer">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b1f19]/85 via-[#13392f]/55 to-[#0b1f19]/90"></div>
            <div class="absolute -left-24 -top-20 h-72 w-72 rounded-full blur-3xl" style="background: rgba(231,177,75,0.14);"></div>
            <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            {{-- breadcrumb --}}
            <nav class="text-sm text-white/70">
                <ol class="flex items-center gap-2">
                    <li class="inline-flex items-center gap-2">
                        {{-- icon home --}}
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>
                        </svg>
                        <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                    </li>
                    <li class="text-white/40">/</li>
                    <li class="font-semibold text-white">Galeri</li>
                </ol>
            </nav>

            <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs uppercase tracking-[0.38em] text-white/70">Dokumentasi</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Galeri Masjid
                    </h1>
                    <p class="mt-2 text-sm text-white/75">
                        Dokumentasi foto & video kegiatan serta program kerja Masjid Agung Al-A'la.
                    </p>
                </div>

                {{-- Search (optional) --}}
                <form method="GET" class="w-full max-w-md">
                    <input type="hidden" name="dept" value="{{ $activeDept }}">
                    <input type="hidden" name="type" value="{{ $activeType }}">
                    <input type="hidden" name="section" value="{{ $activeSection }}">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-600">
                            {{-- icon search --}}
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/>
                            </svg>
                        </span>
                        <input id="gallery-search" type="text" placeholder="Cari judul / deskripsi..."
                               class="h-11 w-full rounded-2xl border border-white/12 bg-white/95 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.45)]">
                    </div>
                </form>
            </div>

            {{-- FILTER BAR --}}
            <section class="mt-6 {{ $glass }} p-4 sm:p-5">
                <div class="grid gap-3 lg:grid-cols-12 lg:items-center">

                    {{-- Tab Dept (Idarah/Imarah/Riayah) --}}
                    <div class="lg:col-span-5">
                        <div class="inline-flex w-full overflow-hidden rounded-2xl border border-white/12 bg-white/6">
                            @foreach($departments as $key => $meta)
                                <button type="button"
                                    data-dept="{{ $key }}"
                                    class="dept-btn flex-1 px-4 py-2 text-sm font-extrabold transition
                                        {{ $activeDept === $key ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
                                    style="{{ $activeDept === $key ? 'background: var(--accent);' : '' }}">
                                    {{-- icon folder --}}
                                    <span class="inline-flex items-center justify-center mr-2 align-middle">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 7h6l2 2h10v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"/>
                                        </svg>
                                    </span>
                                    {{ $meta['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Dropdown Seksi --}}
                    <div class="lg:col-span-4">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/70">
                                {{-- icon list --}}
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/>
                                </svg>
                            </span>
                            <select id="sectionSelect"
                                class="h-11 w-full appearance-none rounded-2xl border border-white/12 bg-white/10 pl-10 pr-10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.45)]">
                                <option value="">Semua Seksi</option>
                                {{-- opsi akan diisi JS sesuai dept --}}
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white/70">
                                {{-- icon chevron --}}
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Type switch (Foto/Video) --}}
                    <div class="lg:col-span-3 lg:justify-end flex">
                        <div class="inline-flex w-full overflow-hidden rounded-2xl border border-white/12 bg-white/6">
                            <button type="button" data-type="all"
                                class="type-btn flex-1 px-4 py-2 text-sm font-extrabold transition
                                    {{ $activeType==='all' ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
                                style="{{ $activeType==='all' ? 'background: var(--accent);' : '' }}">
                                Semua
                            </button>
                            <button type="button" data-type="image"
                                class="type-btn flex-1 px-4 py-2 text-sm font-extrabold transition
                                    {{ $activeType==='image' ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
                                style="{{ $activeType==='image' ? 'background: var(--accent);' : '' }}">
                                Foto
                            </button>
                            <button type="button" data-type="video"
                                class="type-btn flex-1 px-4 py-2 text-sm font-extrabold transition
                                    {{ $activeType==='video' ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
                                style="{{ $activeType==='video' ? 'background: var(--accent);' : '' }}">
                                Video
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between text-xs text-white/70">
                    <span id="resultCount">Menampilkan 0 item</span>
                    <span>Tip: klik item untuk melihat detail & navigasi</span>
                </div>
            </section>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 -mt-4">
        @if ($galleryItems->isEmpty())
            <div class="py-16 text-center {{ $glass }} p-10">
                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
                    {{-- icon image --}}
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 11l2-2 4 4 2-2 3 3"/><path d="M9 9h.01"/>
                    </svg>
                </div>
                <p class="mt-3 text-lg font-extrabold text-white">Belum ada konten galeri</p>
                <p class="text-sm text-white/75">Silakan tambahkan foto/video melalui panel admin.</p>
            </div>
        @else
            {{-- GRID --}}
            <div id="galleryGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($galleryItems as $i => $item)
                    <article
                        class="gallery-item group overflow-hidden rounded-[22px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)] backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/10 cursor-pointer"
                        data-gallery-item
                        data-index="{{ $i }}"
                        data-type="{{ $item['type'] }}"
                        data-dept="{{ $item['dept'] }}"
                        data-section="{{ $item['section'] }}"
                        data-title="{{ e($item['title']) }}"
                        data-desc="{{ e($item['desc']) }}"
                        data-date="{{ $item['date'] }}"
                        data-src="{{ $item['src'] }}"
                    >
                        <div class="relative">
                            <img
                                loading="lazy"
                                src="{{ $item['thumb'] }}"
                                alt="{{ $item['title'] }}"
                                referrerpolicy="no-referrer"
                                class="h-44 w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                onerror="this.src='{{ $fallbackImg }}';"
                            />

                            {{-- Badge dept --}}
                            <div class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/12 bg-black/55 px-2.5 py-1 text-[10px] font-extrabold tracking-wide text-white">
                                {{-- icon tag --}}
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10l-8 8-9-9V4h5l9 6z"/><path d="M7.5 7.5h.01"/>
                                </svg>
                                {{ strtoupper($item['dept']) }}
                            </div>

                            {{-- Type badge --}}
                            <div class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-extrabold text-[#13392f]" style="background: var(--accent);">
                                @if($item['type'] === 'video')
                                    {{-- icon play --}}
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    VIDEO
                                @else
                                    {{-- icon camera --}}
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 7h4l2-2h4l2 2h4v12H4z"/><circle cx="12" cy="13" r="3"/>
                                    </svg>
                                    FOTO
                                @endif
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-extrabold text-white line-clamp-2">{{ $item['title'] }}</h3>
                                    <p class="mt-1 text-xs text-white/70 line-clamp-1">{{ $item['section'] }}</p>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between text-[11px] text-white/65">
                                <span class="inline-flex items-center gap-1">
                                    {{-- icon calendar --}}
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                                    </svg>
                                    {{ $item['date'] ?: '-' }}
                                </span>

                                <span class="inline-flex items-center gap-1 opacity-90">
                                    {{-- icon arrow --}}
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>
                                    </svg>
                                    Buka
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $galeris->links() }}
            </div>
        @endif
    </main>

    {{-- LIGHTBOX --}}
    <div id="gallery-lightbox" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div id="gallery-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        <div class="relative flex h-[82vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-white/12 bg-[#0b1f19]/75 text-white shadow-2xl backdrop-blur">
            <div class="flex items-center justify-between border-b border-white/10 bg-black/30 px-4 py-3">
                <div class="min-w-0">
                    <h2 id="gallery-title" class="text-sm font-extrabold truncate"></h2>
                    <p id="gallery-meta" class="text-xs text-white/70 truncate"></p>
                </div>

                <button type="button" id="gallery-close"
                    class="rounded-2xl px-3 py-2 text-xs font-extrabold text-[#13392f] hover:brightness-105"
                    style="background: var(--accent);">
                    Tutup
                </button>
            </div>

            <div class="relative flex flex-1 items-center justify-center bg-black/60">
                <div id="gallery-body" class="max-h-[72vh] max-w-full"></div>

                <button type="button" id="gallery-prev"
                    class="absolute left-3 top-1/2 -translate-y-1/2 rounded-2xl border border-white/10 bg-white/90 p-3 text-slate-900 hover:bg-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button type="button" id="gallery-next"
                    class="absolute right-3 top-1/2 -translate-y-1/2 rounded-2xl border border-white/10 bg-white/90 p-3 text-slate-900 hover:bg-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-white/10 bg-black/30 px-4 py-3 text-xs">
                <div class="min-w-0">
                    <div class="text-white/90 font-semibold">Deskripsi</div>
                    <div id="gallery-desc" class="text-white/75 line-clamp-2"></div>
                </div>

                <a id="gallery-download" href="#" download
                   class="hidden shrink-0 rounded-2xl border border-white/12 bg-white/90 px-4 py-2 text-xs font-extrabold text-slate-900 hover:bg-white">
                    Unduh
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const departments = @json($departments);
    const fallbackImg = @json($fallbackImg);

    const deptButtons = Array.from(document.querySelectorAll('.dept-btn'));
    const typeButtons = Array.from(document.querySelectorAll('.type-btn'));
    const sectionSelect = document.getElementById('sectionSelect');
    const searchInput = document.getElementById('gallery-search');

    const items = Array.from(document.querySelectorAll('[data-gallery-item]'));
    const resultCount = document.getElementById('resultCount');

    let activeDept = @json($activeDept);
    let activeType = @json($activeType);
    let activeSection = @json($activeSection);

    function setActiveButton(group, predicate) {
        group.forEach(btn => {
            const active = predicate(btn);
            btn.style.background = active ? 'var(--accent)' : '';
            btn.classList.toggle('text-[#13392f]', active);
            btn.classList.toggle('text-white/85', !active);
        });
    }

    function populateSections(deptKey) {
        const sections = (departments[deptKey] && departments[deptKey].sections) ? departments[deptKey].sections : [];
        const current = activeSection || '';

        sectionSelect.innerHTML = `<option value="">Semua Seksi</option>`;
        sections.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            if (s === current) opt.selected = true;
            sectionSelect.appendChild(opt);
        });
    }

    function matchSearch(el, q) {
        if (!q) return true;
        const t = (el.dataset.title || '').toLowerCase();
        const d = (el.dataset.desc || '').toLowerCase();
        const s = (el.dataset.section || '').toLowerCase();
        return t.includes(q) || d.includes(q) || s.includes(q);
    }

    function applyFilters() {
        const q = (searchInput.value || '').trim().toLowerCase();
        let visible = 0;

        items.forEach(el => {
            const okDept = el.dataset.dept === activeDept;
            const okType = (activeType === 'all') || (el.dataset.type === activeType);
            const okSection = (!activeSection) || (el.dataset.section === activeSection);
            const okSearch = matchSearch(el, q);

            const show = okDept && okType && okSection && okSearch;
            el.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        resultCount.textContent = `Menampilkan ${visible} item`;
    }

    // ===== init =====
    populateSections(activeDept);
    setActiveButton(deptButtons, (b) => b.dataset.dept === activeDept);
    setActiveButton(typeButtons, (b) => b.dataset.type === activeType);
    applyFilters();

    // ===== dept click =====
    deptButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activeDept = btn.dataset.dept;
            activeSection = ''; // reset seksi saat ganti dept
            populateSections(activeDept);
            setActiveButton(deptButtons, (b) => b.dataset.dept === activeDept);
            applyFilters();
        });
    });

    // ===== type click =====
    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activeType = btn.dataset.type;
            setActiveButton(typeButtons, (b) => b.dataset.type === activeType);
            applyFilters();
        });
    });

    // ===== section change =====
    sectionSelect.addEventListener('change', () => {
        activeSection = sectionSelect.value || '';
        applyFilters();
    });

    // ===== search =====
    let searchTimer = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilters, 120);
    });

    // ===== LIGHTBOX =====
    const lightbox = document.getElementById('gallery-lightbox');
    const backdrop = document.getElementById('gallery-backdrop');
    const body = document.getElementById('gallery-body');
    const titleEl = document.getElementById('gallery-title');
    const metaEl = document.getElementById('gallery-meta');
    const descEl = document.getElementById('gallery-desc');
    const closeBtn = document.getElementById('gallery-close');
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    const downloadLink = document.getElementById('gallery-download');

    let visibleItems = [];
    let currentIndex = 0;

    function getVisibleItems() {
        visibleItems = items.filter(el => !el.classList.contains('hidden'));
    }

    function formatDateID(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d.getTime())) return dateStr;
        return new Intl.DateTimeFormat('id-ID', { day:'2-digit', month:'short', year:'numeric' }).format(d);
    }

    function renderLightbox() {
        const el = visibleItems[currentIndex];
        if (!el) return;

        const { title, desc, date, src, type, dept, section } = el.dataset;

        titleEl.textContent = title || 'Dokumentasi Masjid';
        metaEl.textContent = `${(dept || '').toUpperCase()} • ${section || '-'} • ${date ? formatDateID(date) : '-'}`;
        descEl.textContent = desc || '—';

        body.innerHTML = '';
        downloadLink.classList.add('hidden');

        if (type === 'video') {
            const video = document.createElement('video');
            video.src = src;
            video.controls = true;
            video.autoplay = true;
            video.className = 'max-h-[72vh] max-w-full';
            body.appendChild(video);
        } else {
            const img = document.createElement('img');
            img.src = src;
            img.alt = title || 'Dokumentasi';
            img.className = 'max-h-[72vh] max-w-full object-contain';
            img.referrerPolicy = 'no-referrer';
            img.onerror = () => { img.src = fallbackImg; };
            body.appendChild(img);

            downloadLink.href = src;
            downloadLink.classList.remove('hidden');
        }
    }

    function openLightboxByElement(el) {
        getVisibleItems();
        currentIndex = Math.max(0, visibleItems.indexOf(el));
        renderLightbox();
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function prev() {
        if (!visibleItems.length) return;
        currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
        renderLightbox();
    }

    function next() {
        if (!visibleItems.length) return;
        currentIndex = (currentIndex + 1) % visibleItems.length;
        renderLightbox();
    }

    items.forEach(el => {
        el.addEventListener('click', () => openLightboxByElement(el));
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') openLightboxByElement(el);
        });
        el.setAttribute('tabindex', '0');
        el.setAttribute('role', 'button');
        el.setAttribute('aria-label', 'Buka detail galeri');
    });

    closeBtn.addEventListener('click', closeLightbox);
    backdrop.addEventListener('click', closeLightbox);
    prevBtn.addEventListener('click', prev);
    nextBtn.addEventListener('click', next);

    window.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prev();
        if (e.key === 'ArrowRight') next();
    });
});
</script>
</x-front-layout>
