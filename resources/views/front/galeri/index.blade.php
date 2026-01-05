<x-front-layout>
@php
    // ========= THEME =========
    $bg     = '#13392f';
    $accent = '#E7B14B';
    $glass  = 'rounded-[26px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

    $heroBg      = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=80';
    $fallbackImg = 'https://images.unsplash.com/photo-1496302662116-35cc4f36df92?auto=format&fit=crop&w=1000&q=80';

    // ========= FILTER HIERARCHY =========
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

    // ========= HELPERS =========
    $getDriveId = function (?string $url): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) return $m[1];

        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $q);
            if (!empty($q['id'])) return $q['id'];
        }

        if (preg_match('~[?&]id=([^&]+)~', $url, $m)) return $m[1];
        return null;
    };

    $driveThumb = function (?string $url) use ($getDriveId): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (str_contains($url, 'drive.google.com/thumbnail')) return $url;
        $id = $getDriveId($url);
        return $id ? "https://drive.google.com/thumbnail?id={$id}&sz=w1200" : $url;
    };

    $youtubeId = function (?string $url): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (preg_match('~youtu\.be/([^?&/]+)~', $url, $m)) return $m[1];
        if (preg_match('~youtube\.com/watch\?v=([^&]+)~', $url, $m)) return $m[1];
        if (preg_match('~youtube\.com/embed/([^?&/]+)~', $url, $m)) return $m[1];
        return null;
    };

    $youtubeThumb = function (?string $url) use ($youtubeId): ?string {
        $id = $youtubeId($url);
        return $id ? "https://i.ytimg.com/vi/{$id}/hqdefault.jpg" : null;
    };

    // ========= NORMALISASI ITEMS UNTUK GRID =========
    // IMPORTANT: tetap bawa modelnya (biar route('galeri.show', $galeri) aman)
    $galleryItems = $galeris->getCollection()->map(function ($galeri) use (
        $departments, $fallbackImg, $driveThumb, $youtubeThumb
    ) {
        $raw  = $galeri->url_file ?? '';
        $type = $galeri->tipe ?? 'image';

        $url = $raw;
        if ($url && !str_starts_with($url, 'http')) {
            $url = \Illuminate\Support\Facades\Storage::url($url);
        }

        $deptRaw = strtolower(trim((string)($galeri->kategori ?? '')));
        $dept = in_array($deptRaw, ['idarah','imarah','riayah'], true) ? $deptRaw : 'idarah';

        $sectionRaw = trim((string)($galeri->seksi ?? ''));
        $sectionsAllowed = $departments[$dept]['sections'] ?? ['Lainnya'];
        $section = in_array($sectionRaw, $sectionsAllowed, true) ? $sectionRaw : 'Lainnya';

        $thumb = $url ?: $fallbackImg;

        // YouTube video thumb
        if ($type === 'video') {
            $yt = $youtubeThumb($url);
            if ($yt) $thumb = $yt;
        }

        // Drive thumb
        if ($url && str_contains($url, 'drive.google.com')) {
            $thumb = $driveThumb($url) ?: $thumb;
        }

        return [
            'model'   => $galeri,
            'type'    => $type,
            'dept'    => $dept,
            'section' => $section,
            'title'   => $galeri->judul ?? 'Dokumentasi',
            'desc'    => $galeri->deskripsi ?? '',
            'date'    => optional($galeri->created_at)->format('Y-m-d'),
            'thumb'   => $thumb,
        ];
    });

    $activeDept    = request('dept', 'idarah');
    $activeType    = request('type', 'all');
    $activeSection = request('section', '');
@endphp

