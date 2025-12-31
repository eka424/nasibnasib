<x-front-layout>
@php
  $bg = '#13392f';
  $accent = '#E7B14B';
@endphp

<style>
  :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }
</style>

<div class="min-h-screen" style="background: var(--bg);">
  <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

    <a href="{{ route('perpustakaan.index', ['tab' => 'quran']) }}"
       class="inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-2 text-sm font-bold text-slate-800 hover:bg-slate-50">
      ← Kembali
    </a>

    <div class="mt-4 rounded-3xl bg-white p-6">
      @if(!empty($error))
        <h1 class="text-xl font-extrabold text-slate-900">Gagal memuat surah</h1>
        <p class="mt-2 text-sm text-slate-600">Error: {{ $error }}</p>
      @elseif(empty($data))
        <h1 class="text-xl font-extrabold text-slate-900">Data kosong</h1>
      @else
        @php
          $namaLatin = $data['namaLatin'] ?? 'Surah';
          $arti = $data['arti'] ?? '';
          $jumlahAyat = $data['jumlahAyat'] ?? '';
          $ayat = $data['ayat'] ?? [];
        @endphp

        <div class="flex items-start justify-between gap-4">
          <div>
            <p class="text-xs text-slate-500">Surah #{{ $surah }} • {{ $jumlahAyat }} ayat</p>
            <h1 class="mt-1 text-2xl font-extrabold text-slate-900">{{ $namaLatin }}</h1>
            @if($arti)
              <p class="mt-1 text-sm text-slate-600">Arti: {{ $arti }}</p>
            @endif
          </div>
          <span class="rounded-xl px-3 py-1 text-xs font-extrabold text-[#13392f]" style="background: var(--accent);">
            Qur’an
          </span>
        </div>

        <div class="mt-6 space-y-4">
          @foreach($ayat as $a)
            @php
              $noAyat = $a['nomorAyat'] ?? '';
              $arab  = $a['teksArab'] ?? '';
              $indo  = $a['teksIndonesia'] ?? '';
              $latin = $a['teksLatin'] ?? '';
            @endphp

            <div class="rounded-2xl border border-slate-200 p-4">
              <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-extrabold text-slate-900">Ayat {{ $noAyat }}</p>
              </div>

              <p class="mt-3 text-right text-2xl leading-relaxed text-slate-900">
                {{ $arab }}
              </p>

              @if($latin)
                <p class="mt-3 text-sm italic text-slate-600">
                  {{ $latin }}
                </p>
              @endif

              @if($indo)
                <p class="mt-3 text-sm text-slate-700">
                  {{ $indo }}
                </p>
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>
</div>
</x-front-layout>
