<x-front-layout>
@php
  $bg = '#13392f';
  $accent = '#E7B14B';
  $glass = 'rounded-[22px] border border-black/10 bg-white shadow-sm';

  // tab default
  $tab = $tab ?? request('tab', 'books'); // books|quran|hadits
  $q   = $q ?? request('q', '');
@endphp

<style>
  :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }
  .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
</style>

<div class="min-h-screen" style="background: var(--bg);">
  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Header + Search --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-sm text-white/70">Perpustakaan Digital</p>
        <h1 class="text-3xl font-extrabold text-white">Perpustakaan (Gabungan)</h1>
        <p class="mt-1 text-sm text-white/70">Buku internal + Qur’an</p>
      </div>

      <form method="GET" class="w-full sm:w-[420px]">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input
          name="q"
          value="{{ $q }}"
          placeholder="Cari buku / surah / kitab hadits..."
          class="h-11 w-full rounded-2xl border border-black/10 bg-white px-4 text-sm text-slate-900
                 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[rgba(231,177,75,0.55)]"
        />
      </form>
    </div>
{{-- CTA: Ramah Anak --}}
<div class="mb-6 grid gap-3 sm:grid-cols-3">
  {{-- Tombol utama --}}
  <a href="{{ route('perpustakaan.ramah-anak') }}"
     class="group inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-extrabold text-[#13392f] shadow-sm transition hover:-translate-y-0.5"
     style="background: var(--accent);">
    👶 Perpustakaan Ramah Anak
    <span class="opacity-70 group-hover:opacity-100">→</span>
  </a>

  {{-- Tombol alternatif --}}
  <a href="{{ route('perpustakaan.index', ['tab' => 'books']) }}"
     class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white hover:bg-white/15">
    📚 Buku Umum
  </a>

  <a href="{{ route('quran.index') }}"
     class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white hover:bg-white/15">
    📖 Al-Qur’an
  </a>
</div>

{{-- Highlight Ramah Anak --}}
<div class="mb-6 rounded-3xl border border-white/12 bg-white/5 p-5 text-white">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0">
      <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-white/70">Highlight</p>
      <h2 class="mt-1 text-lg font-extrabold text-white">Program Perpustakaan Ramah Anak</h2>
      <p class="mt-1 text-sm text-white/70">
        Bacaan islami anak, aktivitas ringan, dan materi yang aman untuk keluarga.
      </p>
      <ul class="mt-3 grid gap-2 text-sm text-white/80 sm:grid-cols-3">
        <li class="rounded-2xl border border-white/12 bg-white/5 px-3 py-2">📚 Buku anak islami</li>
        <li class="rounded-2xl border border-white/12 bg-white/5 px-3 py-2">🎧 Audio/dongeng</li>
        <li class="rounded-2xl border border-white/12 bg-white/5 px-3 py-2">🧩 Aktivitas edukatif</li>
      </ul>
    </div>

    <div class="shrink-0">
      <a href="{{ route('perpustakaan.ramah-anak') }}"
         class="inline-flex w-full items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-extrabold text-[#13392f] shadow-sm hover:bg-slate-50 sm:w-auto">
        Buka Ramah Anak →
      </a>
    </div>
  </div>
