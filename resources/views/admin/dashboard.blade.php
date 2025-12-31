@extends('layouts.app')

@section('content')
@php
  $formatCurrency = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
  $formatPercent = fn ($value) => ($value >= 0 ? '+' : '') . number_format((float) $value, 1) . '%';
@endphp

<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-7xl px-4 py-6 md:px-6">

    <div class="flex items-end justify-between">
      <div>
        <h1 class="text-xl font-bold text-slate-900">Dashboard Admin</h1>
        <p class="text-sm text-slate-500">Ringkasan donasi, pendaftaran, kegiatan, dan tanya ustadz.</p>
      </div>

      <div class="flex gap-2">
        @if (\Illuminate\Support\Facades\Route::has('admin.sedekah.dashboard'))
          <a href="{{ route('admin.sedekah.dashboard') }}"
             class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Sedekah Masjid
          </a>
        @endif
      </div>
    </div>

    {{-- CARDS --}}
    <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="text-xs font-semibold text-slate-500">Total Donasi (Bulan Ini)</div>
        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $formatCurrency($stats['donations']['value'] ?? 0) }}</div>
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

    <div class="mt-6 grid gap-4 lg:grid-cols-2">

      {{-- DONASI TERBARU --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
          <h2 class="text-base font-bold text-slate-900">Donasi Terbaru</h2>
          <p class="text-sm text-slate-500">5 transaksi donasi terakhir.</p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="border-b border-slate-200 bg-slate-50">
              <tr class="text-left">
                <th class="px-4 py-3 text-xs font-semibold text-slate-600">Donatur</th>
                <th class="px-4 py-3 text-xs font-semibold text-slate-600">Kampanye</th>
                <th class="px-4 py-3 text-xs font-semibold text-slate-600">Status</th>
                <th class="px-4 py-3 text-xs font-semibold text-slate-600 text-right">Jumlah</th>
                <th class="px-4 py-3 text-xs font-semibold text-slate-600">Waktu</th>
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
                  <td class="px-4 py-3">
                    <div class="font-medium text-slate-900">{{ optional($d->user)->name ?? 'Anonim' }}</div>
                    <div class="text-xs text-slate-500">{{ optional($d->user)->email ?? '-' }}</div>
                  </td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center rounded-full border border-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                      {{ optional($d->donasi)->judul ?? '-' }}
                    </span>
                  </td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $cls }}">
                      {{ strtoupper($d->status_pembayaran) }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $formatCurrency($d->jumlah) }}</td>
                  <td class="px-4 py-3 text-slate-600">{{ optional($d->created_at)->translatedFormat('d M Y H:i') }}</td>
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

      {{-- PENDAFTARAN TERBARU --}}
      <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
          <h2 class="text-base font-bold text-slate-900">Pendaftaran Terbaru</h2>
          <p class="text-sm text-slate-500">5 pendaftaran kegiatan terakhir.</p>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse ($latestRegistrations as $r)
            <div class="px-5 py-4">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <div class="font-semibold text-slate-900">{{ optional($r->user)->name ?? '—' }}</div>
                  <div class="mt-1 text-sm text-slate-500">
                    Mendaftar: {{ optional($r->kegiatan)->nama_kegiatan ?? '-' }}
                  </div>
                </div>
                <div class="text-right text-sm text-slate-600">
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
