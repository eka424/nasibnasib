<x-front-layout>
    @php
        // ====== DATA NORMALISASI ======
        $galleryItems = $galeris->map(function ($item) {
            $url = $item->url_file;

            // jika image lokal -> Storage::url
            if (($item->tipe ?? 'image') === 'image' && $url && ! str_starts_with($url, 'http')) {
                $url = Storage::url($url);
            }

            return [
                'type' => $item->tipe ?? 'image', // image|video
                'category' => $item->kategori ?? 'lainnya',
                'title' => $item->judul ?? 'Dokumentasi',
                'desc' => $item->deskripsi ?? "Kegiatan di Masjid Agung Al-A'la",
                'date' => optional($item->created_at)->format('Y-m-d'),
                'thumb' => $url,
                'src' => $url,
            ];
        });

        $categories = collect([
            ['id' => 'semua', 'label' => 'Semua'],
            ['id' => 'kegiatan', 'label' => 'Kegiatan'],
            ['id' => 'fasilitas', 'label' => 'Fasilitas'],
            ['id' => 'lainnya', 'label' => 'Lainnya'],
        ]);

        $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=80';
        $fallbackImg = 'https://images.unsplash.com/photo-1496302662116-35cc4f36df92?auto=format&fit=crop&w=1000&q=80';
    @endphp

    <style>
        :root {
            --primary: #059669; /* emerald */
            --gold: #D4AF37;    /* gold */
        }
        /* hide scrollbar */
        .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
        .scrollbar-none::-webkit-scrollbar{display:none;}
        /* line clamp (tailwind plugin may not exist) */
        .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
        .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
        .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}
    </style>

    <div class="min-h-screen scroll-smooth bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">
        {{-- HERO --}}
        <header class="relative overflow-hidden pt-10">
            <div class="absolute inset-0" aria-hidden="true">
                <img src="{{ $heroBg }}" alt="Galeri Masjid" class="h-full w-full object-cover opacity-30" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-emerald-950/50 to-slate-900/90"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                {{-- breadcrumb --}}
                <nav class="py-3 text-sm text-emerald-100/80">
                    <ol class="flex items-center gap-2">
                        <li class="inline-flex items-center gap-2">
                            <span class="opacity-90">⌂</span>
                            <a href="{{ route('home') }}" class="hover:text-emerald-300">Beranda</a>
                        </li>
                        <li class="text-white/60">›</li>
                        <li class="font-semibold text-white">Galeri</li>
                    </ol>
                </nav>

                <p class="text-xs uppercase tracking-[0.4em] text-emerald-200/80">Dokumentasi</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Dokumentasi Kegiatan Masjid
                </h1>
                <p class="mt-1 text-sm text-emerald-100/85">
                    Kumpulan foto dan video kegiatan, fasilitas, dan aktivitas Masjid Agung Al-A'la
                </p>

                {{-- FILTERS --}}
                <section class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    {{-- type switch --}}
                    <div class="inline-flex items-center rounded-2xl border border-white/15 bg-white/10 p-1 shadow-lg shadow-emerald-500/10 backdrop-blur">
                        <button type="button" data-filter-type="all"
                            class="px-3 py-1.5 text-sm rounded-xl inline-flex items-center gap-2 transition bg-emerald-600 text-white shadow">
                            Semua
                        </button>
                        <button type="button" data-filter-type="image"
                            class="px-3 py-1.5 text-sm rounded-xl inline-flex items-center gap-2 transition text-emerald-50 hover:text-white">
                            Foto
                        </button>
                        <button type="button" data-filter-type="video"
                            class="px-3 py-1.5 text-sm rounded-xl inline-flex items-center gap-2 transition text-emerald-50 hover:text-white">
                            Video
                        </button>
                    </div>

                    {{-- category chips --}}
                    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                        @foreach ($categories as $cat)
                            <button type="button"
                                data-filter-category="{{ $cat['id'] }}"
                                class="px-3 py-1.5 text-xs font-semibold rounded-xl border transition whitespace-nowrap
                                {{ $loop->first
                                    ? 'border-emerald-300 bg-emerald-500/15 text-emerald-50'
                                    : 'border-white/15 text-emerald-50 hover:text-white hover:border-emerald-200/60' }}">
                                {{ $cat['label'] }}
                            </button>
                        @endforeach
                    </div>
                </section>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="mx-auto mt-4 max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            @if ($galleryItems->isEmpty())
                <div class="py-16 text-center">
                    <div class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-2xl">
                        🖼️
                    </div>
                    <p class="mt-3 text-lg font-semibold text-white">Belum ada konten galeri</p>
                    <p class="text-sm text-emerald-100/80">Silakan tambahkan foto/video melalui panel admin.</p>
                </div>
            @else
                <div class="gap-4 columns-1 sm:columns-2 lg:columns-3 [column-fill:_balance]">
                    @foreach ($galleryItems as $i => $item)
                        <figure
                            class="group relative mb-4 break-inside-avoid overflow-hidden rounded-2xl border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur transition hover:-translate-y-0.5 hover:shadow-xl cursor-pointer"
                            data-gallery-item
                            data-index="{{ $i }}"
                            data-type="{{ $item['type'] }}"
                            data-category="{{ $item['category'] }}"
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
                                    class="h-auto w-full object-cover transition duration-700 group-hover:scale-[1.02]"
                                    onerror="this.src='{{ $fallbackImg }}';"
                                />
                                <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-black/60 px-2 py-1 text-[10px] font-semibold tracking-widest text-white border border-white/10">
                                    {{ strtoupper($item['type']) }}
                                </span>
                            </div>

                            <figcaption class="absolute inset-0 flex items-end bg-gradient-to-t from-black/70 via-transparent to-transparent p-3 opacity-0 transition group-hover:opacity-100">
                                <div class="text-white drop-shadow-sm min-w-0">
                                    <p class="text-sm font-semibold line-clamp-1">{{ $item['title'] }}</p>
                                    @if ($item['date'])
                                        <p class="text-xs text-white/90 line-clamp-1">{{ $item['date'] }}</p>
                                    @endif
                                </div>
                            </figcaption>
                        </figure>
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

            <div class="relative flex h-[80vh] w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-white/15 bg-slate-950/70 text-white shadow-2xl backdrop-blur">
                <div class="flex items-center justify-between bg-gradient-to-b from-black/60 to-transparent px-4 py-3">
                    <div class="flex flex-col min-w-0">
                        <h2 id="gallery-title" class="text-sm font-semibold truncate"></h2>
                        <p id="gallery-date" class="text-xs text-slate-200"></p>
                    </div>
                    <button type="button" id="gallery-close" class="rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-slate-900">
                        Tutup ✕
                    </button>
                </div>

                <div class="relative flex flex-1 items-center justify-center bg-black">
                    <div id="gallery-body" class="max-h-[70vh] max-w-full"></div>

                    <button type="button" id="gallery-prev"
                        class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-3 text-slate-900">
                        ‹
                    </button>
                    <button type="button" id="gallery-next"
                        class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-3 text-slate-900">
                        ›
                    </button>
                </div>

                <div class="flex items-center justify-between bg-gradient-to-t from-black/60 to-transparent px-4 py-3 text-xs">
                    <div class="flex items-center gap-2 min-w-0">
                        <span>ℹ</span>
                        <span id="gallery-desc" class="truncate text-white/90"></span>
                    </div>

                    <a id="gallery-download" href="#" download class="hidden rounded-xl bg-white/90 px-3 py-1 text-slate-900 font-semibold">
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeButtons = Array.from(document.querySelectorAll('[data-filter-type]'));
            const categoryButtons = Array.from(document.querySelectorAll('[data-filter-category]'));
            const items = Array.from(document.querySelectorAll('[data-gallery-item]'));

            let activeType = 'all';
            let activeCategory = 'semua';

            function applyFilters() {
                items.forEach(el => {
                    const type = el.dataset.type;
                    const category = el.dataset.category;
                    const show = (activeType === 'all' || type === activeType) &&
                                 (activeCategory === 'semua' || category === activeCategory);
                    el.classList.toggle('hidden', !show);
                });
            }

            // ====== type buttons ======
            typeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeType = btn.dataset.filterType;

                    // reset style
                    typeButtons.forEach(b => {
                        b.classList.remove('bg-emerald-600','text-white','shadow');
                        b.classList.add('text-emerald-50');
                    });

                    // active style
                    btn.classList.add('bg-emerald-600','text-white','shadow');
                    btn.classList.remove('text-emerald-50');

                    applyFilters();
                });
            });

            // ====== category buttons ======
            categoryButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeCategory = btn.dataset.filterCategory;

                    categoryButtons.forEach(b => {
                        b.classList.remove('border-emerald-300','bg-emerald-500/15','text-emerald-50');
                        b.classList.add('border-white/15','text-emerald-50');
                    });

                    btn.classList.add('border-emerald-300','bg-emerald-500/15','text-emerald-50');
                    btn.classList.remove('border-white/15');

                    applyFilters();
                });
            });

            applyFilters();

            // ====== LIGHTBOX ======
            const lightbox = document.getElementById('gallery-lightbox');
            const backdrop = document.getElementById('gallery-backdrop');
            const body = document.getElementById('gallery-body');
            const titleEl = document.getElementById('gallery-title');
            const dateEl = document.getElementById('gallery-date');
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

                const { title, desc, date, src, type } = el.dataset;

                titleEl.textContent = title || 'Dokumentasi Masjid';
                descEl.textContent = desc || '—';
                dateEl.textContent = date ? formatDateID(date) : '—';

                body.innerHTML = '';
                downloadLink.classList.add('hidden');

                if (type === 'video') {
                    const video = document.createElement('video');
                    video.src = src;
                    video.controls = true;
                    video.autoplay = true;
                    video.className = 'max-h-[70vh] max-w-full';
                    body.appendChild(video);
                } else {
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = title || 'Dokumentasi';
                    img.className = 'max-h-[70vh] max-w-full object-contain';
                    img.referrerPolicy = 'no-referrer';
                    img.onerror = () => { img.src = @json($fallbackImg); };
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
