<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Profil Masjid</h1>
    <form action="{{ route('admin.mosque_profile.update') }}" method="POST" class="bg-white shadow rounded p-6 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Masjid</label>
            <input type="text" name="nama" value="{{ old('nama', $profile->nama) }}"
                class="mt-1 w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat</label>
            <input type="text" name="alamat" value="{{ old('alamat', $profile->alamat) }}"
                class="mt-1 w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Sejarah</label>
            <textarea name="sejarah" class="mt-1 w-full rounded border-gray-300" rows="4">{{ old('sejarah', $profile->sejarah) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Visi</label>
            <textarea name="visi" class="mt-1 w-full rounded border-gray-300" rows="2">{{ old('visi', $profile->visi) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Misi</label>
            <textarea name="misi" class="mt-1 w-full rounded border-gray-300" rows="3">{{ old('misi', $profile->misi) }}</textarea>
        </div>
        <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
    </form>
</x-app-layout>
