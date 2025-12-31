<x-front-layout>
@php
  $bg = '#13392f';
  $accent = '#E7B14B';
  $glass = 'rounded-[28px] border border-white/14 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  $labels = [
    'video' => 'Video Animasi (YouTube)',
    'story' => 'Dongeng Islami (PDF)',
    'quote' => 'Kata-kata Islami (Quote)',
  ];

  $isCreate = ($mode ?? 'create') === 'create';
  $isEdit   = ! $isCreate;

  // safety defaults
  $type = $type ?? ($item->type ?? 'video');
  $item = $item ?? new \App\Models\KidsContent();

  // pdf url helper
  $pdfUrl = null;
  if (!empty($item->pdf_path)) {
    $pdfUrl = \Illuminate\Support\Facades\Storage::url($item->pdf_path);
  }
@endphp

<style>
  :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }
</style>

<div class="min-h-screen text-white" style="background: var(--bg);">
  <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs text-white/70 uppercase tracking-[0.25em]">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold">
          {{ $isCreate ? 'Tambah' : 'Edit' }} {{ $labels[$type] ?? 'Konten' }}
        </h1>
        <p class="mt-1 text-sm text-white/70">
          Isi data dengan benar. Konten akan tampil di halaman Ramah Anak jika dipublish.
        </p>
      </div>

      <a href="{{ route('admin.kids.index', ['type'=>$type]) }}"
         class="inline-flex items-center justify-center rounded-2xl border border-white/14 bg-white/6 px-4 py-2 text-xs font-extrabold text-white/90 hover:bg-white/10">
        ← Kembali
      </a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
      <div class="mt-5 rounded-2xl border border-rose-300/40 bg-rose-500/10 p-4 text-sm text-rose-100">
        <p class="font-extrabold mb-2">Periksa lagi input kamu:</p>
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    {{-- Form --}}
    <form class="mt-6 {{ $glass }} p-5 sm:p-6"
          method="POST"
          action="{{ $isCreate ? route('admin.kids.store') : route('admin.kids.update', $item) }}"
          enctype="multipart/form-data">
      @csrf
      @if($isEdit) @method('PUT') @endif

      <input type="hidden" name="type" value="{{ $type }}">

      <div class="grid gap-4 sm:grid-cols-2">

        {{-- Title --}}
        <div class="sm:col-span-2">
          <label class="text-xs font-extrabold text-white/80">Judul</label>
          <input name="title" value="{{ old('title', $item->title) }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white placeholder:text-white/45 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                 placeholder="Contoh: Animasi Adab Makan" required>
        </div>

        {{-- Sort Order --}}
        <div>
          <label class="text-xs font-extrabold text-white/80">Urutan (sort order)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]">
          <p class="mt-1 text-[11px] text-white/55">Angka kecil tampil lebih dulu.</p>
        </div>

        {{-- Publish --}}
        <div class="flex items-end">
          <label class="inline-flex items-center gap-2 text-sm text-white/85">
            <input type="checkbox" name="is_published" value="1"
                   class="h-4 w-4 rounded border-white/25 bg-white/10"
                   {{ old('is_published', (int)($item->is_published ?? 1)) ? 'checked' : '' }}>
            Publish
          </label>
        </div>

        {{-- Thumbnail --}}
        <div class="sm:col-span-2">
          <label class="text-xs font-extrabold text-white/80">Thumbnail (opsional URL)</label>
          <input name="thumbnail" value="{{ old('thumbnail', $item->thumbnail) }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white placeholder:text-white/45 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                 placeholder="https://...">
          <p class="mt-1 text-[11px] text-white/55">Boleh dikosongkan.</p>
        </div>

        {{-- VIDEO --}}
        @if($type === 'video')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-white/80">YouTube Link / ID</label>
            <input name="youtube_url" value="{{ old('youtube_url', $item->youtube_url ?: $item->youtube_id) }}"
                   class="mt-1 h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white placeholder:text-white/45 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                   placeholder="https://youtube.com/watch?v=... atau ID saja">
            <p class="mt-2 text-xs text-white/60">Sistem otomatis ambil video ID dari link.</p>
          </div>
        @endif

        {{-- STORY (PDF) --}}
        @if($type === 'story')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-white/80">Upload PDF Dongeng</label>

            <div class="mt-1 rounded-2xl border border-white/14 bg-white/6 p-4">
              <input type="file" name="pdf" accept="application/pdf"
                     {{ $isCreate ? 'required' : '' }}
                     class="w-full text-sm text-white file:mr-4 file:rounded-xl file:border-0 file:bg-white/15 file:px-4 file:py-2 file:text-xs file:font-extrabold file:text-white hover:file:bg-white/20">

              <div class="mt-3 text-xs text-white/65 space-y-1">
                @if($isCreate)
                  <p>📌 Wajib upload PDF untuk Dongeng.</p>
                @else
                  <p>📌 Kalau tidak upload lagi, sistem akan pakai PDF yang lama.</p>
                @endif

                @if($isEdit && $pdfUrl)
                  <p>
                    PDF saat ini:
                    <a href="{{ $pdfUrl }}" target="_blank" class="font-extrabold text-white underline decoration-[rgba(231,177,75,0.8)]">
                      Lihat PDF
                    </a>
                    <span class="text-white/50">({{ $item->pdf_path }})</span>
                  </p>
                @endif
              </div>
            </div>
          </div>
        @endif

        {{-- QUOTE --}}
        @if($type === 'quote')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-white/80">Isi Quote</label>
            <textarea name="quote_text" rows="5"
                      class="mt-1 w-full rounded-2xl border border-white/14 bg-white/6 px-4 py-3 text-sm text-white placeholder:text-white/45 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                      placeholder="Contoh: Bismillah sebelum makan ya..." required>{{ old('quote_text', $item->quote_text) }}</textarea>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-white/80">Sumber (opsional)</label>
            <input name="quote_source" value="{{ old('quote_source', $item->quote_source) }}"
                   class="mt-1 h-11 w-full rounded-2xl border border-white/14 bg-white/6 px-4 text-sm text-white placeholder:text-white/45 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
                   placeholder="Misal: Adab Harian / Hadits / dll">
          </div>
        @endif
      </div>

      {{-- Actions --}}
      <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.kids.index', ['type'=>$type]) }}"
           class="rounded-2xl border border-white/14 bg-white/6 px-6 py-3 text-sm font-extrabold text-white/90 hover:bg-white/10 text-center">
          Batal
        </a>

        <button class="rounded-2xl px-6 py-3 text-sm font-extrabold text-[#13392f] hover:brightness-105"
                style="background: var(--accent);">
          {{ $isCreate ? 'Simpan' : 'Update' }}
        </button>
      </div>
    </form>
  </div>
</div>
</x-front-layout>
