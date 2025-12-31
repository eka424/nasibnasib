<x-front-layout>
@php $bg='#13392f'; @endphp

<div class="min-h-screen text-white" style="background: {{ $bg }};">
  <div class="mx-auto max-w-5xl px-4 py-10">

    <a href="{{ route('perpustakaan.hadits.book', ['book' => $book]) }}" class="text-white/80 hover:text-white">
      ← Kembali ke list nomor
    </a>

    <div class="mt-3 rounded-3xl bg-white p-6 text-slate-900">
      <div class="text-xs text-slate-500">Kitab: {{ $book }} • Hadits #{{ $no }}</div>

      {{-- tergantung struktur JSON, biasanya ada "hadith" atau "text" --}}
      <div class="mt-3 text-lg leading-relaxed">
        {{ $data['hadith'] ?? $data['text'] ?? json_encode($data) }}
      </div>

      @if(!empty($data['reference']))
        <div class="mt-4 text-sm text-slate-600">
          Referensi: {{ is_array($data['reference']) ? json_encode($data['reference']) : $data['reference'] }}
        </div>
      @endif
    </div>

  </div>
</div>
</x-front-layout>
