@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Pendaftaran</h1>
                            <p class="text-gray-600">
                                Manajemen pendaftaran kegiatan dan acara masjid
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8M8 12h8" />
                                </svg>
                                Buat Pendaftaran Manual
                            </button>
                        </div>
                    </div>
                </header>

                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                            <x-admin.stat-card label="Total Pendaftaran" :value="$stats['total']['value']" icon="clipboard"
                                :description="$stats['total']['description']" />
                            <x-admin.stat-card label="Pending" :value="$stats['pending']['value']" icon="user-plus"
                                :description="$stats['pending']['description']" />
                            <x-admin.stat-card label="Disetujui" :value="$stats['approved']['value']" icon="user-check"
                                :description="$stats['approved']['description']" />
                            <x-admin.stat-card label="Peserta Unik" :value="$stats['unique']['value']" icon="users"
                                :description="$stats['unique']['description']" />
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="px-6 pt-6">
                                <h2 class="text-base font-semibold text-slate-900">Filter Pendaftaran</h2>
                                <p class="text-sm text-slate-500">
                                    Saring pendaftaran berdasarkan kriteria tertentu.
                                </p>
                            </div>
                            <form method="GET" class="flex flex-col gap-4 px-6 py-4 md:flex-row md:items-end">
                                <div class="relative flex-1">
                                    <span class="pointer-events-none absolute left-2.5 top-2.5 inline-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 11.01 11.01Z" />
                                        </svg>
                                    </span>
                                    <input type="search" name="q" placeholder="Cari nama pendaftar atau kegiatan..."
                                        value="{{ $filters['q'] ?? '' }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10" />
                                </div>

                                <select name="status"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Status</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="kegiatan_id"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[220px]">
                                    <option value="">Kegiatan</option>
                                    @foreach ($kegiatans as $kegiatan)
                                        <option value="{{ $kegiatan->id }}" @selected(($filters['kegiatan_id'] ?? '') == $kegiatan->id)>
                                            {{ $kegiatan->nama_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="waktu"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Waktu Daftar</option>
                                    <option value="today" @selected(($filters['waktu'] ?? '') === 'today')>Hari Ini</option>
                                    <option value="this_week" @selected(($filters['waktu'] ?? '') === 'this_week')>Minggu Ini</option>
                                    <option value="this_month" @selected(($filters['waktu'] ?? '') === 'this_month')>Bulan Ini</option>
                                    <option value="past" @selected(($filters['waktu'] ?? '') === 'past')>Yang Lalu</option>
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 md:flex-none">
                                        Terapkan
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.pendaftaran-kegiatans.index') }}"
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
                                        <rect x="7" y="4" width="10" height="16" rx="2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h4M9 16h3" />
                                    </svg>
                                    <h2 class="text-base font-semibold text-slate-900">
                                        Daftar Pendaftaran
                                    </h2>
                                </div>
                                <p class="text-sm text-slate-500">
                                    @if ($pendaftaranKegiatans->total())
                                        Menampilkan {{ $pendaftaranKegiatans->count() }} dari
                                        {{ number_format($pendaftaranKegiatans->total()) }} pendaftaran
                                    @else
                                        Belum ada data pendaftaran
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
                                                    Nama Pendaftar
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Kegiatan
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Tanggal Daftar
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                    Status
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 lg:table-cell">
                                                    Email
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-right align-middle text-xs font-medium text-slate-500">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="[&_tr:last-child]:border-0">
                                            @php
                                                $statusStyles = [
                                                    'menunggu' => ['label' => 'Pending', 'classes' => 'bg-slate-100 text-slate-800'],
                                                    'diterima' => ['label' => 'Disetujui', 'classes' => 'bg-emerald-500 text-white'],
                                                    'ditolak' => ['label' => 'Ditolak', 'classes' => 'bg-red-100 text-red-700'],
                                                ];
                                            @endphp
                                            @forelse ($pendaftaranKegiatans as $pendaftaran)
                                                @php
                                                    $appearance = $statusStyles[$pendaftaran->status] ?? $statusStyles['menunggu'];
                                                @endphp
                                                <tr class="border-b">
                                                    <td class="p-2 align-middle">
                                                        <div class="font-medium text-slate-900">
                                                            {{ $pendaftaran->user->name }}
                                                        </div>
                                                        <div class="text-xs text-slate-500 md:hidden">
                                                            {{ $pendaftaran->kegiatan->nama_kegiatan }}
                                                        </div>
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $pendaftaran->kegiatan->nama_kegiatan }}
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $pendaftaran->created_at->translatedFormat('d M Y H:i') }}
                                                    </td>
                                                    <td class="p-2 align-middle">
                                                        <span
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                            {{ $appearance['label'] }}
                                                        </span>
                                                    </td>
                                                    <td class="hidden p-2 align-middle lg:table-cell">
                                                        {{ $pendaftaran->user->email }}
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
                                                                class="absolute right-0 mt-2 w-44 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                                <div
                                                                    class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                                    Actions
                                                                </div>
                                                                <a href="{{ route('admin.pendaftaran-kegiatans.show', $pendaftaran) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Lihat Detail
                                                                </a>
                                                                <a href="{{ route('admin.pendaftaran-kegiatans.edit', $pendaftaran) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Ubah Status
                                                                </a>
                                                                <form action="{{ route('admin.pendaftaran-kegiatans.destroy', $pendaftaran) }}"
                                                                    method="POST" onsubmit="return confirm('Hapus pendaftaran ini?')">
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
                                                        Tidak ada pendaftaran sesuai filter saat ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                <div class="text-xs text-slate-500">
                                    @if ($pendaftaranKegiatans->total())
                                        Menampilkan <strong>{{ $pendaftaranKegiatans->firstItem() }}-{{ $pendaftaranKegiatans->lastItem() }}</strong>
                                        dari <strong>{{ $pendaftaranKegiatans->total() }}</strong> pendaftaran
                                    @else
                                        Menampilkan <strong>0</strong> pendaftaran
                                    @endif
                                </div>

                                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                    <a href="{{ $pendaftaranKegiatans->previousPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $pendaftaranKegiatans->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                        ‹ Sebelumnya
                                    </a>
                                    <a href="{{ $pendaftaranKegiatans->nextPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $pendaftaranKegiatans->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
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
