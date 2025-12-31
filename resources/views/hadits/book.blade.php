<x-front-layout>
@php $bg='#13392f'; $accent='#E7B14B'; @endphp

<div class="min-h-screen text-white" style="background: {{ $bg }};">
  <div class="mx-auto max-w-5xl px-4 py-10">

    <a href="{{ route('perpustakaan.hadits.index') }}" class="text-white/80 hover:text-white">
      ← Semua Kitab
    </a>

    <div class="mt-3">
      <h1 class="text-2xl font-extrabold">{{ $book }}</h1>
      <p class="text-white/70 text-sm">
        Total hadits: {{ number_format($total) }}
      </p>
    </div>

    <div class="mt-6 grid gap-2 sm:grid-cols-5">
      @foreach($numbers as $n)
        <a href="{{ route('perpustakaan.hadits.show', ['book' => $book, 'no' => $n]) }}"
           class="rounded-xl bg-white px-3 py-2 text-center text-sm font-bold text-slate-900 hover:bg-slate-50">
          #{{ $n }}
        </a>
      @endforeach
    </div>

    <div class="mt-8 flex items-center justify-between">
      <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $page-1)]) }}"
         class="rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-900 hover:bg-slate-50">
        Prev
      </a>

      <div class="text-sm text-white/70">
        Page {{ $page }} • Menampilkan {{ count($numbers) }} nomor
      </div>

      <a href="{{ request()->fullUrlWithQuery(['page' => $page+1]) }}"
         class="rounded-xl bg-white px-4 py-2 text-sm font-bold text-slate-900 hover:bg-slate-50">
        Next
      </a>
    </div>

  </div>
</div>
</x-front-layout>
