<x-front-layout>
@php
  $bg='#13392f'; $accent='#E7B14B';
@endphp
<div class="min-h-screen text-white" style="background: {{ $bg }};">
  <div class="mx-auto max-w-5xl px-4 py-10">
    <div class="flex items-end justify-between gap-3">
      <div>
        <p class="text-white/70 text-sm">Perpustakaan</p>
        <h1 class="text-3xl font-extrabold">Kitab Hadits</h1>
      </div>
      <form method="GET" class="w-[320px]">
        <input name="q" value="{{ $q }}" placeholder="Cari kitab..."
          class="h-11 w-full rounded-2xl bg-white px-4 text-slate-900" />
      </form>
    </div>

    <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($books as $b)
        <a href="{{ route('perpustakaan.hadits.book', ['book' => $b['book']]) }}"
           class="rounded-2xl bg-white p-4 text-slate-900 hover:bg-slate-50">
          <div class="text-xs text-slate-500">Edition</div>
          <div class="mt-1 font-extrabold">{{ $b['book'] }}</div>
          <div class="mt-1 text-sm text-slate-600 line-clamp-2">{{ $b['label'] }}</div>
        </a>
      @endforeach
    </div>
  </div>
</div>
</x-front-layout>
