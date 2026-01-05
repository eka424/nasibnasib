<x-app-layout>
    <div class="space-y-8">
        <div class="rounded-3xl border border-gray-200 bg-gradient-to-r from-gray-50 via-white to-gray-100 p-8 shadow-lg">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm uppercase tracking-[0.25em] text-gray-500 font-semibold">Riwayat</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Jawaban Yang Telah Diberikan</h1>
                    <p class="text-sm text-gray-600 max-w-2xl">
                        Arsip pertanyaan jamaah yang telah Anda tanggapi. Gunakan riwayat ini sebagai rujukan cepat atau bahan evaluasi.
                    </p>
                </div>
                <a href="{{ route('ustadz.pertanyaan.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-gray-800 bg-black px-5 py-3 text-sm font-semibold text-white shadow-lg hover:bg-gray-900 transition">
                    Kembali ke Pertanyaan Aktif
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow">
                <p class="text-sm text-gray-500 font-semibold">Total Pertanyaan Dijawab</p>
                <p class="mt-3 text-4xl font-bold text-gray-900">{{ $pertanyaans->total() }}</p>
                <p class="text-xs text-gray-500 mt-6">Seluruh waktu</p>
            </div>
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow">
                <p class="text-sm text-gray-500 font-semibold">Dalam 30 Hari</p>
                <p class="mt-3 text-4xl font-bold text-gray-900">{{ $pertanyaans->count() }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Daftar Pertanyaan Riwayat</h2>
                <p class="text-sm text-gray-500">Urutkan berdasarkan waktu jawaban terbaru</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs uppercase tracking-widest text-gray-400">Filter:</span>
                <button
                    class="rounded-full border border-gray-800 px-4 py-1 text-xs font-semibold text-gray-900 hover:bg-gray-900 hover:text-white">
                    Semua
                </button>
                <button
                    class="rounded-full border border-gray-300 px-4 py-1 text-xs font-semibold text-gray-600 hover:border-gray-600 hover:text-gray-900">
                    Fiqih
                </button>
                <button
                    class="rounded-full border border-gray-300 px-4 py-1 text-xs font-semibold text-gray-600 hover:border-gray-600 hover:text-gray-900">
                    Keluarga
                </button>
                <button
                    class="rounded-full border border-gray-300 px-4 py-1 text-xs font-semibold text-gray-600 hover:border-gray-600 hover:text-gray-900">
                    Muamalah
                </button>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="divide-y divide-gray-100">
                @forelse ($pertanyaans as $pertanyaan)
                    <article class="flex flex-col gap-4 px-6 py-5 md:flex-row md:items-center md:justify-between">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-800">
                                    <span class="h-2 w-2 rounded-full bg-gray-800"></span>
                                    {{ ucfirst($pertanyaan->kategori) }}
                                </span>
                                <span>Ditanyakan oleh <strong>{{ $pertanyaan->penanya->name }}</strong></span>
                            </div>
                            <p class="text-base font-semibold text-gray-900 leading-relaxed">
                                {{ $pertanyaan->pertanyaan }}
                            </p>
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-800 mb-1">Jawaban:</p>
                                {!! nl2br(e($pertanyaan->jawaban)) !!}
                            </div>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <p>Dijawab pada</p>
                            <p class="text-gray-900 font-semibold">{{ $pertanyaan->updated_at->format('d M Y') }}</p>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto mb-4 h-12 w-12 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center text-xl">
                            🗂️
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Belum ada jawaban tersimpan</h3>
                        <p class="mt-1 text-sm text-gray-500">Riwayat jawaban akan tampil setelah Anda menanggapi pertanyaan.</p>
                    </div>
                @endforelse
            </div>

            @if ($pertanyaans->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $pertanyaans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
