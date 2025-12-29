<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
            <h1 class="text-2xl font-bold text-gray-800">Struktur Masjid</h1>
            <a href="{{ route('mosque.struktur') }}" target="_blank"
               class="rounded bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300">
                Lihat Publik
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM TAMBAH --}}
        <form method="POST" action="{{ route('admin.mosque_structure.store') }}" class="bg-white shadow rounded p-5 mb-6 grid md:grid-cols-4 gap-4">
            @csrf
            <div>
                <label class="text-sm font-semibold">Parent</label>
                <select name="parent_id" class="mt-1 w-full rounded border-gray-300">
                    <option value="">(Root)</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}">{{ $p->jabatan }} @if($p->nama) — {{ $p->nama }} @endif</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold">Jabatan</label>
                <input name="jabatan" class="mt-1 w-full rounded border-gray-300" required>
            </div>
            <div>
                <label class="text-sm font-semibold">Nama (opsional)</label>
                <input name="nama" class="mt-1 w-full rounded border-gray-300">
            </div>
            <div>
                <label class="text-sm font-semibold">Urutan</label>
                <input type="number" name="urutan" value="0" class="mt-1 w-full rounded border-gray-300">
            </div>
            <div class="md:col-span-4">
                <button class="rounded bg-emerald-600 px-4 py-2 text-white">Tambah</button>
            </div>
        </form>

        {{-- LIST EDIT --}}
        <div class="bg-white shadow rounded">
            <div class="p-4 border-b font-semibold">Daftar Node</div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-[900px] w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2 pr-4">ID</th>
                            <th class="py-2 pr-4">Parent</th>
                            <th class="py-2 pr-4">Jabatan</th>
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Urutan</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($nodes as $n)
                            <tr class="align-top">
                                <td class="py-3 pr-4">{{ $n->id }}</td>
                                <td class="py-3 pr-4">{{ $n->parent_id ?? '-' }}</td>

                                <td class="py-3 pr-4">
                                    <form method="POST" action="{{ route('admin.mosque_structure.update', $n) }}" class="grid grid-cols-1 gap-2">
                                        @csrf
                                        @method('PUT')

                                        <select name="parent_id" class="rounded border-gray-300">
                                            <option value="">(Root)</option>
                                            @foreach($parents as $p)
                                                <option value="{{ $p->id }}" @selected($n->parent_id == $p->id)>
                                                    {{ $p->jabatan }} @if($p->nama) — {{ $p->nama }} @endif
                                                </option>
                                            @endforeach
                                        </select>

                                        <input name="jabatan" value="{{ $n->jabatan }}" class="rounded border-gray-300" />
                                </td>

                                <td class="py-3 pr-4">
                                        <input name="nama" value="{{ $n->nama }}" class="rounded border-gray-300 w-full" />
                                </td>

                                <td class="py-3 pr-4">
                                        <input type="number" name="urutan" value="{{ $n->urutan }}" class="rounded border-gray-300 w-full" />
                                </td>

                                <td class="py-3 space-y-2">
                                        <button class="rounded bg-emerald-600 px-3 py-1 text-white">Simpan</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.mosque_structure.destroy', $n) }}"
                                          onsubmit="return confirm('Hapus node ini? Anak-anaknya juga akan ikut terhapus kalau parent dihapus (tergantung relasi). Lanjut?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded bg-red-600 px-3 py-1 text-white">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
