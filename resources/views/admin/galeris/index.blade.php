@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Galeri</h1>
                            <p class="text-gray-600">Manajemen media gambar dan video masjid</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.galeris.create') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Upload Media
                            </a>
                        </div>
                    </div>
                </header>

                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                            @foreach ($stats as $stat)
                                <x-admin.stat-card :label="$stat['label']" :value="$stat['value']"
                                    :description="$stat['description']" :icon="$stat['icon']" />
                            @endforeach
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="p-6 pb-2">
                                <h2 class="text-base font-semibold leading-none tracking-tight text-slate-900">Filter Media</h2>
                                <p class="text-sm text-slate-500">
                                    Saring media galeri berdasarkan kriteria tertentu.
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
                                    <input type="search" name="q" placeholder="Cari judul media..."
                                        value="{{ $filters['q'] ?? '' }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10" />
                                </div>

                                <select name="tipe"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[160px]">
                                    @foreach ($typeOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['tipe'] ?? '') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- FILTER BARU: KATEGORI --}}
                                <select name="kategori" id="filterKategori"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[160px]">
                                    @foreach ($deptOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['kategori'] ?? '') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- FILTER BARU: SEKSI (diisi JS) --}}
                                <select name="seksi" id="filterSeksi"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[240px]">
                                    <option value="">Semua Seksi</option>
                                </select>

                                <select name="sort"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[160px]">
                                    @foreach ($sortOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 md:flex-none">
                                        Terapkan
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.galeris.index') }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <div
                            class="grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            @forelse ($galeris as $galeri)
                                @php
                                    $isLocal = $galeri->url_file && ! \Illuminate\Support\Str::startsWith($galeri->url_file, 'http');
                                    $mediaUrl = $isLocal ? \Illuminate\Support\Facades\Storage::url($galeri->url_file) : $galeri->url_file;
                                    $isImage = $galeri->tipe === 'image';

                                    $deptLabel = [
                                        'idarah' => 'Idarah',
                                        'imarah' => 'Imarah',
                                        'riayah' => 'Riayah',
                                    ][$galeri->kategori ?? 'idarah'] ?? 'Idarah';
                                @endphp

                                <div class="flex h-full flex-col rounded-lg border border-slate-200 bg-card text-card-foreground shadow-sm">
                                    <div class="relative aspect-video w-full overflow-hidden rounded-t-lg bg-slate-100">
                                        @if ($isImage)
                                            <img src="{{ $mediaUrl }}" alt="{{ $galeri->judul }}" class="h-full w-full object-cover"
                                                loading="lazy">
                                        @else
                                            <div class="absolute inset-0 flex h-full w-full items-center justify-center bg-slate-900/80 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.752 11.168l-4.596-2.65A1 1 0 009 9.35v5.3a1 1 0 001.156.982l4.596-2.65a1 1 0 000-1.764z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- badge kategori --}}
                                        <div class="absolute left-2 top-2 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-slate-800">
                                            {{ $deptLabel }}
                                        </div>

                                        {{-- badge tipe --}}
                                        <div class="absolute right-2 top-2 rounded-full bg-slate-900/85 px-2.5 py-1 text-[11px] font-semibold text-white">
                                            {{ $isImage ? 'Gambar' : 'Video' }}
                                        </div>
                                    </div>

                                    <div class="flex flex-1 flex-col gap-2 p-4">
                                        <h3 class="text-base font-semibold text-slate-900 leading-snug">
                                            {{ $galeri->judul }}
                                        </h3>

                                        <div class="text-xs text-slate-500">
                                            Seksi:
                                            <span class="font-medium text-slate-700">
                                                {{ $galeri->seksi ?: '—' }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-slate-500">
                                            {{ $galeri->deskripsi ? \Illuminate\Support\Str::limit($galeri->deskripsi, 80) : 'Belum ada deskripsi' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between border-t border-slate-200 px-4 py-3 text-xs text-slate-500">
                                        <span>Diunggah {{ $galeri->created_at->translatedFormat('d M Y') }}</span>
                                        <details class="relative inline-block text-left">
                                            <summary
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer"
                                                role="button"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <circle cx="5" cy="12" r="1.5" />
                                                    <circle cx="12" cy="12" r="1.5" />
                                                    <circle cx="19" cy="12" r="1.5" />
                                                </svg>
                                                <span class="sr-only">Toggle menu</span>
                                            </summary>
                                            <div
                                                class="absolute right-0 mt-2 w-40 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                    Actions
                                                </div>
                                                <a href="{{ route('admin.galeris.show', $galeri) }}"
                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('admin.galeris.edit', $galeri) }}"
                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.galeris.destroy', $galeri) }}" method="POST"
                                                    onsubmit="return confirm('Hapus media ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex w-full items-center px-3 py-1 text-red-600 hover:bg-slate-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full rounded-xl border border-dashed border-slate-200 p-10 text-center">
                                    <p class="text-sm text-slate-500">Belum ada media yang sesuai dengan filter saat ini.</p>
                                    <a href="{{ route('admin.galeris.create') }}"
                                        class="mt-3 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                        Tambah Media
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-6 py-4 text-sm shadow-sm">
                            <div class="text-xs text-slate-500">
                                @if ($galeris->total())
                                    Menampilkan <strong>{{ $galeris->firstItem() }}-{{ $galeris->lastItem() }}</strong>
                                    dari <strong>{{ $galeris->total() }}</strong> media
                                @else
                                    Menampilkan <strong>0</strong> media
                                @endif
                            </div>
                            <nav class="inline-flex items-center gap-2 text-sm" aria-label="Pagination">
                                <a href="{{ $galeris->previousPageUrl() ?? '#' }}"
                                    class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-3 py-1 text-xs {{ $galeris->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                    &larr; <span class="hidden sm:inline">Sebelumnya</span>
                                </a>
                                <a href="{{ $galeris->nextPageUrl() ?? '#' }}"
                                    class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-3 py-1 text-xs {{ $galeris->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
                                    <span class="hidden sm:inline">Berikutnya</span> &rarr;
                                </a>
                            </nav>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sectionsMap = @json($sectionsMap ?? []);
        const kategoriEl = document.getElementById('filterKategori');
        const seksiEl = document.getElementById('filterSeksi');

        const currentKategori = @json($filters['kategori'] ?? '');
        const currentSeksi = @json($filters['seksi'] ?? '');

        function fillSeksi(kategori) {
            seksiEl.innerHTML = '<option value="">Semua Seksi</option>';

            if (!kategori) return; // kalau kategori kosong => semua seksi

            const list = sectionsMap[kategori] || [];
            list.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                if (s === currentSeksi) opt.selected = true;
                seksiEl.appendChild(opt);
            });
        }

        // init
        fillSeksi(currentKategori);

        kategoriEl.addEventListener('change', () => {
            // kalau ganti kategori, reset seksi biar tidak mismatch
            fillSeksi(kategoriEl.value);
            seksiEl.value = '';
        });
    });
    </script>
@endsection
