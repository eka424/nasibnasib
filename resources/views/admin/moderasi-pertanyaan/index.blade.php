@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Tanya Ustadz</h1>
                            <p class="text-gray-600">
                                Manajemen pertanyaan jamaah kepada ustadz
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">
                                <span class="text-base">＋</span>
                                <span>Buat Pertanyaan Manual</span>
                            </button>
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
                                <h2 class="text-base font-semibold leading-none tracking-tight text-slate-900">Filter Pertanyaan</h2>
                                <p class="text-sm text-slate-500">
                                    Saring pertanyaan berdasarkan kriteria tertentu.
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
                                    <input type="search" name="q" placeholder="Cari judul pertanyaan atau penanya..."
                                        value="{{ $filters['q'] ?? '' }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10" />
                                </div>

                                <select name="status"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Status</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>

                                <select name="kategori"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Kategori</option>
                                    @foreach ($kategoriOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['kategori'] ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>

                                <select name="ustadz"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[200px]">
                                    <option value="">Semua Ustadz</option>
                                    <option value="unassigned" @selected(($filters['ustadz'] ?? '') === 'unassigned')>Belum Ditugaskan</option>
                                    @foreach ($ustadzs as $ustadz)
                                        <option value="{{ $ustadz->id }}" @selected(($filters['ustadz'] ?? '') == $ustadz->id)>
                                            {{ $ustadz->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 md:flex-none">
                                        Filter
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.moderasi.index') }}"
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
                                    <span class="text-lg">❓</span>
                                    <h2 class="text-base font-semibold leading-none tracking-tight">
                                        Daftar Pertanyaan Jamaah
                                    </h2>
                                </div>
                                <p class="text-sm text-slate-500">
                                    @if ($pertanyaans->total())
                                        Menampilkan {{ $pertanyaans->count() }} dari {{ number_format($pertanyaans->total()) }} pertanyaan
                                    @else
                                        Belum ada data pertanyaan
                                    @endif
                                </p>
                            </div>

                            <div class="overflow-x-auto p-4 pt-0">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b">
                                        <tr class="border-b">
                                            <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">
                                                Judul Pertanyaan
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">
                                                Penanya
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">
                                                Ustadz
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 md:table-cell">
                                                Kategori
                                            </th>
                                            <th class="h-10 px-2 text-left align-middle font-medium text-slate-500">
                                                Status
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle font-medium text-slate-500 lg:table-cell">
                                                Tanggal Tanya
                                            </th>
                                            <th class="h-10 px-2 text-right align-middle font-medium text-slate-500">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @php
                                            $statusStyles = [
                                                'menunggu' => ['label' => 'Belum Dijawab', 'classes' => 'bg-slate-100 text-slate-800'],
                                                'dijawab' => ['label' => 'Sudah Dijawab', 'classes' => 'bg-green-500 text-white'],
                                            ];
                                        @endphp
                                        @forelse ($pertanyaans as $pertanyaan)
                                            @php
                                                $appearance = $statusStyles[$pertanyaan->status] ?? $statusStyles['menunggu'];
                                            @endphp
                                            <tr class="border-b">
                                                <td class="p-2 align-middle">
                                                    <div class="font-medium text-slate-900">{{ \Illuminate\Support\Str::limit($pertanyaan->pertanyaan, 80) }}</div>
                                                    <div class="text-xs text-slate-500 md:hidden">
                                                        {{ $pertanyaan->penanya->name }} • {{ ucfirst($pertanyaan->kategori) }}
                                                    </div>
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $pertanyaan->penanya->name }}
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ optional($pertanyaan->ustadz)->name ?? 'Belum ditugaskan' }}
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ ucfirst($pertanyaan->kategori) }}
                                                </td>
                                                <td class="p-2 align-middle">
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                        {{ $appearance['label'] }}
                                                    </span>
                                                </td>
                                                <td class="hidden p-2 align-middle lg:table-cell">
                                                    {{ $pertanyaan->created_at->translatedFormat('d M Y') }}
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
                                                            <span class="sr-only">Menu aksi</span>
                                                        </summary>
                                                        <div
                                                            class="absolute right-0 mt-2 w-64 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                            <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                                Tindakan
                                                            </div>
                                                            <div class="px-3 py-2 border-t border-slate-100 space-y-2">
                                                                <form action="{{ route('admin.moderasi.assign', $pertanyaan) }}" method="POST"
                                                                    class="space-y-2">
                                                                    @csrf
                                                                    <label class="text-xs font-semibold text-slate-500">Tugaskan Ustadz</label>
                                                                    <select name="ustadz_id"
                                                                        class="w-full rounded border-slate-200 text-sm focus:border-slate-300 focus:ring-slate-200">
                                                                        @foreach ($ustadzs as $ustadz)
                                                                            <option value="{{ $ustadz->id }}" @selected($pertanyaan->ustadz_id == $ustadz->id)>
                                                                                {{ $ustadz->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="submit"
                                                                        class="w-full rounded bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                                                                        Simpan Penugasan
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <form action="{{ route('admin.moderasi.destroy', $pertanyaan) }}" method="POST"
                                                                onsubmit="return confirm('Hapus pertanyaan ini?')"
                                                                class="border-t border-slate-100">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-slate-50">
                                                                    Hapus Pertanyaan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </details>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="p-6 text-center text-sm text-slate-500">
                                                    Tidak ada pertanyaan ditemukan untuk filter saat ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-2 flex items-center justify-between border-t border-slate-200 px-6 py-4 text-sm text-slate-500">
                                <div>
                                    @if ($pertanyaans->total())
                                        Menampilkan <strong>{{ $pertanyaans->firstItem() }}-{{ $pertanyaans->lastItem() }}</strong>
                                        dari <strong>{{ $pertanyaans->total() }}</strong> pertanyaan
                                    @else
                                        Menampilkan <strong>0</strong> pertanyaan
                                    @endif
                                </div>
                                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                    <a href="{{ $pertanyaans->previousPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $pertanyaans->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                        &larr; <span class="hidden sm:inline">Sebelumnya</span>
                                    </a>
                                    <a href="{{ $pertanyaans->nextPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $pertanyaans->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
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
