@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-7xl px-4 py-6 md:px-6">

    <div class="flex flex-wrap items-end justify-between gap-3">
      <div>
        <h1 class="text-xl font-bold text-slate-900">Builder Struktur</h1>
        <p class="text-sm text-slate-500">
          {{ $term->title }} • Periode {{ $term->period_label ?? '-' }}
        </p>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('admin.struktur.index') }}"
           class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
          Kembali
        </a>
      </div>
    </div>

    @if(session('ok'))
      <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('ok') }}
      </div>
    @endif

    <div class="mt-6 grid gap-4 lg:grid-cols-12">

      {{-- LEFT: TREE --}}
      <div class="lg:col-span-7 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
          <h2 class="text-base font-bold text-slate-900">Struktur Saat Ini</h2>
          <p class="text-sm text-slate-500">Unit → Jabatan → Anggota</p>
        </div>

        <div class="p-5 space-y-3">

          @php
            $renderUnit = function ($parentId, $level = 0) use (&$renderUnit, $childrenByParent, $positionsByUnit, $membersByPosition) {
              $key = $parentId ?? 0;
              $items = $childrenByParent[$key] ?? collect();

              foreach ($items as $u) {
                $pad = $level * 18;
                echo '<div class="rounded-xl border border-slate-200 bg-slate-50 p-4" style="margin-left:'.$pad.'px">';
                echo '<div class="flex items-center justify-between gap-3">';
                echo '<div>';
                echo '<div class="font-semibold text-slate-900">'.$u->name.' <span class="text-xs text-slate-500">('.$u->type.')</span></div>';
                echo '<div class="text-xs text-slate-500">Unit ID: '.$u->id.' • Sort: '.$u->sort_order.'</div>';
                echo '</div>';
                echo '</div>';

                $pos = $positionsByUnit[$u->id] ?? collect();
                if ($pos->count()) {
                  echo '<div class="mt-3 space-y-2">';
                  foreach ($pos as $p) {
                    echo '<div class="rounded-xl border border-slate-200 bg-white px-4 py-3">';
                    echo '<div class="flex items-center justify-between gap-3">';
                    echo '<div>';
                    echo '<div class="font-semibold text-slate-900">'.$p->title.' <span class="text-xs text-slate-500">'.e($p->notes ?? '').'</span></div>';
                    echo '<div class="text-xs text-slate-500">Jabatan ID: '.$p->id.' • Sort: '.$p->sort_order.'</div>';
                    echo '</div>';
                    echo '</div>';

                    $mem = $membersByPosition[$p->id] ?? collect();
                    if ($mem->count()) {
                      echo '<div class="mt-2 grid gap-1">';
                      foreach ($mem as $m) {
                        echo '<div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">';
                        echo '<span class="text-slate-800">'.$m->name.'</span>';
                        echo '<span class="text-xs text-slate-500">ID '.$m->id.' • Sort '.$m->sort_order.'</span>';
                        echo '</div>';
                      }
                      echo '</div>';
                    } else {
                      echo '<div class="mt-2 text-xs text-slate-500">Belum ada anggota.</div>';
                    }

                    echo '</div>';
                  }
                  echo '</div>';
                } else {
                  echo '<div class="mt-3 text-xs text-slate-500">Belum ada jabatan.</div>';
                }

                echo '</div>';

                $renderUnit($u->id, $level + 1);
              }
            };
          @endphp

          @if(($childrenByParent[0] ?? collect())->count() === 0)
            <div class="text-sm text-slate-500">Belum ada struktur. Tambahkan Unit dulu.</div>
          @else
            {!! $renderUnit(null, 0) !!}
          @endif

        </div>
      </div>

      {{-- RIGHT: FORMS --}}
      <div class="lg:col-span-5 space-y-4">

        {{-- Add Unit --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-bold text-slate-900">Tambah Unit</h2>
            <p class="text-sm text-slate-500">Contoh: DEWAN SYURO / BIDANG IMARAH / SEKSI KESEHATAN</p>
          </div>
          <form class="p-5 space-y-3" method="POST" action="{{ route('admin.units.store') }}">
            @csrf
            <input type="hidden" name="term_id" value="{{ $term->id }}">

            <div>
              <label class="text-xs font-semibold text-slate-600">Parent Unit (opsional)</label>
              <select name="parent_id" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                <option value="">— Tidak ada (root) —</option>
                @foreach($units as $u)
                  <option value="{{ $u->id }}">{{ $u->name }} (ID {{ $u->id }})</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-600">Nama Unit</label>
              <input name="name" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm"
                     placeholder="Contoh: BIDANG IMARAH" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-600">Tipe</label>
                <select name="type" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                  <option value="group">group</option>
                  <option value="field">field</option>
                  <option value="section">section</option>
                  <option value="other">other</option>
                </select>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-600">Sort</label>
                <input type="number" name="sort_order" value="0"
                       class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
              </div>
            </div>

            <button class="w-full rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
              Simpan Unit
            </button>
          </form>
        </div>

        {{-- Add Position --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-bold text-slate-900">Tambah Jabatan</h2>
            <p class="text-sm text-slate-500">Contoh: RAIS, KETUA UMUM, ANGGOTA</p>
          </div>
          <form class="p-5 space-y-3" method="POST" action="{{ route('admin.positions.store') }}">
            @csrf
            <div>
              <label class="text-xs font-semibold text-slate-600">Pilih Unit</label>
              <select name="unit_id" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm" required>
                <option value="">— pilih unit —</option>
                @foreach($units as $u)
                  <option value="{{ $u->id }}">{{ $u->name }} (ID {{ $u->id }})</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="text-xs font-semibold text-slate-600">Nama Jabatan</label>
              <input name="title" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm"
                     placeholder="Contoh: KETUA UMUM" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-600">Catatan (opsional)</label>
                <input name="notes" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm"
                       placeholder="Contoh: (BID. IMARAH)">
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-600">Sort</label>
                <input type="number" name="sort_order" value="0"
                       class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
              </div>
            </div>

            <button class="w-full rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
              Simpan Jabatan
            </button>
          </form>
        </div>

        {{-- Add Member --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-bold text-slate-900">Tambah Anggota</h2>
            <p class="text-sm text-slate-500">Masukkan nama anggota per jabatan.</p>
          </div>
          <form class="p-5 space-y-3" method="POST" action="{{ route('admin.members.store') }}">
            @csrf
            <div>
              <label class="text-xs font-semibold text-slate-600">Pilih Jabatan (Position)</label>
              <select name="position_id" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm" required>
                <option value="">— pilih jabatan —</option>
                @foreach($units as $u)
                  @php $pos = $positionsByUnit[$u->id] ?? collect(); @endphp
                  @foreach($pos as $p)
                    <option value="{{ $p->id }}">{{ $u->name }} → {{ $p->title }} (ID {{ $p->id }})</option>
                  @endforeach
                @endforeach
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs font-semibold text-slate-600">Nama</label>
                <input name="name" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm"
                       placeholder="Nama anggota" required>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-600">Sort</label>
                <input type="number" name="sort_order" value="0"
                       class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
              </div>
            </div>

            <button class="w-full rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
              Simpan Anggota
            </button>
          </form>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
