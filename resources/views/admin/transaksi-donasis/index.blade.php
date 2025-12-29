@extends('layouts.app')

@section('content')
    @php
        $formatCurrency = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $formatPercent = fn ($value) => ($value >= 0 ? '+' : '') . number_format($value, 1) . '%';
        $filters = $filters ?? [];
    @endphp

    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Transaksi Donasi</h1>
                            <p class="text-gray-600">Pantau aliran dana donasi jamaah</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.transaksi-donasis.create') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                <span class="text-base">＋</span>
                                Tambah Transaksi
                            </a>
                        </div>
                    </div>
                </header>

                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                            <x-admin.stat-card label="Donasi Berhasil (Bulan Ini)" :value="$stats['total']['value']" icon="dollar"
                                :format="'currency'" :description="$formatPercent($stats['total']['growth']) . ' dibanding bulan lalu'" />
                            <x-admin.stat-card label="Transaksi Pending" :value="$stats['pending']" icon="hourglass"
                                description="Menunggu pembayaran" />
                            <x-admin.stat-card label="Transaksi Berhasil" :value="$stats['success']" icon="check-circle"
                                description="Telah dikonfirmasi" />
                            <x-admin.stat-card label="Donatur Unik" :value="$stats['unique']" icon="users"
                                description="Jumlah jamaah berdonasi" />
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="p-6 pb-2">
                                <h2 class="text-base font-semibold leading-none tracking-tight text-slate-900">Filter Transaksi</h2>
                                <p class="text-sm text-slate-500">
                                    Saring transaksi berdasarkan kriteria tertentu.
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
                                    <input type="search" name="q" placeholder="Cari donatur atau kampanye..."
                                        value="{{ $filters['q'] ?? '' }}"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10" />
                                </div>

                                <select name="status"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[200px]">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>

                                <select name="donasi_id"
                                    class="w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/10 md:w-[240px]">
                                    <option value="">Semua Kampanye</option>
                                    @foreach ($donasis as $donasi)
                                        <option value="{{ $donasi->id }}" @selected(($filters['donasi_id'] ?? '') == $donasi->id)>
                                            {{ $donasi->judul }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="flex w-full gap-2 md:w-auto">
                                    <button type="submit"
                                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 md:flex-none">
                                        Terapkan
                                    </button>
                                    @if (array_filter($filters))
                                        <a href="{{ route('admin.transaksi-donasis.index') }}"
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
                                    <span class="text-lg">💳</span>
                                    <h2 class="text-base font-semibold leading-none tracking-tight">
                                        Daftar Transaksi
                                    </h2>
                                </div>
                                <p class="text-sm text-slate-500">
                                    @if ($transaksis->total())
                                        Menampilkan {{ $transaksis->count() }} dari {{ number_format($transaksis->total()) }} transaksi
                                    @else
                                        Belum ada data transaksi
                                    @endif
                                </p>
                            </div>

                            <div class="overflow-x-auto px-6 py-4">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b">
                                        <tr class="border-b">
                                            <th class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                Donatur
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                Kampanye
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                Jumlah
                                            </th>
                                            <th class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                Status
                                            </th>
                                            <th class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 lg:table-cell">
                                                Tanggal
                                            </th>
                                            <th class="h-10 px-2 text-right align-middle text-xs font-medium text-slate-500">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @php
                                            $statusStyles = [
                                                'pending' => ['label' => 'Pending', 'classes' => 'bg-slate-100 text-slate-800'],
                                                'berhasil' => ['label' => 'Berhasil', 'classes' => 'bg-emerald-500 text-white'],
                                                'gagal' => ['label' => 'Gagal', 'classes' => 'bg-red-100 text-red-700'],
                                            ];
                                        @endphp
                                        @forelse ($transaksis as $transaksi)
                                            @php
                                                $appearance = $statusStyles[$transaksi->status_pembayaran] ?? $statusStyles['pending'];
                                            @endphp
                                            <tr class="border-b">
                                                <td class="p-2 align-middle">
                                                    <div class="font-medium text-slate-900">
                                                        {{ $transaksi->user->name ?? 'Pengguna Terhapus' }}
                                                    </div>
                                                    <div class="text-xs text-slate-500 md:hidden">
                                                        {{ $transaksi->donasi->judul }}
                                                    </div>
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $transaksi->donasi->judul }}
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $formatCurrency($transaksi->jumlah) }}
                                                </td>
                                                <td class="p-2 align-middle">
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                        {{ $appearance['label'] }}
                                                    </span>
                                                </td>
                                                <td class="hidden p-2 align-middle lg:table-cell">
                                                    {{ $transaksi->created_at->translatedFormat('d M Y H:i') }}
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
                                                            <a href="{{ route('admin.transaksi-donasis.show', $transaksi) }}"
                                                                class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                Lihat Detail
                                                            </a>
                                                            <a href="{{ route('admin.transaksi-donasis.edit', $transaksi) }}"
                                                                class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                Ubah Status
                                                            </a>
                                                            <form action="{{ route('admin.transaksi-donasis.destroy', $transaksi) }}"
                                                                method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
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
                                                    Tidak ada transaksi donasi sesuai filter saat ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                <div class="text-xs text-slate-500">
                                    @if ($transaksis->total())
                                        Menampilkan <strong>{{ $transaksis->firstItem() }}-{{ $transaksis->lastItem() }}</strong>
                                        dari <strong>{{ $transaksis->total() }}</strong> transaksi
                                    @else
                                        Menampilkan <strong>0</strong> transaksi
                                    @endif
                                </div>

                                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                    <a href="{{ $transaksis->previousPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $transaksis->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                        ‹ Sebelumnya
                                    </a>
                                    <a href="{{ $transaksis->nextPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $transaksis->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
                                        Berikutnya ›
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
