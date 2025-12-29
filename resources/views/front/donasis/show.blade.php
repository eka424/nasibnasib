<x-front-layout>
    <section class="max-w-4xl mx-auto px-4 py-12 space-y-6">
        <div class="bg-white rounded-3xl shadow p-8 space-y-4">
            <p class="text-sm uppercase tracking-[0.3rem] text-emerald-500">Program Donasi</p>
            <h1 class="text-4xl font-semibold">{{ $donasi->judul }}</h1>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Target Rp {{ number_format($donasi->target_dana) }}</p>
                <p class="text-sm text-gray-500">Terkumpul Rp {{ number_format($donasi->dana_terkumpul) }}</p>
            </div>
            <div class="prose max-w-none">
                {!! nl2br(e($donasi->deskripsi)) !!}
            </div>

            @auth
                <form action="{{ route('donasi.transaksi', $donasi) }}" method="POST" class="space-y-3">
                    @csrf
                    <label class="block text-sm font-semibold text-gray-700">Jumlah Donasi (minimal 10.000)</label>
                    <input
                        type="number"
                        name="jumlah"
                        min="10000"
                        class="w-full rounded-2xl border-gray-200"
                        placeholder="100000"
                        value="{{ old('jumlah', 100000) }}"
                        required>
                    @error('jumlah')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500">Pembayaran akan dialihkan ke halaman Xendit (sandbox).</p>
                    <button type="submit" class="w-full rounded-full bg-emerald-600 px-6 py-3 text-white font-semibold">
                        Kirim Donasi
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-500">Login untuk berdonasi.</p>
            @endauth
        </div>
    </section>
</x-front-layout>
