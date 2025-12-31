<x-front-layout>
@php
  $bg = '#ffffff';
  $card = 'rounded-2xl border border-black/10 bg-white shadow-sm';
@endphp

<div class="min-h-screen bg-white text-slate-900">
  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-slate-500">Perpustakaan</p>
        <h1 class="mt-2 text-3xl font-extrabold">Kitab Hadits</h1>
        <p class="mt-1 text-sm text-slate-600">Pilih kitab → pilih nomor → baca detail hadits.</p>
      </div>

      <a href="{{ route('perpustakaan.index') }}"
         class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50">
        ← Kembali
      </a>
    </div>

    <form class="mt-6" method="GET">
      <input name="q" value="{{ $q ?? '' }}"
             placeholder="Cari kitab (contoh: bukhari, muslim, abu dawud...)"
             class="h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      @forelse(($editions ?? []) as $e)
        @php
          $name = $e['name'] ?? '';
          $display = $e['display'] ?? $e['title'] ?? $name;
          $lang = $e['language'] ?? null;

          $info = $infoMap[$name] ?? null;
          $count = $info['hadithcount'] ?? $info['hadithCount'] ?? null;
        @endphp

        @if($name)
          <a href="{{ route('perpustakaan.hadits.book', $name) }}"
             class="{{ $card }} p-5 hover:bg-slate-50 transition">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <h3 class="text-base font-extrabold text-slate-900 truncate">
                  {{ $display }}
                </h3>
                <p class="mt-1 text-xs text-slate-500 truncate">
                  ID: {{ $name }} @if($lang) • {{ $lang }} @endif
                </p>
              </div>
              <span class="shrink-0 rounded-xl bg-emerald-600 px-3 py-1 text-xs font-extrabold text-white">
                Buka
              </span>
            </div>

            @if($count)
              <p class="mt-3 text-xs text-slate-600">Total hadits: <span class="font-bold">{{ number_format($count) }}</span></p>
            @else
              <p class="mt-3 text-xs text-slate-500">Klik untuk lihat daftar nomor hadits.</p>
            @endif
          </a>
        @endif
      @empty
        <div class="col-span-full {{ $card }} p-8 text-center text-slate-600">
          Data kitab tidak tersedia (API error / internet).
        </div>
      @endforelse
    </div>
  </div>
</div>
</x-front-layout>
