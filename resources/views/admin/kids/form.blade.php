<x-front-layout>
@php
  // ===== Theme (match topbar kamu) =====
  $cream  = '#F5F1E8';
  $bg     = '#13392f';
  $accent = '#E7B14B';

  $labels = [
    'video' => 'Video Animasi (YouTube)',
    'story' => 'Dongeng Islami (PDF)',
    'quote' => 'Kata-kata Islami (Quote)',
  ];

  $type = $type ?? request('type', 'video');
  $item = $item ?? (object)[
    'title' => '',
    'sort_order' => 0,
    'is_published' => true,
    'thumbnail' => '',
    'youtube_url' => '',
    'youtube_id' => '',
    'pdf_path' => '',
    'quote_text' => '',
    'quote_source' => '',
  ];

  $mode = $mode ?? 'create'; // create|edit
@endphp

<div class="min-h-screen" style="background: {{ $cream }};">
  <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs font-bold tracking-[0.25em] text-[#13392f]/60 uppercase">ADMIN</p>
        <h1 class="mt-2 text-3xl font-extrabold text-[#13392f]">
          {{ $mode === 'create' ? 'Tambah' : 'Edit' }} {{ $labels[$type] ?? 'Konten Ramah Anak' }}
        </h1>
        <p class="mt-1 text-sm text-[#13392f]/70">
          Konten akan tampil di halaman Ramah Anak jika dipublish.
        </p>
      </div>

      <div class="flex gap-2">
        <a href="{{ route('admin.kids.index', ['type' => $type]) }}"
           class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2 text-xs font-bold text-[#13392f]
                  border border-black/10 hover:bg-black/5">
          ← Kembali
        </a>
      </div>
    </div>

    {{-- Tabs type (biar gak YouTube doang) --}}
    <div class="mt-6 inline-flex overflow-hidden rounded-full border border-black/10 bg-white">
      <a href="{{ route('admin.kids.create', ['type' => 'video']) }}"
         class="px-4 py-2 text-sm font-semibold transition
                {{ $type==='video' ? 'bg-[#E7B14B] text-[#13392f]' : 'text-[#13392f]/75 hover:bg-black/5' }}">
        Video
      </a>
      <a href="{{ route('admin.kids.create', ['type' => 'story']) }}"
         class="px-4 py-2 text-sm font-semibold transition
                {{ $type==='story' ? 'bg-[#E7B14B] text-[#13392f]' : 'text-[#13392f]/75 hover:bg-black/5' }}">
        Dongeng PDF
      </a>
      <a href="{{ route('admin.kids.create', ['type' => 'quote']) }}"
         class="px-4 py-2 text-sm font-semibold transition
                {{ $type==='quote' ? 'bg-[#E7B14B] text-[#13392f]' : 'text-[#13392f]/75 hover:bg-black/5' }}">
        Quote
      </a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
      <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
        <div class="font-bold mb-2">Ada yang perlu diperbaiki:</div>
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    {{-- Form Card --}}
    <form class="mt-6 rounded-3xl border border-black/10 bg-white p-6 shadow-sm"
          method="POST"
          action="{{ $mode === 'create' ? route('admin.kids.store') : route('admin.kids.update', $item) }}"
          enctype="multipart/form-data">
      @csrf
      @if($mode === 'edit') @method('PUT') @endif

      <input type="hidden" name="type" value="{{ $type }}">

      <div class="grid gap-5 sm:grid-cols-2">
        {{-- Title --}}
        <div class="sm:col-span-2">
          <label class="text-xs font-extrabold text-[#13392f]/80">Judul <span class="text-rose-600">*</span></label>
          <input name="title" required
                 value="{{ old('title', $item->title ?? '') }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#13392f]
                        placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60"
                 placeholder="Contoh: Animasi Adab Makan">
          <p class="mt-1 text-xs text-[#13392f]/60">Wajib diisi.</p>
        </div>

        {{-- Sort order --}}
        <div>
          <label class="text-xs font-extrabold text-[#13392f]/80">Urutan (sort order)</label>
          <input type="number" name="sort_order"
                 value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#13392f]
                        focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60">
          <p class="mt-1 text-xs text-[#13392f]/60">Angka kecil tampil lebih dulu.</p>
        </div>

        {{-- Publish --}}
        <div class="flex items-end">
          <label class="inline-flex items-center gap-2 rounded-2xl border border-black/10 bg-black/5 px-4 py-3 text-sm font-semibold text-[#13392f]">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1"
                   class="h-4 w-4 rounded border-black/20"
                   {{ old('is_published', $item->is_published ?? true) ? 'checked' : '' }}>
            Publish
          </label>
        </div>

        {{-- Thumbnail --}}
        <div class="sm:col-span-2">
          <label class="text-xs font-extrabold text-[#13392f]/80">Thumbnail (opsional URL)</label>
          <input name="thumbnail"
                 value="{{ old('thumbnail', $item->thumbnail ?? '') }}"
                 class="mt-1 h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#13392f]
                        placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60"
                 placeholder="https://...">
          <p class="mt-1 text-xs text-[#13392f]/60">Boleh kosong.</p>
        </div>

        {{-- VIDEO --}}
        @if($type === 'video')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-[#13392f]/80">YouTube Link / ID</label>
            <input name="youtube_url"
                   value="{{ old('youtube_url', ($item->youtube_url ?? '') ?: ($item->youtube_id ?? '')) }}"
                   class="mt-1 h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#13392f]
                          placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60"
                   placeholder="https://youtube.com/watch?v=... atau ID saja">
            <p class="mt-2 text-xs text-[#13392f]/60">
              Sistem akan ambil <b>youtube_id</b> otomatis dari link (kalau controller sudah kamu rapihin).
            </p>
          </div>
        @endif

        {{-- STORY (PDF) --}}
        @if($type === 'story')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-[#13392f]/80">
              Upload PDF Dongeng
              @if($mode==='create') <span class="text-rose-600">*</span> @endif
            </label>

            <input type="file" name="pdf" accept="application/pdf"
                   class="mt-1 w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-[#13392f]">

            @if($mode==='edit' && !empty($item->pdf_path))
              <p class="mt-2 text-xs text-[#13392f]/70">
                PDF saat ini:
                <span class="font-bold">{{ $item->pdf_path }}</span>
              </p>
              <p class="mt-1 text-xs text-[#13392f]/60">Kalau tidak upload lagi, PDF lama tetap dipakai.</p>
            @else
              <p class="mt-2 text-xs text-[#13392f]/60">Wajib upload PDF untuk tipe “Dongeng”.</p>
            @endif
          </div>
        @endif

        {{-- QUOTE --}}
        @if($type === 'quote')
          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-[#13392f]/80">Isi Quote <span class="text-rose-600">*</span></label>
            <textarea name="quote_text" rows="5" required
              class="mt-1 w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-[#13392f]
                     placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60"
              placeholder="Contoh: Bismillah sebelum makan ya...">{{ old('quote_text', $item->quote_text ?? '') }}</textarea>
          </div>

          <div class="sm:col-span-2">
            <label class="text-xs font-extrabold text-[#13392f]/80">Sumber (opsional)</label>
            <input name="quote_source"
                   value="{{ old('quote_source', $item->quote_source ?? '') }}"
                   class="mt-1 h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-[#13392f]
                          placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60"
                   placeholder="Misal: Hadits / Adab Harian / dll">
          </div>
        @endif
      </div>

      {{-- Actions --}}
      <div class="mt-7 flex flex-col gap-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.kids.index', ['type' => $type]) }}"
           class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-bold text-[#13392f]
                  border border-black/10 hover:bg-black/5">
          Batal
        </a>

        <button type="submit"
                class="inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-extrabold text-[#13392f]
                       border border-black/10 shadow-sm hover:-translate-y-0.5 transition"
                style="background: {{ $accent }};">
          {{ $mode === 'create' ? 'Simpan' : 'Update' }}
        </button>
      </div>
    </form>
  </div>
</div>
</x-front-layout>
