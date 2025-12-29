@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Perpustakaan</h1>
                            <p class="text-gray-600">Manajemen koleksi buku dan peminjaman</p>
                        </div>

                        <div class="flex items-center gap-2">
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
                                <h2 class="text-base font-semibold leading-none tracking-tight text-slate-900">Filter Buku</h2>
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
                                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="kategori"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[220px]">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoriOptions as $kategori)
                                        <option value="{{ $kategori }}" @selected(($filters['kategori'] ?? '') === $kategori)>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 md:flex-none">
                                        Filter
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.perpustakaans.index') }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="p-6 pb-2">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
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

                            <div class="overflow-x-auto p-4 pt-0">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b">
                                        <tr class="border-b">
                                            <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">
                                                Judul Buku
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">
                                                Penulis
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">
                                                Kategori
                                            </th>
                                            <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">
                                                Status
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 lg:table-cell">
                                                Eksemplar
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 lg:table-cell">
                                                ISBN
                                                </th>
                                            <th class="h-10 px-2 text-right align-middle font-medium text-slate-500">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @php
                                            $statusStyles = [
                                                'available' => ['label' => 'Tersedia', 'classes' => 'bg-green-500 text-white'],
                                                'borrowed' => ['label' => 'Dipinjam', 'classes' => 'bg-slate-100 text-slate-800'],
                                            ];
                                        @endphp
                                        @forelse ($perpustakaans as $item)
                                            @php
                                                $isAvailable = $item->stok_tersedia > 0;
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
                                                            <img src="{{ $coverUrl }}" alt="{{ $item->judul }}" class="h-12 w-12 rounded object-cover" loading="lazy">
                                                        @endif
                                                        <div>
                                                            <div class="font-medium text-slate-900">{{ $item->judul }}</div>
                                                            <div class="text-xs text-slate-500 md:hidden space-y-0.5">
                                                                <div>{{ $item->penulis ?? 'Penulis belum diisi' }}</div>
                                                                <div>{{ $item->kategori ?? 'Kategori umum' }} • ISBN {{ $item->isbn ?? 'Belum ada' }}</div>
                                                                <div>Eksemplar: {{ $item->stok_tersedia }} dari {{ $item->stok_total }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $item->penulis ?? '—' }}
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $item->kategori ?? 'Umum' }}
                                                </td>
                                                <td class="p-2 align-middle">
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                        {{ $appearance['label'] }}
                                                    </span>
                                                </td>
                                                <td class="hidden p-2 align-middle lg:table-cell">
                                                    {{ $item->stok_tersedia }} / {{ $item->stok_total }}
                                                </td>
                                                <td class="hidden p-2 align-middle lg:table-cell">
                                                    {{ $item->isbn ?? '—' }}
                                                </td>
                                                <td class="p-2 align-middle text-right">
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
                                                            class="absolute right-0 mt-2 w-44 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                            <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                                Actions
                                                            </div>
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
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
