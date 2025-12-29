<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-4">
        <h1 class="text-3xl font-semibold">{{ $donasi->judul }}</h1>
        <p class="text-sm text-gray-500">Target Rp {{ number_format($donasi->target_dana) }}</p>
        <p class="text-sm text-gray-600">Terkumpul Rp {{ number_format($donasi->dana_terkumpul) }}</p>
        <div class="prose max-w-none">
            {!! nl2br(e($donasi->deskripsi)) !!}
        </div>

        <div>
            <h2 class="text-lg font-semibold">Transaksi</h2>
            <ul class="text-sm text-gray-700 space-y-1">
                @foreach ($donasi->transaksiDonasis as $transaksi)
                    <li>
                        {{ $transaksi->user->name }} - Rp {{ number_format($transaksi->jumlah) }}
                        ({{ $transaksi->status_pembayaran }})
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
