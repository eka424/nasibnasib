<x-app-layout>
  <div class="bg-white shadow rounded p-6 space-y-4">
    <div class="flex items-start justify-between gap-3">
      <div class="min-w-0">
        <h1 class="text-3xl font-semibold leading-tight">{{ $galeri->judul }}</h1>
        <p class="mt-1 text-sm text-gray-500 break-all">{{ $galeri->url_file }}</p>
      </div>

      <a href="{{ route('admin.galeris.edit', $galeri) }}"
         class="shrink-0 rounded bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
        Edit
      </a>
    </div>

    <div class="grid gap-2 text-sm text-gray-600 sm:grid-cols-3">
      <div class="rounded-lg border bg-gray-50 p-3">
        <div class="text-xs font-semibold text-gray-500">Kategori</div>
        <div class="mt-1 font-semibold text-gray-900">{{ $galeri->kategori ?? '-' }}</div>
      </div>

      <div class="rounded-lg border bg-gray-50 p-3">
        <div class="text-xs font-semibold text-gray-500">Seksi</div>
        <div class="mt-1 font-semibold text-gray-900">{{ $galeri->seksi ?? '-' }}</div>
      </div>

      <div class="rounded-lg border bg-gray-50 p-3">
        <div class="text-xs font-semibold text-gray-500">Tipe</div>
        <div class="mt-1 font-semibold text-gray-900">{{ $galeri->tipe }}</div>
      </div>
    </div>

    @if(!empty($galeri->deskripsi))
      <div class="rounded-lg border bg-white p-4">
        <div class="text-xs font-semibold uppercase tracking-wider text-gray-500">Deskripsi</div>
        <p class="mt-2 text-sm text-gray-700 whitespace-pre-line">{{ $galeri->deskripsi }}</p>
      </div>
    @endif

    @php
      // kalau masih ada data lama local storage
      $isLocal = ($galeri->url_file ?? null) && !str_starts_with($galeri->url_file, 'http');
      $mediaUrl = $isLocal ? \Illuminate\Support\Facades\Storage::url($galeri->url_file) : ($galeri->url_file ?? '');

      $lower = strtolower($mediaUrl);

      // deteksi embed video (hasil normalize controller kamu)
      $isYoutubeEmbed = str_contains($mediaUrl, 'youtube.com/embed/');
      $isDrivePreview = str_contains($mediaUrl, 'drive.google.com') && str_contains($mediaUrl, '/preview');

      // mp4 direct fallback
      $isMp4 = str_ends_with(parse_url($mediaUrl, PHP_URL_PATH) ?? '', '.mp4')
              || str_contains($lower, '.mp4');

      // image detection fallback (kalau ternyata url_file image direct)
      $isImageUrl = preg_match('~\.(jpg|jpeg|png|webp|gif)$~i', parse_url($mediaUrl, PHP_URL_PATH) ?? '') === 1;
    @endphp

    <div class="rounded-xl border bg-white p-4">
      <div class="flex items-center justify-between gap-2">
        <div>
          <div class="text-sm font-semibold text-gray-900">Preview</div>
          <div class="text-xs text-gray-500">Tampil otomatis sesuai tipe & link.</div>
        </div>

        @if($mediaUrl)
          <a href="{{ $mediaUrl }}" target="_blank" rel="noreferrer"
             class="rounded-lg border px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
            Buka di Tab Baru
          </a>
        @endif
      </div>

      <div class="mt-4 overflow-hidden rounded-xl border bg-gray-50">
        {{-- IMAGE --}}
        @if($galeri->tipe === 'image' || $isImageUrl)
          <img
            src="{{ $mediaUrl }}"
            alt="{{ $galeri->judul }}"
            class="w-full max-h-[75vh] object-contain bg-white"
            loading="lazy"
            referrerpolicy="no-referrer"
          />

        {{-- VIDEO: YouTube embed --}}
        @elseif($galeri->tipe === 'video' && $isYoutubeEmbed)
          <iframe
            src="{{ $mediaUrl }}"
            class="w-full h-[70vh]"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer"
            title="YouTube Video"
          ></iframe>

        {{-- VIDEO: Drive preview --}}
        @elseif($galeri->tipe === 'video' && $isDrivePreview)
          <iframe
            src="{{ $mediaUrl }}"
            class="w-full h-[70vh]"
            loading="lazy"
            referrerpolicy="no-referrer"
            title="Drive Video"
          ></iframe>

        {{-- VIDEO: direct mp4 --}}
        @elseif($galeri->tipe === 'video' && $isMp4)
          <video controls class="w-full max-h-[75vh] bg-black">
            <source src="{{ $mediaUrl }}" type="video/mp4">
            Browser kamu tidak mendukung tag video.
          </video>

        {{-- FALLBACK --}}
        @else
          <div class="p-6 text-sm text-gray-600">
            Preview tidak tersedia untuk link ini.
            @if($mediaUrl)
              Silakan klik <span class="font-semibold text-gray-900">Buka di Tab Baru</span>.
            @endif
          </div>
        @endif
      </div>
    </div>

    <div class="flex items-center justify-between gap-2 pt-2">
      <a href="{{ route('admin.galeris.index') }}"
         class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        ← Kembali
      </a>

      <form action="{{ route('admin.galeris.destroy', $galeri) }}" method="POST"
            onsubmit="return confirm('Hapus media ini?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
          Hapus
        </button>
      </form>
    </div>
  </div>
</x-app-layout>