<style>
    :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }
    .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
    .scrollbar-none::-webkit-scrollbar{display:none;}
    .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
    .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}
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
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Galeri Masjid</h1>
                    <p class="mt-2 text-sm text-white/75">
                        Dokumentasi foto & video kegiatan serta program kerja Masjid Agung Al-A'la.
                    </p>
                </div>

                <div class="w-full max-w-md">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/>
                            </svg>
                        </span>
                        <input id="gallery-search" type="text" placeholder="Cari judul / deskripsi..."
                               class="h-11 w-full rounded-2xl border border-white/12 bg-white/95 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.45)]">
                    </div>
                </div>
            </div>

            {{-- FILTER BAR (CLIENT SIDE, TANPA LIGHTBOX) --}}
            <section class="mt-6 {{ $glass }} p-4 sm:p-5">
                <div class="grid gap-3 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-5">
                        <div class="inline-flex w-full overflow-hidden rounded-2xl border border-white/12 bg-white/6">
                            @foreach($departments as $key => $meta)
                                <button type="button"
                                    data-dept="{{ $key }}"
                                    class="dept-btn flex-1 px-4 py-2 text-sm font-extrabold transition
                                        {{ $activeDept === $key ? 'text-[#13392f]' : 'text-white/85 hover:bg-white/10' }}"
                                    style="{{ $activeDept === $key ? 'background: var(--accent);' : '' }}">
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

                    <div class="lg:col-span-4">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/70">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/>
                                </svg>
                            </span>
                            <select id="sectionSelect"
                                class="h-11 w-full appearance-none rounded-2xl border border-white/12 bg-white/10 pl-10 pr-10 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.45)]">
                                <option value="">Semua Seksi</option>
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-white/70">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6"/>
                                </svg>
                            </span>
                        </div>
                    </div>

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
                    <span>Tip: klik item untuk melihat detail</span>
                </div>
            </section>
        </div>
    </header>

    {{-- MAIN --}}
    <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 -mt-4">
        @if ($galleryItems->isEmpty())
            <div class="py-16 text-center {{ $glass }} p-10">
                <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/12 bg-white/6 text-white">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M8 11l2-2 4 4 2-2 3 3"/><path d="M9 9h.01"/>
                    </svg>
                </div>
                <p class="mt-3 text-lg font-extrabold text-white">Belum ada konten galeri</p>
                <p class="text-sm text-white/75">Silakan tambahkan foto/video melalui panel admin.</p>
            </div>
        @else
            <div id="galleryGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($galleryItems as $i => $item)
                    @php $galeri = $item['model']; @endphp

                    <a
                        href="{{ route('galeri.show', $galeri) }}"
                        class="gallery-item group block overflow-hidden rounded-[22px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.35)] backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/10"
                        data-gallery-item
                        data-dept="{{ e($item['dept']) }}"
                        data-type="{{ e($item['type']) }}"
                        data-section="{{ e($item['section']) }}"
                        data-title="{{ e($item['title']) }}"
                        data-desc="{{ e($item['desc']) }}"
                    >
                        <div class="relative">
                            <img
                                loading="lazy"
                                src="{{ e($item['thumb']) }}"
                                alt="{{ e($item['title']) }}"
                                referrerpolicy="no-referrer"
                                class="h-44 w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                onerror="this.onerror=null;this.src='{{ $fallbackImg }}';"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-transparent"></div>

                            <div class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full border border-white/12 bg-black/55 px-2.5 py-1 text-[10px] font-extrabold tracking-wide text-white">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10l-8 8-9-9V4h5l9 6z"/><path d="M7.5 7.5h.01"/>
                                </svg>
                                {{ strtoupper($item['dept']) }}
                            </div>

                            <div class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-extrabold text-[#13392f]" style="background: var(--accent);">
                                @if($item['type'] === 'video')
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    VIDEO
                                @else
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 7h4l2-2h4l2 2h4v12H4z"/><circle cx="12" cy="13" r="3"/>
                                    </svg>
                                    FOTO
                                @endif
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-extrabold text-white line-clamp-2">{{ $item['title'] }}</h3>
                            <p class="mt-1 text-xs text-white/70 line-clamp-1">{{ $item['section'] }}</p>

                            <div class="mt-3 flex items-center justify-between text-[11px] text-white/65">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                                    </svg>
                                    {{ $item['date'] ?: '-' }}
                                </span>

                                <span class="inline-flex items-center gap-1 opacity-90">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>
                                    </svg>
                                    Buka
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $galeris->links() }}
            </div>
        @endif
    </main>
</div>

{{-- FILTER JS ONLY (NO LIGHTBOX) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const departments = @json($departments);

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

    // init
    populateSections(activeDept);
    setActiveButton(deptButtons, (b) => b.dataset.dept === activeDept);
    setActiveButton(typeButtons, (b) => b.dataset.type === activeType);
    applyFilters();

    deptButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activeDept = btn.dataset.dept;
            activeSection = '';
            populateSections(activeDept);
            setActiveButton(deptButtons, (b) => b.dataset.dept === activeDept);
            applyFilters();
        });
    });

    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            activeType = btn.dataset.type;
            setActiveButton(typeButtons, (b) => b.dataset.type === activeType);
            applyFilters();
        });
    });

    sectionSelect.addEventListener('change', () => {
        activeSection = sectionSelect.value || '';
        applyFilters();
    });

    let searchTimer = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilters, 120);
    });
});
</script>
</x-front-layout>
