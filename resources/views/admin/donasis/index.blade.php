@extends('layouts.app')

@section('content')
    @php
        $formatCurrency = function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        $statusStyles = [
            'pending' => ['label' => 'Pending', 'classes' => 'bg-slate-100 text-slate-800'],
            'berhasil' => ['label' => 'Selesai', 'classes' => 'bg-emerald-500 text-white'],
            'gagal' => ['label' => 'Dibatalkan', 'classes' => 'bg-red-100 text-red-700'],
        ];
    @endphp

    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-slate-100">
            <div class="flex flex-col sm:gap-4 sm:py-4">
                <header
                    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Donasi</h1>
                            <p class="text-gray-600">Manajemen transaksi dan kampanye donasi masjid</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.transaksi-donasis.create') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8M8 12h8" />
                                </svg>
                                Tambah Donasi Manual
                            </a>
                            <a href="{{ route('admin.donasis.create') }}"
                                class="inline-flex items-center gap-2 rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-white">
                                Kampanye Baru
                            </a>
                        </div>
                    </div>
                </header>

                <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                            <x-admin.stat-card label="Total Donasi" :value="$stats['total']['value']" icon="dollar"
                                :format="$stats['total']['format']" :description="$stats['total']['description']" />
                            <x-admin.stat-card label="Donasi Pending" :value="$stats['pending']['value']" icon="hourglass"
                                :format="$stats['pending']['format']" :description="$stats['pending']['description']" />
                            <x-admin.stat-card label="Donasi Selesai" :value="$stats['completed']['value']" icon="check-circle"
                                :format="$stats['completed']['format']" :description="$stats['completed']['description']" />
                            <x-admin.stat-card label="Donatur Unik" :value="$stats['unique']['value']" icon="users"
                                :format="$stats['unique']['format']" :description="$stats['unique']['description']" />
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="px-6 pt-6">
                                <h2 class="text-base font-semibold text-slate-900">Filter Donasi</h2>
                                <p class="text-sm text-slate-500">
                                    Saring transaksi donasi berdasarkan kriteria tertentu.
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
                                    <input type="search" name="q" placeholder="Cari nama donatur atau kampanye..."
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

                                <select name="donasi_id"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[220px]">
                                    <option value="">Kampanye</option>
                                    @foreach ($campaignOptions as $option)
                                        <option value="{{ $option->id }}" @selected(($filters['donasi_id'] ?? '') == $option->id)>
                                            {{ $option->judul }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="waktu"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                    <option value="">Waktu Donasi</option>
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
                                        <a href="{{ route('admin.donasis.index') }}"
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
                                            d="M12 3v18M7 8c0-1.66 2.24-3 5-3s5 1.34 5 3-2.24 3-5 3-5 1.34-5 3 2.24 3 5 3 5-1.34 5-3" />
                                    </svg>
                                    <h2 class="text-base font-semibold text-slate-900">
                                        Daftar Donasi
                                    </h2>
                                </div>
                                <p class="text-sm text-slate-500">
                                    @if ($transactions->total())
                                        Menampilkan {{ $transactions->count() }} dari
                                        {{ number_format($transactions->total()) }} transaksi donasi
                                    @else
                                        Belum ada transaksi donasi
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
                                                    Nama Donatur
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Kampanye
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                                                    Jumlah
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">
                                                    Status
                                                </th>
                                                <th
                                                    class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 lg:table-cell">
                                                    Tanggal
                                                </th>
                                                <th
                                                    class="h-10 px-2 text-right align-middle text-xs font-medium text-slate-500">
                                                    <span class="sr-only">Actions</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="[&_tr:last-child]:border-0">
                                            @forelse ($transactions as $transaction)
                                                @php
                                                    $appearance = $statusStyles[$transaction->status_pembayaran] ?? $statusStyles['pending'];
                                                @endphp
                                                <tr class="border-b">
                                                    <td class="p-2 align-middle">
                                                        <div class="font-medium text-slate-900">
                                                            {{ $transaction->user->name ?? 'Pengguna Terhapus' }}
                                                        </div>
                                                        <div class="text-xs text-slate-500 md:hidden">
                                                            {{ $transaction->donasi->judul }}
                                                        </div>
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $transaction->donasi->judul }}
                                                    </td>
                                                    <td class="hidden p-2 align-middle md:table-cell">
                                                        {{ $formatCurrency($transaction->jumlah) }}
                                                    </td>
                                                    <td class="p-2 align-middle">
                                                        <span
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $appearance['classes'] }}">
                                                            {{ $appearance['label'] }}
                                                        </span>
                                                    </td>
                                                    <td class="hidden p-2 align-middle lg:table-cell">
                                                        {{ $transaction->created_at->translatedFormat('d M Y H:i') }}
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
                                                                class="absolute right-0 mt-2 w-48 rounded-md border border-slate-200 bg-white text-sm text-slate-700 shadow-lg z-20">
                                                                <div
                                                                    class="px-3 py-2 text-xs font-semibold text-slate-500">
                                                                    Actions
                                                                </div>
                                                                <a href="{{ route('admin.transaksi-donasis.show', $transaction) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Lihat Detail
                                                                </a>
                                                                <a href="{{ route('admin.transaksi-donasis.edit', $transaction) }}"
                                                                    class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                                                    Ubah Status
                                                                </a>
                                                                <form action="{{ route('admin.transaksi-donasis.destroy', $transaction) }}"
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
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                                <div class="text-xs text-slate-500">
                                    @if ($transactions->total())
                                        Menampilkan <strong>{{ $transactions->firstItem() }}-{{ $transactions->lastItem() }}</strong>
                                        dari <strong>{{ $transactions->total() }}</strong> transaksi
                                    @else
                                        Menampilkan <strong>0</strong> transaksi
                                    @endif
                                </div>

                                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                                    <a href="{{ $transactions->previousPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $transactions->onFirstPage() ? 'text-slate-300 cursor-not-allowed' : 'text-slate-700 hover:bg-slate-50' }}">
                                        ‹ Sebelumnya
                                    </a>
                                    <a href="{{ $transactions->nextPageUrl() ?? '#' }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs {{ $transactions->hasMorePages() ? 'text-slate-700 hover:bg-slate-50' : 'text-slate-300 cursor-not-allowed' }}">
                                        Selanjutnya ›
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="px-6 pt-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-base font-semibold text-slate-900">Kampanye Donasi</h2>
                                        <p class="text-sm text-slate-500">Kelola target dan dana terkumpul setiap kampanye.</p>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-500">
                                        {{ number_format($campaigns->total()) }} kampanye
                                    </span>
                                </div>
                            </div>
                            <div class="grid gap-4 px-6 py-6 lg:grid-cols-2">
                                @forelse ($campaigns as $campaign)
                                    @php
                                        $progress = min(100, ($campaign->dana_terkumpul / max(1, $campaign->target_dana)) * 100);
                                    @endphp
                                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    {{ $campaign->judul }}
                                                </h3>
                                                <p class="text-xs text-slate-500">
                                                    Target {{ $formatCurrency($campaign->target_dana) }}
                                                </p>
                                            </div>
                                            @if ($campaign->tanggal_selesai)
                                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                                    Selesai {{ $campaign->tanggal_selesai->translatedFormat('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-4 space-y-2">
                                            <div class="flex items-center justify-between text-sm text-slate-600">
                                                <span>{{ $formatCurrency($campaign->dana_terkumpul) }}</span>
                                                <span>{{ number_format($progress, 0) }}%</span>
                                            </div>
                                            <div class="h-2 rounded-full bg-slate-100">
                                                <div class="h-full rounded-full bg-emerald-500 transition-all"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                                            <a href="{{ route('admin.donasis.show', $campaign) }}" class="text-blue-600">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.donasis.edit', $campaign) }}" class="text-emerald-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.donasis.recalc', $campaign) }}" method="POST"
                                                class="inline-flex" onsubmit="return confirm('Sinkronkan dana terkumpul?')">
                                                @csrf
                                                <button type="submit" class="text-sky-600">
                                                    Sinkron Dana
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.donasis.destroy', $campaign) }}" method="POST"
                                                class="inline-flex" onsubmit="return confirm('Hapus kampanye ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full rounded-xl border border-dashed border-slate-200 p-10 text-center">
                                        <p class="text-sm text-slate-500">Belum ada kampanye donasi yang dibuat.</p>
                                        <a href="{{ route('admin.donasis.create') }}"
                                            class="mt-3 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                                            Buat Kampanye Pertama
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                            <div class="border-t border-slate-200 px-6 py-4">
                                {{ $campaigns->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
