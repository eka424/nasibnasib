@extends('layouts.app')

@section('content')
    @php
        // ===== Kids stats sidebar (optional) =====
        // Aman walaupun tabel belum ada: pakai try/catch.
        $kidsStats = [
            'video' => 0,
            'dongeng' => 0,
            'kata' => 0,
        ];

        try {
            if (class_exists(\App\Models\KidsContent::class)) {
                $kidsStats['video'] = \App\Models\KidsContent::where('type', 'video')->count();
                $kidsStats['dongeng'] = \App\Models\KidsContent::where('type', 'dongeng')->count();
                $kidsStats['kata'] = \App\Models\KidsContent::where('type', 'kata')->count();
            }
        } catch (\Throwable $e) {
            // kalau tabel belum migrate / model beda, biarkan 0 (biar tidak error)
        }

        $hasKidsRoutes = \Illuminate\Support\Facades\Route::has('admin.kids.index') && \Illuminate\Support\Facades\Route::has('admin.kids.create');
    @endphp

    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                {{-- HEADER --}}
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Perpustakaan</h1>
                            <p class="text-gray-600">Manajemen koleksi buku dan peminjaman</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            {{-- CTA Kids --}}
                            @if($hasKidsRoutes)
                                <a href="{{ route('admin.kids.index') }}"
                                    class="inline-flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm hover:bg-emerald-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200">
                                    {{-- icon: spark --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 2l1.7 4.6L18 8.3l-4.3 1.7L12 14l-1.7-4L6 8.3l4.3-1.7L12 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 12l1 2.7L9 16l-3 1.3L5 20l-1-2.7L1 16l3-1.3L5 12z" />
                                    </svg>
                                    Kelola Ramah Anak
                                </a>
                            @endif

                            {{-- Add Book --}}
                            <a href="{{ route('admin.perpustakaans.create') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Tambah Buku
                            </a>
                        </div>
                    </div>
                </header>

                {{-- MAIN --}}
                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid gap-4 lg:grid-cols-12 md:gap-8">

                        {{-- LEFT: CONTENT --}}
                        <section class="lg:col-span-8 space-y-4 md:space-y-8">
                            {{-- Stats --}}
                            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                                @foreach ($stats as $stat)
                                    <x-admin.stat-card :label="$stat['label']" :value="$stat['value']"
                                        :description="$stat['description']" :icon="$stat['icon']" />
                                @endforeach
                            </div>

                            {{-- Filter --}}
                            <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                                <div class="p-6 pb-2">
                                    <div class="flex items-center gap-2">
                                        {{-- icon: filter --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M22 3H2l8 9v7l4 2v-9l8-9z" />
                                        </svg>
                                        <h2 class="text-base font-semibold leading-none tracking-tight text-slate-900">Filter Buku</h2>
                                    </div>
                                    <p class="text-sm text-slate-500">
                                        Saring koleksi buku berdasarkan kriteria tertentu.
                                    </p>
                                </div>

                                <form method="GET" class="flex flex-col gap-4 p-6 pt-0 md:flex-row md:items-end">
                                    <div class="relative flex-1">
                                        <span class="pointer-events-none absolute left-2.5 top-2.5 inline-flex text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 11.01 11.01Z" />
                                            </svg>
                                        </span>

                                        <input type="search" name="q" placeholder="Cari judul, penulis, atau ISBN..."
                                            value="{{ $filters['q'] ?? '' }}"
                                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10" />
                                    </div>

                                    <select name="status"
                                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[200px]">
                                        <option value="">Status Ketersediaan</option>
                                        @foreach ($statusOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <select name="kategori"
                                        class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[220px]">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoriOptions as $kategori)
                                            <option value="{{ $kategori }}" @selected(($filters['kategori'] ?? '') === $kategori)>{{ $kategori }}</option>
                                        @endforeach
                                    </select>

                                    <div class="flex w-full gap-2 md:w-auto">
                                        <button type="submit"
                                            class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 md:flex-none">
                                            Filter
                                        </button>
                                        @if (array_filter($filters ?? []))
                                            <a href="{{ route('admin.perpustakaans.index') }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                                Reset
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            {{-- Table --}}
                            <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                                <div class="p-6 pb-2">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 19.5A2.5 2.5 0 0 1 6.5 17H19" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 19V5a2 2 0 0 1 2-2h10" />
                                                </svg>
                                                <h2 class="text-base font-semibold leading-none tracking-tight">Daftar Koleksi Buku</h2>
                                            </div>
                                            <p class="text-sm text-slate-500">
                                                @if ($perpustakaans->total())
                                                    Menampilkan {{ $perpustakaans->count() }} dari {{ number_format($perpustakaans->total()) }} judul buku
                                                @else
                                                    Belum ada data buku
                                                @endif
                                            </p>
                                        </div>

                                        {{-- mini hint --}}
                                        <div class="hidden sm:block text-xs text-slate-500">
                                            Tip: klik menu (•••) untuk aksi cepat.
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto p-4 pt-0">
                                    <table class="w-full caption-bottom text-sm">
                                        <thead class="[&_tr]:border-b">
                                            <tr class="border-b">
                                                <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">Judul Buku</th>
                                                <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">Penulis</th>
                                                <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">Kategori</th>
                                                <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">Status</th>
                                                <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 lg:table-cell">Eksemplar</th>
                                                <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 lg:table-cell">ISBN</th>
                                                <th class="h-10 px-2 text-right align-middle font-medium text-slate-500">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="[&_tr:last-child]:border-0">
                                            @php
                                                $statusStyles = [
                                                    'available' => ['label' => 'Tersedia', 'classes' => 'bg-emerald-600 text-white'],
                                                    'borrowed' => ['label' => 'Habis', 'classes' => 'bg-slate-100 text-slate-800'],
                                                ];
                                            @endphp

                                            @forelse ($perpustakaans as $item)
                                                @php
                                                    $isAvailable = ($item->stok_tersedia ?? 0) > 0;
                                                    $statusKey = $isAvailable ? 'available' : 'borrowed';
                                                    $appearance = $statusStyles[$statusKey];
                                                @endphp

                                                <tr class="border-b">
                                                    <td class="p-2 align-middle">
                                                        <div class="flex items-center gap-3">
                                                            @if($item->cover)
                                                                @php
                                                                    $isLocalCover = !str_starts_with($item->cover, 'http');
                                                                    $coverUrl = $isLocalCover ? Storage::url($item->cover) : $item->cover;
                                                                @endphp
                                                                <img src="{{ $coverUrl }}" alt="{{ $item->judul }}"
                                                                    class="h-12 w-12 rounded-lg object-cover border border-slate-200" loading="lazy">
                                                            @else
                                                                <div class="h-12 w-12 rounded-lg border border-slate-200 bg-slate-50 grid place-items-center text-slate-400 text-xs">
                                                                    N/A
                                                                </div>
                                                            @endif

                                                            <div class="min-w-0">
                                                                <div class="font-medium text-slate-900 truncate max-w-[320px]">{{ $item->judul }}</div>
                                                                <div class="text-xs text-slate-500 md:hidden space-y-0.5">
                                                                    <div>{{ $item->penulis ?? 'Penulis belum diisi' }}</div>
                                                                    <div>{{ $item->kategori ?? 'Kategori umum' }} • ISBN {{ $item->isbn ?? 'Belum ada' }}</div>
                                                                    <div>Eksemplar: {{ $item->stok_tersedia ?? 0 }} dari {{ $item->stok_total ?? 0 }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="hidden p-2 align-middle md:table-cell">{{ $item->penulis ?? '—' }}</td>
                                                    <td class="hidden p-2 align-middle md:table-cell">{{ $item->kategori ?? 'Umum' }}</td>

                                                    <td class="p-2 align-middle">
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                            {{ $appearance['label'] }}
                                                        </span>
                                                    </td>

                                                    <td class="hidden p-2 align-middle lg:table-cell">
                                                        {{ $item->stok_tersedia ?? 0 }} / {{ $item->stok_total ?? 0 }}
                                                    </td>

                                                    <td class="hidden p-2 align-middle lg:table-cell">{{ $item->isbn ?? '—' }}</td>

                                                    <td class="p-2 align-middle text-right">
                                                        <details class="relative inline-block text-left">
                                                            <summary
                                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer"
                                                                role="button">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <circle cx="5" cy="12" r="1.5" />
                                                                    <circle cx="12" cy="12" r="1.5" />
                                                                    <circle cx="19" cy="12" r="1.5" />
                                                                </svg>
                                                                <span class="sr-only">Toggle menu</span>
                                                            </summary>

                                                            <div class="absolute right-0 mt-2 w-44 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                                <div class="px-3 py-2 text-xs font-semibold text-slate-500">Actions</div>

                                                                <a href="{{ route('admin.perpustakaans.show', $item) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Lihat Detail
                                                                </a>

                                                                <a href="{{ route('admin.perpustakaans.edit', $item) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Edit
                                                                </a>

                                                                <form action="{{ route('admin.perpustakaans.destroy', $item) }}" method="POST"
                                                                    onsubmit="return confirm('Hapus buku ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="flex w-full items-center px-3 py-1 text-red-600 hover:bg-slate-50">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </details>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="p-6 text-center text-sm text-slate-500">
                                                        Tidak ada buku yang sesuai dengan filter saat ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                    <div class="text-xs text-slate-500">
                                        @if ($perpustakaans->total())
                                            Menampilkan <strong>{{ $perpustakaans->firstItem() }}-{{ $perpustakaans->lastItem() }}</strong>
                                            dari <strong>{{ $perpustakaans->total() }}</strong> judul buku
                                        @else
                                            Menampilkan <strong>0</strong> buku
                                        @endif
                                    </div>

                                    <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                        <a href="{{ $perpustakaans->previousPageUrl() ?? '#' }}"
                                            class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $perpustakaans->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                            &larr; <span class="hidden sm:inline">Sebelumnya</span>
                                        </a>

                                        <a href="{{ $perpustakaans->nextPageUrl() ?? '#' }}"
                                            class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $perpustakaans->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
                                            <span class="hidden sm:inline">Berikutnya</span> &rarr;
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </section>

                        {{-- RIGHT: SIDEBAR HIGHLIGHT RAMAH ANAK --}}
                        <aside class="lg:col-span-4 space-y-4 md:space-y-6">
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-900 shadow-sm">
                                <div class="p-6 pb-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700/80">Highlight</p>
                                            <h3 class="mt-1 text-lg font-bold">Perpustakaan Ramah Anak</h3>
                                            <p class="mt-1 text-sm text-emerald-800/80">
                                                Kelola konten anak: video YouTube, dongeng PDF, dan kata-kata islami.
                                            </p>
                                        </div>
                                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-white text-2xl shadow-sm">
                                            🧒
                                        </div>
                                    </div>

                                    <div class="mt-4 grid grid-cols-3 gap-2">
                                        <div class="rounded-lg border border-emerald-200 bg-white p-3 text-center">
                                            <p class="text-[11px] text-emerald-700/70">Video</p>
                                            <p class="mt-1 text-base font-extrabold">{{ number_format($kidsStats['video']) }}</p>
                                        </div>
                                        <div class="rounded-lg border border-emerald-200 bg-white p-3 text-center">
                                            <p class="text-[11px] text-emerald-700/70">Dongeng</p>
                                            <p class="mt-1 text-base font-extrabold">{{ number_format($kidsStats['dongeng']) }}</p>
                                        </div>
                                        <div class="rounded-lg border border-emerald-200 bg-white p-3 text-center">
                                            <p class="text-[11px] text-emerald-700/70">Kata</p>
                                            <p class="mt-1 text-base font-extrabold">{{ number_format($kidsStats['kata']) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-2 border-t border-emerald-200 bg-white p-4">
                                    @if($hasKidsRoutes)
                                        <a href="{{ route('admin.kids.index') }}"
                                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                                            {{-- icon list --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                                            </svg>
                                            Kelola
                                        </a>

                                        <a href="{{ route('admin.kids.create') }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">
                                            {{-- icon plus --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                            </svg>
                                            Tambah
                                        </a>
                                    @else
                                        <div class="text-sm text-emerald-800/80">
                                            Route admin kids belum tersedia. Pastikan route: <span class="font-semibold">admin.kids.index</span> & <span class="font-semibold">admin.kids.create</span>.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Quick link front --}}
                            <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                                <div class="p-6">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Cek Tampilan Front</p>
                                            <p class="mt-1 text-xs text-slate-500">Lihat halaman ramah anak di website publik.</p>
                                        </div>
                                        <div class="grid h-10 w-10 place-items-center rounded-lg bg-slate-50 text-xl">🌙</div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('perpustakaan.ramah-anak') }}" target="_blank"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                            {{-- icon external --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h6m0 0v6m0-6L10 20" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 3H8a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h13" />
                                            </svg>
                                            Buka Halaman Ramah Anak
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </aside>

                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