</div>

    {{-- Tabs --}}
    <div class="mb-6 inline-flex overflow-hidden rounded-full border border-white/20 bg-white/10">
      <a href="{{ request()->fullUrlWithQuery(['tab'=>'books']) }}"
         class="px-5 py-2 text-sm font-bold transition {{ $tab==='books' ? 'text-[#13392f]' : 'text-white/90 hover:bg-white/10' }}"
         style="{{ $tab==='books' ? 'background: var(--accent);' : '' }}">
         📚 Buku
      </a>
      <a href="{{ request()->fullUrlWithQuery(['tab'=>'quran']) }}"
         class="px-5 py-2 text-sm font-bold transition {{ $tab==='quran' ? 'text-[#13392f]' : 'text-white/90 hover:bg-white/10' }}"
         style="{{ $tab==='quran' ? 'background: var(--accent);' : '' }}">
         📖 Qur’an
      </a>

    </div>

    {{-- Content wrapper --}}
    <div class="rounded-3xl border border-white/12 bg-white/5 p-4 sm:p-6">

      {{-- TAB: BOOKS (DB) --}}
      @if($tab === 'books')
        @php
          $booksCount = is_object($books ?? null) ? ($books->count() ?? 0) : (is_array($books ?? null) ? count($books) : 0);
        @endphp

        @if($booksCount === 0)
          <div class="rounded-2xl bg-white p-8 text-center">
            <p class="text-slate-900 font-bold">Belum ada buku ditemukan.</p>
            <p class="mt-1 text-sm text-slate-500">Coba ganti kata kunci pencarian.</p>
          </div>
        @else
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach(($books ?? []) as $book)
              @php
                $cover = $book->cover ?? null;
                if ($cover && !str_starts_with($cover, 'http')) {
                  $cover = \Illuminate\Support\Facades\Storage::url($cover);
                }
              @endphp

              <article class="overflow-hidden {{ $glass }}">
                @if($cover)
                  <img src="{{ $cover }}" class="h-44 w-full object-cover" alt="{{ $book->judul }}" loading="lazy">
                @else
                  <div class="h-44 w-full bg-slate-100"></div>
                @endif

                <div class="p-4">
                  <p class="text-[11px] text-slate-500">{{ $book->kategori ?? 'Buku' }}</p>
                  <h3 class="mt-1 font-extrabold text-slate-900 line-clamp-2">{{ $book->judul }}</h3>

                  @if(!empty($book->penulis))
                    <p class="mt-1 text-xs text-slate-600">oleh {{ $book->penulis }}</p>
                  @endif

                  <div class="mt-3 flex gap-2">
                    <a href="{{ route('perpustakaan.show', $book) }}"
                       class="flex-1 rounded-xl px-3 py-2 text-xs font-extrabold text-[#13392f] text-center"
                       style="background: var(--accent);">
                      Detail
                    </a>

                    @if(!empty($book->file_ebook))
                      <a href="{{ $book->file_ebook }}" target="_blank" rel="noreferrer"
                         class="rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-extrabold text-slate-800 hover:bg-slate-50">
                        Baca
                      </a>
                    @endif
                  </div>
                </div>
              </article>
            @endforeach
          </div>

          @if(($books ?? null) instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="mt-6">
              {{ $books->appends(request()->query())->links() }}
            </div>
          @endif
        @endif
      @endif

      {{-- TAB: QURAN --}}
      @if($tab === 'quran')
        @if(!empty($quranError))
          <div class="rounded-2xl bg-white p-8 text-center">
            <p class="text-slate-900 font-bold">Data Qur’an gagal dimuat.</p>
            <p class="mt-1 text-sm text-slate-500">Error: {{ $quranError }}</p>
          </div>
        @elseif(empty($surahs))
          <div class="rounded-2xl bg-white p-8 text-center">
            <p class="text-slate-900 font-bold">Data Qur’an kosong.</p>
            <p class="mt-1 text-sm text-slate-500">Coba refresh.</p>
          </div>
        @else
          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($surahs as $s)
              @php
                // EQuran v2
                $no   = $s['nomor'] ?? null;
                $nama = $s['namaLatin'] ?? ('Surah ' . $no);
                $arti = $s['arti'] ?? null;
                $ayat = $s['jumlahAyat'] ?? null;
              @endphp

              <article class="{{ $glass }} p-4">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <p class="text-xs text-slate-500">Surah #{{ $no }} @if($ayat) • {{ $ayat }} ayat @endif</p>
                    <h3 class="mt-1 font-extrabold text-slate-900">{{ $nama }}</h3>
                    @if($arti)
                      <p class="mt-1 text-sm text-slate-600">{{ $arti }}</p>
                    @endif
                  </div>
                  <span class="shrink-0 rounded-xl px-3 py-1 text-xs font-extrabold text-[#13392f]" style="background: var(--accent);">
                    Qur’an
                  </span>
                </div>

                <div class="mt-3">
                  @if(\Illuminate\Support\Facades\Route::has('quran.show') && $no)
                    <a href="{{ route('quran.show', ['surah' => $no]) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-extrabold text-slate-800 hover:bg-slate-50">
                      Buka Surah
                    </a>
                  @else
                    <a href="{{ request()->fullUrlWithQuery(['tab'=>'quran']) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-extrabold text-slate-800 hover:bg-slate-50">
                      Lihat Surah (nanti detail)
                    </a>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        @endif
      @endif

      {{-- TAB: HADITS --}}
      @if($tab === 'hadits')
        @if(!empty($hadithError))
          <div class="rounded-2xl bg-white p-8 text-center">
            <p class="text-slate-900 font-bold">Data Hadits gagal dimuat.</p>
            <p class="mt-1 text-sm text-slate-500">Error: {{ $hadithError }}</p>
          </div>
        @elseif(empty($narrators))
          <div class="rounded-2xl bg-white p-8 text-center">
            <p class="text-slate-900 font-bold">Data Hadits kosong.</p>
            <p class="mt-1 text-sm text-slate-500">Coba refresh.</p>
          </div>
        @else
          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($narrators as $n)
              @php
                $slug = $n['slug'] ?? $n['id'] ?? null;
                $name = $n['name'] ?? $n['title'] ?? $slug;
              @endphp

              <article class="{{ $glass }} p-4">
                <p class="text-xs text-slate-500">Kitab Hadits</p>
                <h3 class="mt-1 font-extrabold text-slate-900">{{ $name }}</h3>

                <div class="mt-3">
                  @if($slug)
                    <a href="{{ route('hadits.list', ['slug' => $slug]) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl border border-black/10 bg-white px-3 py-2 text-xs font-extrabold text-slate-800 hover:bg-slate-50">
                      Lihat Kitab
                    </a>
                  @else
                    <div class="text-xs text-rose-600">Slug kosong (data API tidak sesuai).</div>
                  @endif
                </div>

                <p class="mt-2 text-[11px] text-slate-500">
                  Klik untuk lihat list nomor hadits, lalu detailnya.
                </p>
              </article>
            @endforeach
          </div>
        @endif
      @endif

    </div>
  </div>
</div>
</x-front-layout>
