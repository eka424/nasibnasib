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

        $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';
        $fallbackCover = 'https://images.unsplash.com/photo-1541417904950-b855846fe074?auto=format&fit=crop&w=1400&q=80';
        $glass = 'border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur';
    @endphp

    <style>
        :root {
            --primary: #10b981;
            --gold: #d4af37;
        }
        .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
        .scrollbar-none::-webkit-scrollbar{display:none;}
        .line-clamp-1{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:1;overflow:hidden;}
        .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    </style>

    <div class="min-h-screen scroll-smooth bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">
        {{-- HERO --}}
        <header class="relative overflow-hidden">
            <div class="absolute inset-0" aria-hidden="true">
                <img src="{{ $heroBg }}" alt="Perpustakaan" class="h-full w-full object-cover opacity-35" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-emerald-950/45 to-slate-900/90"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-12 text-center sm:px-6 lg:px-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-lg shadow-emerald-500/30">
                    <span class="text-2xl">📚</span>
                </div>
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">Perpustakaan Digital</h1>
                <p class="mx-auto mt-3 max-w-2xl text-base text-emerald-100/90">
                    Koleksi buku Islam dan literasi keagamaan untuk meningkatkan ilmu dan wawasan.
                </p>

                {{-- Quick Stats --}}
                <div class="mx-auto mt-6 max-w-4xl rounded-3xl p-4 sm:p-5 {{ $glass }}">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                            <p class="text-[11px] text-white/60">Total Buku</p>
                            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['books'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                            <p class="text-[11px] text-white/60">Kategori</p>
                            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['categories'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                            <p class="text-[11px] text-white/60">Total Dibaca</p>
                            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['views'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                            <p class="text-[11px] text-white/60">Total Diunduh</p>
                            <p class="mt-1 text-base font-extrabold text-white">{{ number_format($stats['downloads'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            {{-- SEARCH + FILTER --}}
            <section class="mb-6 rounded-2xl p-4 sm:p-6 {{ $glass }}">
                <form method="GET" class="grid gap-3 md:grid-cols-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-emerald-200/80">🔎</span>
                            <input
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari buku (judul, penulis, deskripsi)..."
                                class="h-11 w-full rounded-xl border border-white/15 bg-white/5 pl-10 pr-3 text-sm text-white placeholder:text-emerald-100/50 focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                        </div>
                    </div>

                    <div>
                        <select
                            name="category"
                            class="h-11 w-full rounded-xl border border-white/15 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                            <option value="">Semua Kategori</option>
                            @foreach ($categoryList as $cat)
                                <option value="{{ $cat }}" @selected((string) $selectedCategory === (string) $cat) class="text-slate-900">
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-xl border text-sm transition {{ $viewMode === 'grid' ? 'border-emerald-400 bg-emerald-500/20 text-white' : 'border-white/15 bg-white/5 text-white/80 hover:bg-white/10' }}"
                            aria-label="Tampilan grid">
                            ⬛⬛
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-xl border text-sm transition {{ $viewMode === 'list' ? 'border-emerald-400 bg-emerald-500/20 text-white' : 'border-white/15 bg-white/5 text-white/80 hover:bg-white/10' }}"
                            aria-label="Tampilan list">
                            ☰
                        </a>
                    </div>
                </form>

                {{-- tabs --}}
                <div class="mt-4 flex justify-center">
                    <div class="inline-grid grid-cols-2 overflow-hidden rounded-full border border-white/15 bg-white/10 shadow-sm shadow-emerald-500/10">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}"
                           class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold transition {{ $activeTab === 'all' ? 'bg-emerald-500/70 text-white' : 'text-white/85 hover:bg-white/10' }}">
                            <span>📚</span> Semua Buku
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'featured']) }}"
                           class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold transition {{ $activeTab === 'featured' ? 'bg-emerald-500/70 text-white' : 'text-white/85 hover:bg-white/10' }}">
                            <span>⭐</span> Pilihan Editor
                        </a>
                    </div>
                </div>

                {{-- result info --}}
                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-emerald-100/70">
                        Menampilkan <span class="font-semibold text-white">{{ $booksCollection->count() }}</span> buku
                        @if ($selectedCategory)
                            pada kategori <span class="font-semibold text-white">{{ $selectedCategory }}</span>
                        @endif
                        @if ($search)
                            dengan kata kunci "<span class="font-semibold text-white">{{ $search }}</span>"
                        @endif
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-white/60">Tip:</span>
                        <span class="text-[11px] text-emerald-100/70">Klik “Detail” untuk halaman detail.</span>
                    </div>
                </div>
            </section>

            @php
                $filteredBooks = $booksCollection->filter(function ($book) use ($activeTab, $selectedCategory, $search) {
                    if ($activeTab === 'featured' && empty($book->is_featured ?? $book->isFeatured)) {
                        return false;
                    }
                    if ($selectedCategory && ($book->kategori ?? $book->category?->name ?? '') !== $selectedCategory) {
                        return false;
                    }
                    if ($search) {
                        $haystack = strtolower(
                            ($book->judul ?? '') . ' ' .
                            ($book->penulis ?? '') . ' ' .
                            ($book->deskripsi ?? '') . ' ' .
                            ($book->kategori ?? $book->category?->name ?? '')
                        );
                        if (! str_contains($haystack, strtolower($search))) {
                            return false;
                        }
                    }
                    return true;
                });
            @endphp

            {{-- EMPTY --}}
            @if ($filteredBooks->isEmpty())
                <div class="rounded-2xl p-10 text-center {{ $glass }}">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-2xl">📖</div>
                    <h3 class="text-lg font-semibold text-white">Tidak ada buku ditemukan</h3>
                    <p class="mt-1 text-sm text-emerald-100/70">Coba ubah kata kunci pencarian atau filter kategori.</p>
                </div>
            @else
                {{-- LIST / GRID --}}
                <section class="{{ $viewMode === 'grid' ? 'grid gap-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'space-y-4' }}">
                    @foreach ($filteredBooks as $book)
                        @php
                            $cover = $book->cover ?? null;
                            if ($cover && ! str_starts_with($cover, 'http')) {
                                $cover = Storage::url($cover);
                            }
                            $cover = $cover ?: $fallbackCover;
                            $categoryLabel = $book->kategori ?? optional($book->category)->name;
                            $isAvailable = $book->stok_tersedia ?? $book->is_available ?? $book->isAvailable ?? true;
                            $viewCount = $book->view_count ?? $book->views ?? 0;
                            $downloadCount = $book->download_count ?? $book->downloads ?? 0;
                            $isFeatured = ! empty($book->is_featured ?? $book->isFeatured);
                        @endphp

                        @if ($viewMode === 'grid')
                            <article class="group overflow-hidden rounded-2xl border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur transition hover:-translate-y-0.5 hover:shadow-xl">
                                <div class="relative">
                                    <img src="{{ $cover }}" alt="{{ $book->judul }}" class="h-56 w-full object-cover" loading="lazy" referrerpolicy="no-referrer" onerror="this.src='{{ $fallbackCover }}';">
                                    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                        @if ($categoryLabel)
                                            <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-1 text-[10px] font-semibold text-white/90">
                                                {{ $categoryLabel }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-1 text-[10px] font-semibold text-white/90">
                                            {{ $isAvailable ? 'Tersedia' : 'Tidak tersedia' }}
                                        </span>
                                    </div>
                                    @if ($isFeatured)
                                        <div class="absolute right-3 top-3 rounded-full bg-amber-400/90 px-2 py-1 text-[10px] font-extrabold text-slate-900">⭐ PILIHAN</div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-base font-semibold text-white line-clamp-2 group-hover:text-emerald-200">{{ $book->judul }}</h3>
                                    @if ($book->penulis)
                                        <p class="mt-1 text-xs text-emerald-100/75">oleh {{ $book->penulis }}</p>
                                    @endif
                                    <p class="mt-2 text-sm text-white/75 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($book->deskripsi), 140) }}</p>
                                    <div class="mt-3 flex items-center justify-between text-[11px] text-white/60">
                                        <span>👁 {{ number_format($viewCount, 0, ',', '.') }}</span>
                                        <span>⬇ {{ number_format($downloadCount, 0, ',', '.') }}</span>
                                        @if (! empty($book->pages))
                                            <span>{{ $book->pages }} hal</span>
                                        @else
                                            <span></span>
                                        @endif
                                    </div>
                                    <div class="mt-3 flex gap-2">
                                        <a href="{{ route('perpustakaan.show', $book) }}"
                                           class="inline-flex flex-1 items-center justify-center rounded-xl px-3 py-2 text-xs font-extrabold text-slate-950"
                                           style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                            Detail
                                        </a>
                                        @if (! empty($book->file_ebook))
                                            <a href="{{ $book->file_ebook }}" target="_blank" rel="noreferrer"
                                               class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-3 py-2 text-xs font-extrabold text-white/85 hover:bg-white/10">
                                                Baca
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @else
                            <article class="rounded-2xl border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur transition hover:shadow-xl">
                                <div class="flex gap-4 p-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $cover }}" alt="{{ $book->judul }}" class="h-28 w-20 rounded-xl object-cover" loading="lazy" referrerpolicy="no-referrer" onerror="this.src='{{ $fallbackCover }}';">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($categoryLabel)
                                                <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-1 text-[10px] font-semibold text-white/90">
                                                    {{ $categoryLabel }}
                                                </span>
                                            @endif
                                            @if ($isFeatured)
                                                <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-1 text-[10px] font-semibold text-white/90">
                                                    ⭐ Pilihan
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-2 py-1 text-[10px] font-semibold text-white/90">
                                                {{ $isAvailable ? 'Tersedia' : 'Tidak tersedia' }}
                                            </span>
                                        </div>
                                        <h3 class="mt-2 text-lg font-semibold text-white hover:text-emerald-200">{{ $book->judul }}</h3>
                                        @if ($book->penulis)
                                            <p class="text-xs text-emerald-100/75">oleh {{ $book->penulis }}</p>
                                        @endif
                                        <p class="mt-2 text-sm text-white/75 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($book->deskripsi), 160) }}</p>
                                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-white/60">
                                            <span>👁 {{ number_format($viewCount, 0, ',', '.') }} dilihat</span>
                                            <span>⬇ {{ number_format($downloadCount, 0, ',', '.') }} diunduh</span>
                                            @if (! empty($book->publish_year ?? $book->year))
                                                <span>📅 {{ $book->publish_year ?? $book->year }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-3 flex gap-2">
                                            <a href="{{ route('perpustakaan.show', $book) }}"
                                               class="inline-flex items-center rounded-xl px-3 py-2 text-xs font-extrabold text-slate-950"
                                               style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                                Detail
                                            </a>
                                            @if (! empty($book->file_ebook))
                                                <a href="{{ $book->file_ebook }}" target="_blank" rel="noreferrer"
                                                   class="inline-flex items-center rounded-xl border border-white/15 bg-white/5 px-3 py-2 text-xs font-extrabold text-white/85 hover:bg-white/10">
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

                {{-- PAGINATION (keeps filters) --}}
                @if ($perpustakaans instanceof AbstractPaginator)
                    <div class="mt-8">
                        {{ $perpustakaans->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-front-layout>
