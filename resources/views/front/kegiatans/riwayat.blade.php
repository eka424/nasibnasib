<x-front-layout>
    <section class="max-w-5xl mx-auto px-4 py-12 space-y-6">
        <div class="text-center space-y-2">
            <p class="text-sm uppercase tracking-[0.3rem] text-emerald-500">Riwayat Saya</p>
            <h1 class="text-3xl font-semibold">Pendaftaran Kegiatan</h1>
            <p class="text-gray-500">Pantau status pendaftaran seluruh kegiatan yang pernah Anda ikuti.</p>
        </div>
        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-5 py-3 font-semibold text-slate-600">Kegiatan</th>
                        <th class="px-5 py-3 font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-600">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendaftaranKegiatans as $pendaftaran)
                        <tr class="border-t border-slate-100">
                            <td class="px-5 py-3">{{ $pendaftaran->kegiatan->nama_kegiatan }}</td>
                            <td class="px-5 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize
                                    {{ $pendaftaran->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : ($pendaftaran->status === 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $pendaftaran->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3">{{ $pendaftaran->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            {{ $pendaftaranKegiatans->links() }}
        </div>
    </section>
</x-front-layout>
