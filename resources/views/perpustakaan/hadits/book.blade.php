<x-front-layout>
@php
  $card = 'rounded-2xl border border-black/10 bg-white shadow-sm';
  $title = $bookInfo['title'] ?? $bookInfo['display'] ?? $edition;
@endphp

<div class="min-h-screen bg-white text-slate-900">
  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-slate-500">Kitab Hadits</p>
        <h1 class="mt-2 text-3xl font-extrabold">{{ $title }}</h1>
        <p class="mt-1 text-sm text-slate-600">
          Edition: <span class="font-semibold">{{ $edition }}</span> • Total: <span class="font-semibold">{{ number_format($total) }}</span>
        </p>
      </div>

      <a href="{{ route('perpustakaan.hadits.index') }}"
         class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50">
        ← Semua Kitab
      </a>
    </div>

    <div class="mt-6 {{ $card }} p-5">
      <div class="flex items-center justify-between gap-3">
        <p class="text-sm text-slate-700">
          Halaman <span class="font-bold">{{ $page }}</span> / {{ $lastPage }}
        </p>

        <div class="flex gap-2">
          <a class="rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-bold hover:bg-slate-50 {{ $page <= 1 ? 'pointer-events-none opacity-40' : '' }}"
             href="{{ request()->fullUrlWithQuery(['page' => max(1, $page-1)]) }}">
            ← Prev
          </a>
          <a class="rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-bold hover:bg-slate-50 {{ $page >= $lastPage ? 'pointer-events-none opacity-40' : '' }}"
             href="{{ request()->fullUrlWithQuery(['page' => min($lastPage, $page+1)]) }}">
            Next →
          </a>
        </div>
      </div>

      <div class="mt-4 grid grid-cols-4 gap-2 sm:grid-cols-6 lg:grid-cols-10">
        @foreach($numbers as $n)
          <a href="{{ route('perpustakaan.hadits.show', [$edition, $n]) }}"
             class="rounded-xl border border-black/10 bg-white px-3 py-2 text-center text-xs font-extrabold text-slate-800 hover:bg-emerald-50 hover:border-emerald-200">
            #{{ $n }}
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>
</x-front-layout>
