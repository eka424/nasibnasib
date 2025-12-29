@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Kegiatan</h1>
                            <p class="text-gray-600">Manajemen kegiatan dan acara masjid</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.kegiatans.create') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8M8 12h8" />
                                </svg>
                                Buat Kegiatan
                            </a>
                        </div>
                    </div>
                </header>

                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                            <x-admin.stat-card label="Total Kegiatan" :value="$stats['total']" icon="megaphone"
                                :description="number_format($kegiatans->total()) . ' kegiatan tercatat'" />
                            <x-admin.stat-card label="Mendatang" :value="$stats['upcoming']" icon="hourglass"
                                description="Jadwal yang akan datang" />
                            <x-admin.stat-card label="Berlangsung" :value="$stats['ongoing']" icon="check-circle"
                                description="Sedang berjalan" />
                            <x-admin.stat-card label="Peserta Terdaftar" :value="$stats['participants']" icon="users"
                                description="Total pendaftar kegiatan" />
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="px-6 pt-6">
                                <h2 class="text-base font-semibold text-slate-900">Filter Kegiatan</h2>
                                <p class="text-sm text-slate-500">
                                    Saring kegiatan berdasarkan kriteria tertentu.
                                </p>
                            </div>
                            <form method="GET"
                                class="flex flex-col gap-4 px-6 py-4 md:flex-row md:items-end">
                                <div class="relative flex-1">
                                    <span class="pointer-events-none absolute left-2.5 top-2.5 inline-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 11.01 11.01Z" />
                                        </svg>
                                    </span>
                                    <input type="search" name="q" placeholder="Cari nama atau lokasi kegiatan..."
                                        value="{{ $filters['q'] ?? '' }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10" />
                                </div>

                                <select name="status"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Status</option>
                                    <option value="upcoming" @selected(($filters['status'] ?? '') === 'upcoming')>Mendatang</option>
                                    <option value="ongoing" @selected(($filters['status'] ?? '') === 'ongoing')>Berlangsung</option>
                                    <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
                                </select>

                                <select name="time"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Waktu</option>
                                    <option value="today" @selected(($filters['time'] ?? '') === 'today')>Hari Ini</option>
                                    <option value="this_week" @selected(($filters['time'] ?? '') === 'this_week')>Minggu Ini</option>
                                    <option value="this_month" @selected(($filters['time'] ?? '') === 'this_month')>Bulan Ini</option>
                                    <option value="past" @selected(($filters['time'] ?? '') === 'past')>Yang Lalu</option>
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 md:flex-none">
                                        Terapkan
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.kegiatans.index') }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="px-6 pt-6">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 8V4l-5 4H5v6h6l5 4v-4l4-2V10l-4-2Z" />
                                    </svg>
                                    <h2 class="text-base font-semibold text-slate-900">
                                        Daftar Kegiatan
                                    </h2>
                                </div>
                                <p class="text-sm text-slate-500">
                                    @if ($kegiatans->total())
                                        Menampilkan {{ $kegiatans->count() }} kegiatan dari total {{ $kegiatans->total() }}
                                    @else
                                        Belum ada kegiatan yang tercatat
                                    @endif
                                </p>
                            </div>

                            <div class="px-6 py-4">
                                <div class="w-full overflow-x-auto">
                                    <table class="w-full caption-bottom text-sm">
                                        <thead class="[&_tr]:border-b">
                                            <tr class="border-b">
                                                <th
                                                    class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                    Nama Kegiatan
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Lokasi
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Tanggal
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                    Status
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 lg:table-cell">
                                                    Peserta
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-right align-middle text-xs font-medium text-slate-500">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="[&_tr:last-child]:border-0">
                                            @forelse ($kegiatans as $kegiatan)
                                                @php
                                                    $statusLabel = 'Mendatang';
                                                    $statusClasses = 'bg-slate-100 text-slate-800';
                                                    if ($kegiatan->tanggal_mulai->isFuture()) {
                                                        $statusLabel = 'Mendatang';
                                                        $statusClasses = 'bg-slate-100 text-slate-800';
                                                    } elseif (
                                                        $kegiatan->tanggal_selesai &&
                                                        $kegiatan->tanggal_selesai->isPast()
                                                    ) {
                                                        $statusLabel = 'Selesai';
                                                        $statusClasses = 'border border-slate-300 text-slate-700';
                                                    } else {
                                                        $statusLabel = 'Berlangsung';
                                                        $statusClasses = 'bg-emerald-500 text-white';
                                                    }
                                                @endphp
                                                <tr class="border-b">
                                                    <td class="p-2 align-middle">
                                                        <div class="font-medium text-slate-900">
                                                            {{ $kegiatan->nama_kegiatan }}
                                                        </div>
                                                        <div class="text-xs text-slate-500 md:hidden">
                                                            {{ $kegiatan->lokasi ?? 'Lokasi belum diisi' }}
                                                        </div>
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $kegiatan->lokasi ?? '—' }}
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $kegiatan->tanggal_mulai->translatedFormat('d M Y H:i') }}
                                                    </td>
                                                    <td class="p-2 align-middle">
                                                        <span
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </td>
                                                    <td class="hidden p-2 align-middle lg:table-cell">
                                                        {{ number_format($kegiatan->pendaftarans_count) }}
                                                    </td>
                                                    <td class="p-2 align-middle text-right">
                                                        <details class="relative inline-block text-left">
                                                            <summary
                                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer"
                                                                role="button"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <circle cx="5" cy="12" r="1.5" />
                                                                    <circle cx="12" cy="12" r="1.5" />
                                                                    <circle cx="19" cy="12" r="1.5" />
                                                                </svg>
                                                                <span class="sr-only">Toggle menu</span>
                                                            </summary>
                                                            <div
                                                                class="absolute right-0 mt-2 w-36 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                                <div
                                                                    class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                                    Aksi
                                                                </div>
                                                                <a href="{{ route('admin.kegiatans.show', $kegiatan) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Lihat
                                                                </a>
                                                                <a href="{{ route('admin.kegiatans.edit', $kegiatan) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Edit
                                                                </a>
                                                                <form action="{{ route('admin.kegiatans.destroy', $kegiatan) }}"
                                                                    method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
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
                                                    <td colspan="6" class="p-6 text-center text-sm text-slate-500">
                                                        Tidak ada kegiatan yang sesuai dengan filter saat ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                <div class="text-xs text-slate-500">
                                    @if ($kegiatans->total())
                                        Menampilkan <strong>{{ $kegiatans->firstItem() }}-{{ $kegiatans->lastItem() }}</strong>
                                        dari <strong>{{ $kegiatans->total() }}</strong> kegiatan
                                    @else
                                        Menampilkan <strong>0</strong> kegiatan
                                    @endif
                                </div>

                                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                    <a href="{{ $kegiatans->previousPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $kegiatans->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                        ‹ Sebelumnya
                                    </a>
                                    <a href="{{ $kegiatans->nextPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $kegiatans->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
                                        Selanjutnya ›
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
