<x-front-layout>
    <section class="max-w-5xl mx-auto px-4 py-12 space-y-6">
        <div class="text-center space-y-2">
            <p class="text-sm uppercase tracking-[0.3rem] text-emerald-500">Riwayat Donasi</p>
            <h1 class="text-3xl font-semibold">Kontribusi Terbaik Anda</h1>
            <p class="text-gray-500">Catatan donasi akan muncul secara otomatis setelah transaksi tercatat.</p>
        </div>
        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-5 py-3 font-semibold text-slate-600">Program</th>
                        <th class="px-5 py-3 font-semibold text-slate-600">Jumlah</th>
                        <th class="px-5 py-3 font-semibold text-slate-600">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr class="border-t border-slate-100">
                            <td class="px-5 py-3">{{ $transaksi->donasi->judul }}</td>
                            <td class="px-5 py-3 font-semibold">Rp {{ number_format($transaksi->jumlah) }}</td>
                            <td class="px-5 py-3 capitalize">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $transaksi->status_pembayaran === 'berhasil' ? 'bg-emerald-100 text-emerald-700' : ($transaksi->status_pembayaran === 'gagal' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $transaksi->status_pembayaran }}
                                </span>
                            </td>
                            <td class="px-5 py-3">{{ $transaksi->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            {{ $transaksis->links() }}
        </div>
    </section>
</x-front-layout>
