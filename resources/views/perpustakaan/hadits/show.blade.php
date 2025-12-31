<x-front-layout>
@php
  $card = 'rounded-2xl border border-black/10 bg-white shadow-sm';
@endphp

<div class="min-h-screen bg-white text-slate-900">
  <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-slate-500">Hadits</p>
        <h1 class="mt-2 text-3xl font-extrabold">{{ $edition }} • #{{ $no }}</h1>
      </div>

      <a href="{{ route('perpustakaan.hadits.book', $edition) }}"
         class="rounded-xl border border-black/10 bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50">
        ← Kembali ke Nomor
      </a>
    </div>

    <div class="mt-6 {{ $card }} p-6">
      @if($hadithText)
        <div class="prose max-w-none prose-slate">
          <p class="text-lg leading-relaxed text-slate-900 whitespace-pre-line">{{ $hadithText }}</p>
        </div>
      @else
        <p class="text-slate-600">Hadits tidak ditemukan / format API berubah.</p>
        <pre class="mt-4 overflow-auto rounded-xl bg-slate-50 p-4 text-xs text-slate-700">{{ json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
      @endif

      <div class="mt-6 grid gap-3 sm:grid-cols-2">
        <div class="rounded-xl border border-black/10 bg-slate-50 p-4">
          <p class="text-xs font-bold text-slate-600">GRADE</p>
          <p class="mt-1 text-sm font-semibold text-slate-900">{{ $grade ?? '-' }}</p>
        </div>
        <div class="rounded-xl border border-black/10 bg-slate-50 p-4">
          <p class="text-xs font-bold text-slate-600">REFERENCE</p>
          <p class="mt-1 text-sm font-semibold text-slate-900">{{ is_array($ref) ? json_encode($ref) : ($ref ?? '-') }}</p>
        </div>
      </div>

      <div class="mt-6 flex gap-2">
        <a href="{{ route('perpustakaan.hadits.show', [$edition, max(1, $no-1)]) }}"
           class="rounded-xl border border-black/10 bg-white px-4 py-2 text-xs font-extrabold hover:bg-slate-50 {{ $no <= 1 ? 'pointer-events-none opacity-40' : '' }}">
          ← Sebelumnya
        </a>
        <a href="{{ route('perpustakaan.hadits.show', [$edition, $no+1]) }}"
           class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-extrabold text-white hover:bg-emerald-700">
          Berikutnya →
        </a>
      </div>
    </div>
  </div>
</div>
</x-front-layout>
