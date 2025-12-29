<x-front-layout>
    @php
        // ===== Data normalization =====
        $judul = $perpustakaan->judul ?? 'Ebook Masjid';
        $penulis = $perpustakaan->penulis ?? null;
        $kategori = $perpustakaan->kategori ?? optional($perpustakaan->category)->name ?? null;
        $tahun = $perpustakaan->publish_year ?? $perpustakaan->tahun ?? null;
        $halaman = $perpustakaan->pages ?? $perpustakaan->halaman ?? null;

        $cover = $perpustakaan->cover ?? $perpustakaan->thumbnail ?? $perpustakaan->gambar ?? null;
        if ($cover && ! str_starts_with($cover, 'http')) {
            $cover = Storage::url($cover);
        }

        $file = $perpustakaan->file_ebook ?? null;
        $fileUrl = $file
            ? (str_starts_with($file, 'http') ? $file : Storage::url($file))
            : null;

        $desc = $perpustakaan->deskripsi ?? '';

        $viewCount = (int) ($perpustakaan->view_count ?? 0);
        $downloadCount = (int) ($perpustakaan->download_count ?? 0);

        $isPdf = $fileUrl ? str_ends_with(strtolower(parse_url($fileUrl, PHP_URL_PATH) ?? ''), '.pdf') : false;
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950/50 to-slate-900 text-slate-100 pb-[calc(92px+env(safe-area-inset-bottom))]">
        {{-- Fonts + helpers --}}
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap');
            body{font-family:Inter, ui-sans-serif, system-ui, -apple-system;}
            h1,h2,h3,.heading{font-family:Poppins, ui-sans-serif, system-ui, -apple-system;}
            .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
            .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}
        </style>

        {{-- HERO --}}
        <header class="relative overflow-hidden">
            <div aria-hidden class="absolute inset-0 -z-10">
                <img
                    src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80"
                    alt="Masjid"
                    class="h-full w-full object-cover opacity-35"
                    referrerpolicy="no-referrer"
                />
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-emerald-950/55 to-slate-900/95"></div>
            </div>

            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                {{-- Breadcrumb --}}
                <nav class="text-xs text-emerald-100/80">
                    <ol class="flex items-center gap-2">
                        <li><a class="hover:text-emerald-100" href="{{ route('home') }}">Beranda</a></li>
                        <li class="text-white/60">/</li>
                        <li><a class="hover:text-emerald-100" href="{{ route('perpustakaan.index') }}">Perpustakaan</a></li>
                        <li class="text-white/60">/</li>
                        <li class="font-semibold text-white line-clamp-2">Detail Ebook</li>
                    </ol>
                </nav>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200/25 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Ebook Masjid
                    </span>

                    @if($kategori)
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                            🏷️ {{ $kategori }}
                        </span>
                    @endif

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                        📘 Koleksi Digital
                    </span>
                </div>

                <h1 class="heading mt-3 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
                    {{ $judul }}
                </h1>
                @if($penulis)
                    <p class="mt-2 text-sm text-emerald-50/85">
                        oleh <span class="font-semibold text-emerald-100">{{ $penulis }}</span>
                    </p>
                @endif
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="mx-auto max-w-6xl px-4 pb-10 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-12">
                {{-- LEFT --}}
                <section class="lg:col-span-8">
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/95 text-slate-900 shadow-xl shadow-emerald-500/10">
                        {{-- Cover + Meta --}}
                        <div class="grid gap-0 sm:grid-cols-[220px_1fr]">
                            <div class="relative">
                                @if($cover)
                                    <img
                                        src="{{ $cover }}"
                                        alt="{{ $judul }}"
                                        class="h-64 w-full object-cover sm:h-full sm:min-h-[280px]"
                                        loading="lazy"
                                        referrerpolicy="no-referrer"
                                    />
                                @else
                                    <div class="grid h-64 w-full place-items-center bg-gradient-to-br from-emerald-100 to-emerald-200 sm:h-full sm:min-h-[280px]">
                                        <span class="text-5xl">📚</span>
                                    </div>
                                @endif
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                            </div>

                            <div class="p-5 sm:p-7">
                                <div class="flex flex-wrap gap-2">
                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                                        <span aria-hidden>👁️</span>
                                        <span class="text-slate-500">Dilihat:</span>
                                        <span class="font-semibold text-slate-900">{{ number_format($viewCount, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                                        <span aria-hidden>⬇️</span>
                                        <span class="text-slate-500">Diunduh:</span>
                                        <span class="font-semibold text-slate-900">{{ number_format($downloadCount, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                                        <span aria-hidden>📄</span>
                                        <span class="text-slate-500">Halaman:</span>
                                        <span class="font-semibold text-slate-900">{{ $halaman ?? '-' }}</span>
                                    </div>

                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                                        <span aria-hidden>📅</span>
                                        <span class="text-slate-500">Tahun:</span>
                                        <span class="font-semibold text-slate-900">{{ $tahun ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-600">Ringkasan</p>
                                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700">
                                        {{ $desc ?: 'Belum ada deskripsi.' }}
                                    </p>
                                </div>

                                {{-- Desktop actions --}}
                                <div class="mt-5 hidden flex-wrap gap-2 sm:flex">
                                    <a
                                        href="{{ $fileUrl ?: '#' }}"
                                        target="_blank"
                                        class="{{ $fileUrl ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'cursor-not-allowed bg-slate-200 text-slate-500' }}
                                               inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold shadow-sm transition"
                                    >
                                        ⬇️ Unduh Ebook
                                    </a>

                                    <button
                                        type="button"
                                        id="copyLinkBtnDesktop"
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50"
                                    >
                                        🔗 Salin Link
                                    </button>

                                    @if($fileUrl)
                                        <a
                                            href="{{ $fileUrl }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100"
                                        >
                                            📖 Baca Online
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="border-t border-slate-200 p-5 sm:p-7">
                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-600">Preview</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Jika file PDF, preview muncul langsung di halaman.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                @if($fileUrl && $isPdf)
                                    <iframe
                                        title="Preview PDF"
                                        src="{{ $fileUrl }}"
                                        class="h-[70vh] w-full"
                                    ></iframe>
                                @else
                                    <div class="p-6 text-sm text-slate-600">
                                        Preview tersedia untuk PDF. Klik <span class="font-semibold text-slate-800">Baca Online</span> untuk membuka file di tab baru.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- RIGHT --}}
                <aside class="lg:col-span-4">
                    <div class="sticky top-6 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-xl shadow-emerald-500/10 backdrop-blur">
                            <p class="text-sm font-semibold text-white">Aksi Cepat</p>
                            <p class="mt-1 text-xs text-emerald-100/80">Unduh atau simpan link untuk dibaca nanti.</p>

                            <div class="mt-4 grid gap-2">
                                <a
                                    href="{{ $fileUrl ?: '#' }}"
                                    target="_blank"
                                    class="{{ $fileUrl ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'cursor-not-allowed bg-white/20 text-white/50' }}
                                           inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold shadow-sm transition"
                                >
                                    ⬇️ Unduh Ebook
                                </a>

                                @if($fileUrl)
                                    <a
                                        href="{{ $fileUrl }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                                    >
                                        📖 Baca Online
                                    </a>
                                @endif

                                <button
                                    type="button"
                                    id="copyLinkBtnSide"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10"
                                >
                                    🔗 Salin Link
                                </button>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-xl shadow-emerald-500/10 backdrop-blur">
                            <p class="text-sm font-semibold text-white">Catatan</p>
                            <ul class="mt-3 space-y-2 text-sm text-emerald-50/90">
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Gunakan mode baca malam untuk nyaman.</li>
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Share link agar jamaah lain ikut belajar.</li>
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Pastikan koneksi stabil saat unduh.</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        {{-- MOBILE STICKY CTA --}}
        <div class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-slate-950/75 p-3 backdrop-blur lg:hidden">
            <div class="mx-auto max-w-6xl px-1">
                <div class="grid grid-cols-2 gap-2">
                    <a
                        href="{{ $fileUrl ?: '#' }}"
                        target="_blank"
                        class="{{ $fileUrl ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'cursor-not-allowed bg-white/20 text-white/50' }}
                               w-full rounded-2xl px-4 py-3 text-center text-sm font-semibold transition"
                    >
                        ⬇️ Unduh
                    </a>

                    <button
                        type="button"
                        id="copyLinkBtnMobile"
                        class="w-full rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                    >
                        🔗 Share
                    </button>
                </div>
            </div>
        </div>

        {{-- Copy link scripts --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const copy = async () => {
                    const url = window.location.href;
                    try {
                        await navigator.clipboard.writeText(url);
                        toast('Link tersalin ✅');
                    } catch (e) {
                        const tmp = document.createElement('input');
                        tmp.value = url;
                        document.body.appendChild(tmp);
                        tmp.select();
                        document.execCommand('copy');
                        document.body.removeChild(tmp);
                        toast('Link tersalin ✅');
                    }
                };

                function toast(text) {
                    const t = document.createElement('div');
                    t.textContent = text;
                    t.className =
                        'fixed left-1/2 top-6 z-[80] -translate-x-1/2 rounded-2xl border border-white/10 bg-slate-950/90 px-4 py-2 text-sm font-semibold text-white shadow-xl backdrop-blur';
                    document.body.appendChild(t);
                    setTimeout(() => t.remove(), 1300);
                }

                document.getElementById('copyLinkBtnDesktop')?.addEventListener('click', copy);
                document.getElementById('copyLinkBtnSide')?.addEventListener('click', copy);
                document.getElementById('copyLinkBtnMobile')?.addEventListener('click', copy);
            });
        </script>
    </div>
</x-front-layout>
