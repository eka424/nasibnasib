@extends('layouts.app')

@section('content')
    @php
        $formatCurrency = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $formatPercent = fn ($value) => ($value >= 0 ? '+' : '') . number_format($value, 1) . '%';
        $navLinks = [
            ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => true],
            ['label' => 'Kegiatan', 'route' => route('admin.kegiatans.index')],
            ['label' => 'Donasi', 'route' => route('admin.donasis.index')],
            ['label' => 'Perpustakaan', 'route' => route('admin.perpustakaans.index')],
            ['label' => 'Galeri', 'route' => route('admin.galeris.index')],
            ['label' => 'Tanya Ustadz', 'route' => route('admin.moderasi.index')],
        ];
    @endphp

    <div class="flex min-h-screen w-full flex-col bg-slate-100">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b bg-white px-4 md:px-6">
            <nav class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-lg font-semibold md:text-base">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded bg-slate-900 text-white text-xs font-bold">WM</span>
                    <span class="sr-only">WebMasjid</span>
                </a>
                @foreach ($navLinks as $link)
                    @php $isActive = $link['active'] ?? false; @endphp
                    <a href="{{ $link['route'] }}"
                        class="{{ $isActive ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <button type="button"
                class="inline-flex items-center justify-center rounded-md border bg-white px-2 py-2 text-sm font-medium text-slate-900 shadow-sm hover:bg-slate-50 md:hidden">
                <span class="sr-only">Toggle navigation menu</span>
                ☰
            </button>

            <div class="flex w-full items-center gap-4 md:ml-auto md:gap-2 lg:gap-4">
                <form class="ml-auto flex-1 sm:flex-initial">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-2.5 top-2.5 text-xs text-slate-400">🔍</span>
                        <input type="search" placeholder="Cari apa saja..."
                            class="h-9 w-full rounded-md border border-slate-200 bg-white px-8 text-sm shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-slate-900/20 sm:w-[300px] md:w-[200px] lg:w-[300px]" />
                    </div>
                </form>

                <button type="button"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-slate-200 bg-white text-xs font-medium text-slate-900 shadow-sm hover:bg-slate-50">
                    ⬇
                    <span class="sr-only">Download Laporan</span>
                </button>

                <button type="button"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-800 text-sm font-medium shadow-sm">
                    <span class="sr-only">User menu</span>
                    👤
                </button>
            </div>
        </header>

        <main class="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border bg-white text-slate-900 shadow-sm">
                    <div class="flex flex-row items-center justify-between border-b px-4 pb-2 pt-4">
                        <h3 class="text-sm font-medium">Total Donasi (Bulan Ini)</h3>
                        <span class="text-lg text-slate-400">💰</span>
                    </div>
                    <div class="space-y-1 px-4 pb-4 pt-2">
                        <div class="text-2xl font-bold">{{ $formatCurrency($stats['donations']['value']) }}</div>
                        <p class="text-xs text-slate-500">{{ $formatPercent($stats['donations']['growth']) }} vs bulan lalu</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-white text-slate-900 shadow-sm">
                    <div class="flex flex-row items-center justify-between border-b px-4 pb-2 pt-4">
                        <h3 class="text-sm font-medium">Pendaftar Baru (Bulan Ini)</h3>
                        <span class="text-lg text-slate-400">👥</span>
                    </div>
                    <div class="space-y-1 px-4 pb-4 pt-2">
                        <div class="text-2xl font-bold">+{{ number_format($stats['registrations']['value']) }}</div>
                        <p class="text-xs text-slate-500">{{ $formatPercent($stats['registrations']['growth']) }} vs bulan lalu</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-white text-slate-900 shadow-sm">
                    <div class="flex flex-row items-center justify-between border-b px-4 pb-2 pt-4">
                        <h3 class="text-sm font-medium">Kegiatan Mendatang</h3>
                        <span class="text-lg text-slate-400">📢</span>
                    </div>
                    <div class="space-y-1 px-4 pb-4 pt-2">
                        <div class="text-2xl font-bold">{{ number_format($stats['events']) }}</div>
                        <p class="text-xs text-slate-500">Kegiatan dijadwalkan</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-white text-slate-900 shadow-sm">
                    <div class="flex flex-row items-center justify-between border-b px-4 pb-2 pt-4">
                        <h3 class="text-sm font-medium">Pertanyaan Baru</h3>
                        <span class="text-lg text-slate-400">❓</span>
                    </div>
                    <div class="space-y-1 px-4 pb-4 pt-2">
                        <div class="text-2xl font-bold">{{ number_format($stats['questions']) }}</div>
                        <p class="text-xs text-slate-500">Menunggu jawaban ustadz</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:gap-8 lg:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-lg border bg-white text-slate-900 shadow-sm xl:col-span-2">
                    <div class="flex items-center border-b px-4 py-4">
                        <div class="grid gap-1">
                            <h3 class="text-base font-semibold">Donasi Terbaru</h3>
                            <p class="text-sm text-slate-500">Daftar donasi terbaru yang masuk.</p>
                        </div>
                        <a href="{{ route('admin.transaksi-donasis.index') }}"
                            class="ml-auto inline-flex items-center gap-1 rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-slate-800">
                            Lihat Semua
                            <span>↗</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto px-4 pb-4 pt-2">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="border-b">
                                <tr class="text-left">
                                    <th class="h-10 py-2 text-xs font-medium text-slate-500">Donatur</th>
                                    <th class="hidden h-10 py-2 text-xs font-medium text-slate-500 xl:table-cell">Kampanye</th>
                                    <th class="hidden h-10 py-2 text-xs font-medium text-slate-500 xl:table-cell">Status</th>
                                    <th class="hidden h-10 py-2 text-xs font-medium text-slate-500 xl:table-cell">Tanggal</th>
                                    <th class="h-10 py-2 text-right text-xs font-medium text-slate-500">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestDonations as $donation)
                                    @php
                                        $statusClasses = match ($donation->status_pembayaran) {
                                            'berhasil' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-slate-100 text-slate-700',
                                            default => 'bg-red-100 text-red-700',
                                        };
                                    @endphp
                                    <tr class="border-b last:border-0">
                                        <td class="py-3 align-middle">
                                            <div class="font-medium">{{ optional($donation->user)->name ?? 'Anonim' }}</div>
                                            <div class="hidden text-xs text-slate-500 md:inline">
                                                {{ optional($donation->user)->email ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="hidden py-3 align-middle xl:table-cell">
                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold">
                                                {{ optional($donation->donasi)->judul ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="hidden py-3 align-middle xl:table-cell">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $statusClasses }}">
                                                {{ ucfirst($donation->status_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="hidden py-3 align-middle md:table-cell lg:hidden xl:table-cell">
                                            {{ $donation->created_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="py-3 text-right align-middle">{{ $formatCurrency($donation->jumlah) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-sm text-slate-500">Belum ada donasi terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-lg border bg-white text-slate-900 shadow-sm">
                    <div class="border-b px-4 py-4">
                        <h3 class="text-base font-semibold">Pendaftaran Terbaru</h3>
                        <p class="text-sm text-slate-500">Pendaftar baru untuk kegiatan mendatang.</p>
                    </div>
                    <div class="grid gap-6 px-4 py-4">
                        @forelse ($latestRegistrations as $registration)
                            <div class="flex items-center gap-4">
                                <div class="hidden h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-slate-100 text-xs font-semibold text-slate-600 sm:flex">
                                    {{ strtoupper(substr(optional($registration->user)->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">{{ optional($registration->user)->name ?? '—' }}</p>
                                    <p class="text-sm text-slate-500">
                                        Mendaftar untuk {{ optional($registration->kegiatan)->nama_kegiatan ?? '-' }}
                                    </p>
                                </div>
                                <div class="ml-auto text-sm font-medium text-emerald-600">
                                    {{ $registration->created_at->diffForHumans(null, true) }} lalu
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada pendaftaran terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
