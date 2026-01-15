<x-front-layout>
@php
  // ================= THEME =================
  $bg     = $bg ?? '#13392f';
  $accent = $accent ?? '#E7B14B';
  $glass  = $glass ?? 'rounded-[26px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

  // ================= HELPERS =================
  $extractDriveId = function (?string $url): ?string {
      $url = trim((string)$url);
      if ($url === '') return null;

      // /file/d/{id}/...
      if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) return $m[1];

      // thumbnail?id={id}
      if (preg_match('~drive\.google\.com/thumbnail\?id=([^&]+)~', $url, $m)) return $m[1];

      // uc?export=...&id={id}  atau uc?id={id}
      if (preg_match('~drive\.google\.com/uc\?(?:[^#]*&)?id=([^&]+)~', $url, $m)) return $m[1];

      // open?id={id} atau apapun yang punya ?id=
      $parts = parse_url($url);
      if (!empty($parts['query'])) {
          parse_str($parts['query'], $q);
          if (!empty($q['id'])) return (string)$q['id'];
      }

      return null;
  };

  $youtubeId = function (?string $url): ?string {
      $url = trim((string)$url);
      if ($url === '') return null;

      if (preg_match('~youtu\.be/([^?&/]+)~', $url, $m)) return $m[1];
      if (preg_match('~youtube\.com/watch\?v=([^&]+)~', $url, $m)) return $m[1];
      if (preg_match('~youtube\.com/embed/([^?&/]+)~', $url, $m)) return $m[1];
      if (preg_match('~youtube\.com/shorts/([^?&/]+)~', $url, $m)) return $m[1];
      return null;
  };

  // ================= DATA =================
  $type = $galeri->tipe ?? 'image';

  // url_file dari DB (bisa thumbnail / view / uc / dll)
  $rawUrl = trim((string)($galeri->url_file ?? ''));

  // tombol foto lainnya (opsional)
  $gdriveMore = trim((string)($galeri->gdrive_url ?? ''));

  // ================= NORMALIZE FINAL =================
  // kita paksa bikin URL final yang "pasti tampil"
  $fileSrc = $rawUrl; // yang dipakai tampil
  $action  = $rawUrl; // tombol unduh/buka

  // 1) YOUTUBE (video)
  if ($type === 'video') {
      $yt = $youtubeId($rawUrl);
      if ($yt) {
          $fileSrc = "https://www.youtube.com/embed/{$yt}";
          $action  = $rawUrl;
      }
  }

  // 2) GOOGLE DRIVE (image/video)
  $driveId = $extractDriveId($rawUrl);
  if ($driveId) {
      if ($type === 'image') {
          // tampil gambar: paling aman pakai thumbnail (hotlink)
          $fileSrc = "https://drive.google.com/thumbnail?id={$driveId}&sz=w1600";

          // tombol unduh
          $action  = "https://drive.google.com/uc?export=download&id={$driveId}";
      } else {
          // video drive: iframe preview
          $fileSrc = "https://drive.google.com/file/d/{$driveId}/preview";
          $action  = $rawUrl;
      }
  }

  // ================= META =================
  $tanggal  = optional($galeri->created_at)->format('d M Y') ?? '-';
  $kategori = strtoupper($galeri->kategori ?? '-');
  $seksi    = $galeri->seksi ?? '-';
@endphp

<style>
  :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }
</style>

