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
        <label class="block text-sm font-medium text-gray-700">Poster</label>
        <input type="file" name="poster" class="mt-1 w-full rounded border-gray-300">
        @if(isset($kegiatan) && $kegiatan->poster)
            <p class="text-sm text-gray-500 mt-1">Saat ini: {{ $kegiatan->poster }}</p>
        @endif
    </div>
    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
