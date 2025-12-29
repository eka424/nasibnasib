<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ $pendaftaran->user->name }}</h1>
            <p class="text-sm text-gray-500">{{ $pendaftaran->kegiatan->nama_kegiatan }}</p>
        </div>
        <form action="{{ route('admin.pendaftaran-kegiatans.update', $pendaftaran) }}" method="POST"
            class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full rounded border-gray-300">
                    @foreach (['menunggu', 'diterima', 'ditolak'] as $status)
                        <option value="{{ $status }}" @selected($pendaftaran->status === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
        </form>
    </div>
</x-app-layout>
