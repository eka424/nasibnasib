@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-4xl px-4 py-6 md:px-6">

    <div class="flex items-end justify-between gap-3">
      <div>
        <h1 class="text-xl font-bold text-slate-900">
          {{ $mode === 'edit' ? 'Edit Periode' : 'Buat Periode Baru' }}
        </h1>
        <p class="text-sm text-slate-500">Isi metadata SK/keputusan dan masa khidmat.</p>
      </div>

      <a href="{{ route('admin.struktur.index') }}"
         class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
        Kembali
      </a>
    </div>

    @if ($errors->any())
      <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <div class="font-semibold mb-1">Ada error:</div>
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('ok'))
      <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('ok') }}
      </div>
    @endif

    <form class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
          method="POST"
          action="{{ $mode === 'edit' ? route('admin.struktur.update',$term) : route('admin.struktur.store') }}">
      @csrf
      @if($mode === 'edit') @method('PUT') @endif

      <div class="grid gap-4 md:grid-cols-2">
        <div class="md:col-span-2">
          <label class="text-xs font-semibold text-slate-600">Judul</label>
          <input name="title" value="{{ old('title', $term->title) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="SUSUNAN PENGURUS DKM MASJID ...">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Judul Keputusan (opsional)</label>
          <input name="decision_title" value="{{ old('decision_title', $term->decision_title) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="KEPUTUSAN MUSYAWARAH BESAR VI">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Nomor Keputusan</label>
          <input name="decision_number" value="{{ old('decision_number', $term->decision_number) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="013 / MUBES-VI / I / 2022">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Periode</label>
          <input name="period_label" value="{{ old('period_label', $term->period_label) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="2022-2026">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Lokasi</label>
          <input name="location" value="{{ old('location', $term->location) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="Gianyar">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Tanggal Hijriah</label>
          <input name="decision_date_hijri" value="{{ old('decision_date_hijri', $term->decision_date_hijri) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200"
                 placeholder="25 Jumadil Akhir 1443 H">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Tanggal Masehi</label>
          <input type="date" name="decision_date_masehi"
                 value="{{ old('decision_date_masehi', optional($term->decision_date_masehi)->format('Y-m-d')) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Berlaku dari</label>
          <input type="date" name="valid_from"
                 value="{{ old('valid_from', optional($term->valid_from)->format('Y-m-d')) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200">
        </div>

        <div>
          <label class="text-xs font-semibold text-slate-600">Berlaku sampai</label>
          <input type="date" name="valid_to"
                 value="{{ old('valid_to', optional($term->valid_to)->format('Y-m-d')) }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-emerald-200">
        </div>
      </div>

      <div class="mt-5 flex flex-wrap gap-2">
        <button class="rounded-xl bg-emerald-700 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
          {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Buat Periode' }}
        </button>

        @if($mode === 'edit')
          <a href="{{ route('admin.struktur.builder',$term) }}"
             class="rounded-xl bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Ke Builder
          </a>
        @endif
      </div>
    </form>

  </div>
</div>
@endsection
