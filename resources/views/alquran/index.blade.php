<x-front-layout>
<div class="max-w-4xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-extrabold">Daftar Surah</h1>

  @if(!empty($error))
    <p class="mt-2 text-red-600">Error: {{ $error }}</p>
  @endif

  <div class="mt-6 grid gap-3 sm:grid-cols-2">
    @foreach(($surahs ?? []) as $s)
      <a class="rounded-2xl border p-4 hover:bg-slate-50"
         href="{{ route('quran.show', ['surah' => $s['nomor']]) }}">
        <div class="text-sm text-slate-500">Surah #{{ $s['nomor'] }}</div>
        <div class="font-bold">{{ $s['namaLatin'] ?? '-' }}</div>
        <div class="text-sm text-slate-600">{{ $s['arti'] ?? '' }}</div>
      </a>
    @endforeach
  </div>
</div>
</x-front-layout>
