@csrf
@if(isset($kegiatan) && $kegiatan->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('deskripsi', $kegiatan->deskripsi ?? '') }}</textarea>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="datetime-local" name="tanggal_mulai"
                value="{{ old('tanggal_mulai', optional($kegiatan->tanggal_mulai ?? null)->format('Y-m-d\TH:i')) }}"
                class="mt-1 w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="datetime-local" name="tanggal_selesai"
                value="{{ old('tanggal_selesai', optional($kegiatan->tanggal_selesai ?? null)->format('Y-m-d\TH:i')) }}"
                class="mt-1 w-full rounded border-gray-300">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $kegiatan->lokasi ?? '') }}"
            class="mt-1 w-full rounded border-gray-300">
    </div>
    <div>
  <label class="block text-sm font-medium text-gray-700">Poster (Link Google Drive)</label>

  <input
    type="url"
    name="poster"
    value="{{ old('poster', $kegiatan->poster ?? '') }}"
    placeholder="Tempel link Google Drive (akses: Anyone with the link)"
    class="mt-1 w-full rounded border-gray-300"
  >

  <p class="text-xs text-gray-500 mt-1">
    Pastikan di Google Drive: <b>Share → Anyone with the link → Viewer</b>
  </p>

  @php
    $posterUrl = old('poster', $kegiatan->poster ?? null);
  @endphp

  @if($posterUrl)
    <div class="mt-3 rounded-lg border border-gray-200 p-3">
      <div class="text-xs text-gray-500 mb-2">Preview (kalau tidak muncul, cek akses Drive):</div>
      <img
        src="{{ $posterUrl }}"
        alt="Poster"
        class="max-h-56 w-full object-cover rounded-md bg-gray-50"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
      >
      <div style="display:none" class="text-sm text-red-600">
        Preview gagal. Biasanya karena link Drive belum “Anyone with the link”.
      </div>
      <div class="mt-2 text-xs text-gray-600 break-all">
        Link: {{ $posterUrl }}
      </div>
    </div>
  @endif
</div>
<div class="mt-2">
  @php $posterUrl = old('poster', $kegiatan->poster ?? ''); @endphp

  @if($posterUrl)
    <img
      src="{{ $posterUrl }}"
      alt="Poster Preview"
      class="mt-2 h-40 w-auto rounded border"
      onerror="this.style.display='none'; document.getElementById('posterErr').style.display='block';"
    >
    <div id="posterErr" class="mt-2 text-sm text-red-600" style="display:none;">
      Preview gagal. Pastikan link Drive sudah <b>Anyone with the link</b> (Viewer) dan filenya benar-benar gambar (jpg/png).
    </div>
  @endif
</div>

    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
