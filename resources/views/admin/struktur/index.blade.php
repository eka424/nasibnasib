@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-7xl px-4 py-6 md:px-6">

    <div class="flex flex-wrap items-end justify-between gap-3">
      <div>
        <h1 class="text-xl font-bold text-slate-900">Struktur Pengurus</h1>
        <p class="text-sm text-slate-500">Kelola periode, publish, set aktif, dan masuk ke builder.</p>
      </div>

      <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.struktur.create') }}"
           class="inline-flex items-center rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
          + Periode Baru
        </a>

        @if (\Illuminate\Support\Facades\Route::has('admin.dashboard'))
          <a href="{{ route('admin.dashboard') }}"
             class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali Dashboard
          </a>
        @endif
      </div>
    </div>

    @if(session('ok'))
      <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('ok') }}
      </div>
    @endif

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="border-b border-slate-200 bg-slate-50">
          <tr class="text-left">
            <th class="px-4 py-3 text-xs font-semibold text-slate-600">Judul</th>
            <th class="px-4 py-3 text-xs font-semibold text-slate-600">Periode</th>
            <th class="px-4 py-3 text-xs font-semibold text-slate-600">Status</th>
            <th class="px-4 py-3 text-xs font-semibold text-slate-600 text-right">Aksi</th>
          </tr>
        </thead>

        <tbody>
          @forelse($terms as $t)
            @php
              $badge = $t->is_active
                ? 'bg-emerald-100 text-emerald-700'
                : ($t->status === 'published'
                    ? 'bg-sky-100 text-sky-700'
                    : 'bg-slate-100 text-slate-700');
            @endphp

            <tr class="border-t border-slate-100">
              <td class="px-4 py-3">
                <div class="font-semibold text-slate-900">{{ $t->title }}</div>
                <div class="text-xs text-slate-500">
                  {{ $t->decision_number ? 'No: '.$t->decision_number : '' }}
                  {{ $t->decision_date_masehi ? ' • '.$t->decision_date_masehi->translatedFormat('d M Y') : '' }}
                </div>
              </td>

              <td class="px-4 py-3 text-slate-700">
                {{ $t->period_label ?? '-' }}
              </td>

              <td class="px-4 py-3">
                <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $badge }}">
                  {{ $t->is_active ? 'AKTIF' : strtoupper($t->status) }}
                </span>
              </td>

              <td class="px-4 py-3 text-right">
                <div class="flex flex-wrap justify-end gap-2">
                  <a href="{{ route('admin.struktur.builder', $t) }}"
                     class="inline-flex items-center rounded-xl bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-600">
                    Builder
                  </a>

                  <a href="{{ route('admin.struktur.edit', $t) }}"
                     class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                    Edit
                  </a>

                  <form method="POST" action="{{ route('admin.struktur.publish', $t) }}">
                    @csrf
                    <button class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                      Publish
                    </button>
                  </form>

                  <form method="POST" action="{{ route('admin.struktur.setActive', $t) }}">
                    @csrf
                    <button class="inline-flex items-center rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                      Set Aktif
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                Belum ada periode. Klik <b>+ Periode Baru</b>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
