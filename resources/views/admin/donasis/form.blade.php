@csrf
@if(isset($donasi) && $donasi->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="judul" value="{{ old('judul', $donasi->judul ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4" class="mt-1 w-full rounded border-gray-300">{{ old('deskripsi', $donasi->deskripsi ?? '') }}</textarea>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Target Dana</label>
            <input type="number" name="target_dana" value="{{ old('target_dana', $donasi->target_dana ?? '') }}"
                class="mt-1 w-full rounded border-gray-300" min="0" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai"
                value="{{ old('tanggal_selesai', optional($donasi->tanggal_selesai ?? null)->format('Y-m-d')) }}"
                class="mt-1 w-full rounded border-gray-300">
        </div>
    </div>
    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
