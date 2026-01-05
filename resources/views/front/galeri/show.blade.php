<x-front-layout>
@php
    $bg     = '#13392f';
    $accent = '#E7B14B';
    $glass  = 'rounded-[26px] border border-white/12 bg-white/8 shadow-[0_18px_60px_-45px_rgba(0,0,0,0.55)] backdrop-blur';

    // fallback hanya kalau url_file kosong / error load
    $fallbackImg = 'https://images.unsplash.com/photo-1496302662116-35cc4f36df92?auto=format&fit=crop&w=1200&q=80';

    $getDriveId = function (?string $url): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (preg_match('~drive\.google\.com/file/d/([^/]+)~', $url, $m)) return $m[1];

        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $q);
            if (!empty($q['id'])) return $q['id'];
        }

        if (preg_match('~[?&]id=([^&]+)~', $url, $m)) return $m[1];
        return null;
    };

    $driveView = function (?string $url) use ($getDriveId): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (str_contains($url, 'drive.google.com/uc?') && preg_match('~[?&]id=([^&]+)~', $url, $m)) {
            return "https://drive.google.com/uc?export=view&id={$m[1]}";
        }

        $id = $getDriveId($url);
        return $id ? "https://drive.google.com/uc?export=view&id={$id}" : $url;
    };

    $driveDownload = function (?string $url) use ($getDriveId): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (str_contains($url, 'drive.google.com/uc?') && preg_match('~[?&]id=([^&]+)~', $url, $m)) {
            return "https://drive.google.com/uc?export=download&id={$m[1]}";
        }

        $id = $getDriveId($url);
        return $id ? "https://drive.google.com/uc?export=download&id={$id}" : $url;
    };

    $drivePreview = function (?string $url) use ($getDriveId): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (str_contains($url, 'drive.google.com') && str_contains($url, '/preview')) return $url;

        $id = $getDriveId($url);
        return $id ? "https://drive.google.com/file/d/{$id}/preview" : $url;
    };

    $youtubeId = function (?string $url): ?string {
        $url = trim((string)$url);
        if ($url === '') return null;

        if (preg_match('~youtu\.be/([^?&/]+)~', $url, $m)) return $m[1];
        if (preg_match('~youtube\.com/watch\?v=([^&]+)~', $url, $m)) return $m[1];
        if (preg_match('~youtube\.com/embed/([^?&/]+)~', $url, $m)) return $m[1];

        return null;
    };

    $youtubeEmbed = function (?string $url) use ($youtubeId): ?string {
        $id = $youtubeId($url);
        return $id ? "https://www.youtube.com/embed/{$id}" : $url;
    };

    // ===== normalize sumber =====
    $raw  = $galeri->url_file ?? '';
    $type = $galeri->tipe ?? 'image';

    $url = $raw;
    if ($url && !str_starts_with($url, 'http')) {
        $url = \Illuminate\Support\Facades\Storage::url($url);
    }

    $fileSrc = $url ?: $fallbackImg; // untuk img/iframe/video
    $action  = $url ?: '#';          // tombol unduh/buka

    // YouTube
    if ($type === 'video' && $url && $youtubeId($url)) {
        $fileSrc = $youtubeEmbed($url);
        $action  = $url;
    }

    // Drive
    if ($url && str_contains($url, 'drive.google.com')) {
        if ($type === 'image') {
            $fileSrc = $driveView($url) ?: $fileSrc;
            $action  = $driveDownload($url) ?: $action;
        } else {
            $fileSrc = $drivePreview($url) ?: $fileSrc;
            $action  = $url ?: $action;
        }
    }

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
            @php
              $isEmbed = str_contains($fileSrc, 'youtube.com/embed/')
                        || (str_contains($fileSrc, 'drive.google.com') && str_contains($fileSrc, '/preview'));
            @endphp

            @if($isEmbed)
              <iframe
                src="{{ $fileSrc }}"
                class="w-full h-[60vh]"
                loading="lazy"
                referrerpolicy="no-referrer"
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              ></iframe>
            @else
              <video src="{{ $fileSrc }}" controls class="w-full max-h-[60vh]"></video>
            @endif
          @else
            <img
              src="{{ $fileSrc }}"
              alt="{{ $galeri->judul }}"
              class="w-full max-h-[65vh] object-contain"
              referrerpolicy="no-referrer"
              onerror="this.onerror=null;this.src='{{ $fallbackImg }}';"
            />
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

          <a href="{{ $action }}" target="_blank" rel="noreferrer"
             class="rounded-2xl px-4 py-2 text-xs font-extrabold text-[#13392f] hover:brightness-110"
             style="background: var(--accent);">
            {{ $type === 'image' ? 'Unduh' : 'Buka' }}
          </a>
        </div>

        {{-- debug (hapus kalau sudah beres) --}}
        <div class="mt-4 text-[10px] text-white/35 break-all">
          url_file: {{ $galeri->url_file }} <br>
          fileSrc: {{ $fileSrc }}
        </div>
      </aside>
    </div>
  </div>
</div>
</x-front-layout>