<div class="min-h-screen text-slate-100" style="background: var(--bg);">
  <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

    <nav class="text-sm text-white/70 mb-6">
      <ol class="flex items-center gap-2">
        <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
        <li class="text-white/40">/</li>
        <li><a href="{{ route('galeri.index') }}" class="hover:text-white">Galeri</a></li>
        <li class="text-white/40">/</li>
        <li class="font-semibold text-white line-clamp-1">{{ $galeri->judul }}</li>
      </ol>
    </nav>

    <div class="grid gap-6 lg:grid-cols-12">
      <div class="lg:col-span-8 {{ $glass }} p-4">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-black/30 flex items-center justify-center">
          @if($type === 'video')
            <iframe
              src="{{ $fileSrc }}"
              class="w-full h-[60vh]"
              loading="lazy"
              referrerpolicy="no-referrer"
              allowfullscreen
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            ></iframe>
          @else
            <div class="relative w-full">
              @if(!empty($fileSrc))
                <img
                  id="mainImg"
                  src="{{ $fileSrc }}"
                  alt="{{ $galeri->judul }}"
                  class="w-full max-h-[65vh] object-contain"
                  referrerpolicy="no-referrer"
                  loading="lazy"
                />
                <div id="imgError"
                     class="hidden absolute inset-0 flex items-center justify-center p-6 text-center">
                  <div class="rounded-2xl border border-white/12 bg-black/50 px-4 py-3 text-sm text-white/90">
                    Gambar gagal dimuat.<br>
                    Pastikan Google Drive: <b>Anyone with the link</b>.<br>
                    Dan pastikan yang diinput adalah <b>link FILE</b> (bukan folder).
                  </div>
                </div>
              @else
                <div class="p-10 text-center text-white/80">
                  Belum ada link gambar.
                </div>
              @endif
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', () => {
                const img = document.getElementById('mainImg');
                const err = document.getElementById('imgError');
                if (!img || !err) return;
                img.addEventListener('error', () => {
                  err.classList.remove('hidden');
                  img.classList.add('opacity-0');
                });
              });
            </script>
          @endif
        </div>
      </div>

      <aside class="lg:col-span-4 {{ $glass }} p-5">
        <p class="text-xs uppercase tracking-[0.35em] text-white/70">Detail</p>
        <h1 class="mt-2 text-xl font-extrabold text-white">{{ $galeri->judul }}</h1>

        <p class="mt-3 text-sm text-white/75 leading-relaxed">
          {{ $galeri->deskripsi ?: '—' }}
        </p>

        <div class="mt-5 space-y-2 text-xs text-white/75">
          <div class="flex justify-between gap-3"><span>Kategori</span><span class="font-semibold">{{ $kategori }}</span></div>
          <div class="flex justify-between gap-3"><span>Seksi</span><span class="font-semibold text-right">{{ $seksi }}</span></div>
          <div class="flex justify-between gap-3"><span>Tanggal</span><span class="font-semibold">{{ $tanggal }}</span></div>
          <div class="flex justify-between gap-3"><span>Tipe</span><span class="font-semibold uppercase">{{ $type }}</span></div>
        </div>

        <div class="mt-6 flex items-center gap-2">
          <a href="{{ route('galeri.index') }}"
             class="rounded-2xl border border-white/15 bg-white/10 px-4 py-2 text-xs font-extrabold text-white hover:bg-white/15">
            ← Kembali
          </a>

          @if(!empty($action))
            <a href="{{ $action }}" target="_blank" rel="noreferrer"
               class="rounded-2xl px-4 py-2 text-xs font-extrabold text-[#13392f] hover:brightness-110"
               style="background: var(--accent);">
              {{ $type === 'image' ? 'Unduh' : 'Buka' }}
            </a>
          @endif
        </div>

        @if(!empty($gdriveMore))
          <a href="{{ $gdriveMore }}" target="_blank" rel="noopener"
             class="mt-3 inline-flex w-full items-center justify-center rounded-2xl border border-white/14 bg-white/10 px-4 py-2 text-xs font-extrabold text-white hover:bg-white/15">
            Lihat Foto Lainnya di Google Drive →
          </a>
        @endif

        {{-- DEBUG (kalau masih bandel, buka ini) --}}
        {{--
        <div class="mt-4 text-[10px] text-white/35 break-all">
          url_file(DB): {{ $galeri->url_file }} <br>
          driveId: {{ $driveId }} <br>
          fileSrc: {{ $fileSrc }} <br>
          action: {{ $action }} <br>
          gdrive_url: {{ $galeri->gdrive_url }}
        </div>
        --}}
      </aside>
    </div>
  </div>
</div>
</x-front-layout>
