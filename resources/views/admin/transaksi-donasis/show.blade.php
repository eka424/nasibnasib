<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-2">
        <h1 class="text-2xl font-semibold">Transaksi Donasi</h1>
        <p>Jamaah: {{ $transaksi->user->name }}</p>
        <p>Donasi: {{ $transaksi->donasi->judul }}</p>
        <p>Jumlah: Rp {{ number_format($transaksi->jumlah) }}</p>
        <p>Status: <span class="capitalize">{{ $transaksi->status_pembayaran }}</span></p>
        <p>Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}</p>
    </div>
</x-app-layout>
