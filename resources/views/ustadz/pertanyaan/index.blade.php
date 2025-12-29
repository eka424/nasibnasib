<x-app-layout>
    <div class="space-y-8">
        <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-r from-gray-50 via-white to-gray-100 p-8 text-gray-900 shadow-lg">
            <div class="absolute inset-y-0 right-0 flex items-center opacity-40">
                <svg width="220" height="220" viewBox="0 0 220 220" fill="none">
                    <circle cx="110" cy="110" r="100" stroke="#d1d5db" stroke-dasharray="6 14" stroke-opacity="0.8" />
                </svg>
            </div>
            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm uppercase tracking-[0.4em] font-semibold text-gray-500">Panel Ustadz</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight">Pertanyaan Ditugaskan</h1>
                    <p class="text-gray-700 text-sm sm:text-base max-w-xl">
                        Tanggapi pertanyaan jamaah dengan jawaban terbaik. Gunakan panel ini untuk mengelola antrean yang menunggu respons Anda.
                    </p>
                </div>
                <div class="flex flex-col gap-2 text-sm">
                    <p class="text-gray-600">Butuh kilas balik?</p>
                    <a href="{{ route('ustadz.pertanyaan.riwayat') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-800 bg-gray-100 px-5 py-3 font-semibold text-gray-900 shadow-lg shadow-gray-400/40 transition hover:bg-white">
                        Lihat Riwayat
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="flex flex-col justify-between rounded-3xl border border-gray-200 bg-white p-5 shadow">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Total Ditugaskan</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">{{ $stats['total'] ?? $pertanyaans->total() }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-6">Menunggu jawaban Anda saat ini</p>
                </div>
                <div class="flex flex-col justify-between rounded-3xl border border-gray-200 bg-white p-5 shadow">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Belum Dijawab</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">{{ $stats['waiting'] ?? $pertanyaans->count() }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-6">Aktif pada halaman ini</p>
                </div>
                <div class="flex flex-col justify-between rounded-3xl border border-gray-200 bg-white p-5 shadow">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Rata-rata Respons</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">
                            {{ isset($stats['avg_hours']) ? $stats['avg_hours'].' Jam' : '—' }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 mt-6">Hitung dari pertanyaan terjawab</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" class="flex w-full flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm lg:flex-row lg:items-center">
                <div class="flex-1">
                    <label class="text-sm font-semibold text-gray-500">Cari Pertanyaan</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Ketik kata kunci pertanyaan..." class="w-full rounded-2xl border border-gray-300 bg-gray-50 px-10 py-2 text-sm focus:border-gray-900 focus:ring-gray-900" />
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase">Kategori</span>
                    @foreach ($categoryOptions as $category)
                        <button name="kategori" value="{{ $category }}" type="submit"
                            class="inline-flex items-center rounded-full border px-4 py-1 text-xs font-semibold {{ ($kategori ?? '') === $category ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 text-gray-600 hover:border-gray-500 hover:text-gray-800' }}">
                            {{ ucfirst($category) }}
                        </button>
                    @endforeach
                    <a href="{{ route('ustadz.pertanyaan.index') }}"
                        class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold text-gray-500 hover:border-gray-700 hover:text-gray-900">
                        Reset
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-2 gap-3 lg:w-[320px]">
                <div class="rounded-2xl border border-gray-200 bg-white p-3 text-center shadow-sm">
                    <p class="text-xs text-gray-500">Respons Rata-rata</p>
                    <p class="text-lg font-semibold text-gray-900">{{ isset($stats['avg_hours']) ? $stats['avg_hours'].' Jam' : '—' }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-3 text-center shadow-sm">
                    <p class="text-xs text-gray-500">Sudah Dijawab</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stats['answered'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <x-pertanyaan-card>
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Pertanyaan</h2>
                    <p class="text-sm text-gray-500">Tinjau dan berikan jawaban terbaik Anda</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                    {{ $pertanyaans->total() }} Pertanyaan
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($pertanyaans as $pertanyaan)
                    <div class="grid gap-4 px-6 py-5 md:grid-cols-[1fr,200px] items-start">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-800">
                                    <span class="h-2 w-2 rounded-full bg-gray-800"></span>
                                    {{ ucfirst($pertanyaan->kategori) }}
                                </span>
                                <p class="text-sm text-gray-500">Dari <strong>{{ $pertanyaan->penanya->name ?? 'Anonim' }}</strong></p>
                                <span class="text-xs text-gray-400">ID #{{ \Illuminate\Support\Str::padLeft($pertanyaan->id, 4, '0') }}</span>
                            </div>
                            <p class="text-base font-semibold leading-relaxed text-gray-900">
                                {{ $pertanyaan->pertanyaan }}
                            </p>
                        </div>

                        <div class="flex md:flex-col gap-3 md:justify-center md:items-end items-start">
                            <a href="{{ route('ustadz.pertanyaan.edit', $pertanyaan) }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-black px-4 py-2 text-sm font-medium text-white shadow hover:bg-gray-800 transition">
                                Jawab Sekarang
                            </a>
                            <span class="inline-flex items-center justify-center rounded-2xl border border-dashed border-gray-300 px-4 py-2 text-sm font-medium text-gray-500">
                                Penghapusan/arsip hanya oleh admin
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-20 text-center">
                        <div class="mx-auto mb-4 h-12 w-12 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center text-xl">
                            ✨
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Belum ada pertanyaan</h3>
                        <p class="mt-1 text-sm text-gray-500">Pertanyaan yang ditugaskan kepada Anda akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            @if ($pertanyaans->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $pertanyaans->links() }}
                </div>
            @endif
        </x-pertanyaan-card>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Ringkasan Akses Ustadz</h3>
                <ul class="mt-3 space-y-1 text-sm text-gray-600">
                    <li>• Fokus menjawab pertanyaan jamaah di /ustadz-pertanyaan</li>
                    <li>• Bisa lihat detail pertanyaan, klaim, dan kirim jawaban</li>
                    <li>• Simpan draf sebelum publikasi jawaban</li>
                    <li>• Profil (nama/bio/spesialisasi) tampil di jawaban</li>
                </ul>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Batasan Akses</h3>
                <ul class="mt-3 space-y-1 text-sm text-gray-600">
                    <li>• Tidak bisa kelola Artikel/Kegiatan/Donasi/Galeri/Perpustakaan</li>
                    <li>• Tidak bisa ubah pengguna/peran atau pengaturan sistem</li>
                    <li>• Tidak menghapus/mengedit pertanyaan jamaah (wewenang admin)</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
