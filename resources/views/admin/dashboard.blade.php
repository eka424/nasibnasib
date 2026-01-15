@extends('layouts.app')

@section('content')
@php
  $formatCurrency = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
  $formatPercent = fn ($value) => ($value >= 0 ? '+' : '') . number_format((float) $value, 1) . '%';
@endphp

<div class="min-h-screen bg-slate-100">
  <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div class="min-w-0">
        <h1 class="text-xl font-bold text-slate-900">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan donasi, pendaftaran, kegiatan, dan tanya ustadz.</p>
      </div>

      <div class="flex flex-wrap gap-2">
        @if (\Illuminate\Support\Facades\Route::has('admin.finance.transaksi.index'))
          <a href="{{ route('admin.finance.transaksi.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Kelola Keuangan
          </a>
        @endif

        @if (\Illuminate\Support\Facades\Route::has('admin.finance.year-balance.index'))
          <a href="{{ route('admin.finance.year-balance.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Input Sisa Kas
          </a>
        @endif

        @if (\Illuminate\Support\Facades\Route::has('admin.struktur.index'))
          <a href="{{ route('admin.struktur.index') }}"
             class="inline-flex items-center rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
            Struktur Pengurus
          </a>
        @endif

        @if (\Illuminate\Support\Facades\Route::has('admin.sedekah.dashboard'))
          <a href="{{ route('admin.sedekah.dashboard') }}"
             class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Sedekah Masjid
          </a>
        @endif
      </div>
    </div>

    {{-- CARDS --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs font-semibold text-slate-500">Total Donasi (Bulan Ini)</div>
        <div class="mt-2 text-2xl font-bold text-slate-900 break-words">{{ $formatCurrency($stats['donations']['value'] ?? 0) }}</div>
        <div class="mt-1 text-xs text-slate-500">{{ $formatPercent($stats['donations']['growth'] ?? 0) }} vs bulan lalu</div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs font-semibold text-slate-500">Pendaftaran (Bulan Ini)</div>
        <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format((int)($stats['registrations']['value'] ?? 0)) }}</div>
        <div class="mt-1 text-xs text-slate-500">{{ $formatPercent($stats['registrations']['growth'] ?? 0) }} vs bulan lalu</div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs font-semibold text-slate-500">Kegiatan Mendatang</div>
        <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format((int)($stats['events'] ?? 0)) }}</div>
        <div class="mt-1 text-xs text-slate-500">Mulai dari hari ini</div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs font-semibold text-slate-500">Pertanyaan Menunggu</div>
        <div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format((int)($stats['questions'] ?? 0)) }}</div>
        <div class="mt-1 text-xs text-slate-500">Menunggu jawaban ustadz</div>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">

      {{-- DONASI TERBARU --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
          <h2 class="text-base font-bold text-slate-900">Donasi Terbaru</h2>
          <p class="text-sm text-slate-500">5 transaksi donasi terakhir.</p>
        </div>

        {{-- trik biar mobile nggak “ngegeser” layar: negative margin + padding dalam --}}
        <div class="-mx-4 overflow-x-auto sm:mx-0">
          <div class="min-w-full px-4 sm:px-0">
            <table class="w-full min-w-[720px] text-sm">
              <thead class="border-b border-slate-200 bg-slate-50">
                <tr class="text-left">
                  <th class="px-4 py-3 text-xs font-semibold text-slate-600">Donatur</th>
                  <th class="px-4 py-3 text-xs font-semibold text-slate-600">Kampanye</th>

                  {{-- status tetap tampil di mobile --}}
                  <th class="px-4 py-3 text-xs font-semibold text-slate-600">Status</th>

                  <th class="px-4 py-3 text-xs font-semibold text-slate-600 text-right">Jumlah</th>

                  {{-- kolom waktu disembunyikan di layar kecil biar nggak terlalu lebar --}}
                  <th class="hidden px-4 py-3 text-xs font-semibold text-slate-600 sm:table-cell">Waktu</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($latestDonations as $d)
                  @php
                    $cls = match($d->status_pembayaran) {
                      'berhasil' => 'bg-emerald-100 text-emerald-700',
                      'pending' => 'bg-amber-100 text-amber-800',
                      default => 'bg-rose-100 text-rose-700',
                    };
                  @endphp

                  <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 align-top">
                      <div class="max-w-[240px] truncate font-medium text-slate-900">
                        {{ optional($d->user)->name ?? 'Anonim' }}
                      </div>
                      <div class="max-w-[240px] truncate text-xs text-slate-500">
                        {{ optional($d->user)->email ?? '-' }}
                      </div>
                    </td>

                    <td class="px-4 py-3 align-top">
                      <span class="inline-flex max-w-[220px] truncate items-center rounded-full border border-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                        {{ optional($d->donasi)->judul ?? '-' }}
                      </span>
                    </td>

                    <td class="px-4 py-3 align-top">
                      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $cls }}">
                        {{ strtoupper($d->status_pembayaran) }}
                      </span>

                      {{-- waktu dipindah ke bawah status untuk mobile --}}
                      <div class="mt-2 text-xs text-slate-500 sm:hidden">
                        {{ optional($d->created_at)->translatedFormat('d M Y H:i') }}
                      </div>
                    </td>

                    <td class="px-4 py-3 align-top text-right font-semibold text-slate-900 whitespace-nowrap">
                      {{ $formatCurrency($d->jumlah) }}
                    </td>

                    <td class="hidden px-4 py-3 align-top text-slate-600 sm:table-cell whitespace-nowrap">
                      {{ optional($d->created_at)->translatedFormat('d M Y H:i') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada donasi.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- PENDAFTARAN TERBARU --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
          <h2 class="text-base font-bold text-slate-900">Pendaftaran Terbaru</h2>
          <p class="text-sm text-slate-500">5 pendaftaran kegiatan terakhir.</p>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse ($latestRegistrations as $r)
            <div class="px-5 py-4">
              <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between sm:gap-3">
                <div class="min-w-0">
                  <div class="truncate font-semibold text-slate-900">{{ optional($r->user)->name ?? '—' }}</div>
                  <div class="mt-1 text-sm text-slate-500 break-words">
                    Mendaftar: {{ optional($r->kegiatan)->nama_kegiatan ?? '-' }}
                  </div>
                </div>

                <div class="text-sm text-slate-600 sm:text-right whitespace-nowrap">
                  {{ optional($r->created_at)->diffForHumans() }}
                </div>
              </div>
            </div>
          @empty
            <div class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pendaftaran.</div>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
