<x-front-layout>
    @php
        $poster = $kegiatan->poster ?? null;
        if ($poster && ! str_starts_with($poster, 'http')) {
            $poster = Storage::url($poster);
        }
        $poster = $poster ?: 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';

        $startLabel = optional($kegiatan->tanggal_mulai)->locale('id')->translatedFormat('l, d M Y • H:i') ?? '-';
        $endLabel = $kegiatan->tanggal_selesai
            ? optional($kegiatan->tanggal_selesai)->locale('id')->translatedFormat('l, d M Y • H:i')
            : null;

        $kategori = $kegiatan->kategori ?? 'Program Masjid';
        $biaya = $kegiatan->biaya ?? 0;

        $formatRupiah = function ($n) {
            $n = (int) ($n ?? 0);
            return $n <= 0 ? 'Gratis' : 'Rp ' . number_format($n, 0, ',', '.');
        };

        $status = 'Terbuka';
        if (! empty($kegiatan->tanggal_selesai) && $kegiatan->tanggal_selesai->isPast()) {
            $status = 'Selesai';
        }

        $wa = $kegiatan->contact_whatsapp ?? $kegiatan->whatsapp ?? null;
        $waNum = $wa ? preg_replace('/\D+/', '', $wa) : null;
        $waLink = $waNum
            ? 'https://wa.me/' . $waNum . '?text=' . urlencode("Assalamu'alaikum, saya ingin tanya tentang kegiatan: {$kegiatan->nama_kegiatan}")
            : null;

        $gcalLink = null;
        try {
            $start = $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->copy()->utc() : null;
            $end = $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->copy()->utc() : ($start ? $start->copy()->addHours(2) : null);

            if ($start && $end) {
                $fmt = fn($d) => $d->format('Ymd\THis\Z');
                $gcalLink = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                    . '&text=' . urlencode($kegiatan->nama_kegiatan)
                    . '&dates=' . $fmt($start) . '/' . $fmt($end)
                    . '&details=' . urlencode('Info kegiatan masjid')
                    . '&location=' . urlencode($kegiatan->lokasi ?? '');
            }
        } catch (\Throwable $e) {
            $gcalLink = null;
        }
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950/50 to-slate-900 text-slate-100">
        {{-- Fonts (opsional) --}}
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap');
            body{font-family:Inter, ui-sans-serif, system-ui, -apple-system;}
            .font-heading{font-family:Poppins, ui-sans-serif, system-ui, -apple-system;}
        </style>

        {{-- Top bar --}}
        <div class="fixed inset-x-0 top-0 z-50 h-1 bg-white/10">
            <div class="h-1 w-full bg-emerald-400"></div>
        </div>

        {{-- HERO --}}
        <header class="relative overflow-hidden pt-10 sm:pt-14">
            <div aria-hidden class="absolute inset-0 -z-10">
                <img src="{{ $poster }}" alt="{{ $kegiatan->nama_kegiatan }}"
                    class="h-full w-full object-cover opacity-35" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-emerald-950/55 to-slate-900/95"></div>
            </div>

            <div class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                {{-- Breadcrumb --}}
                <nav class="text-xs text-emerald-100/80">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-100">Beranda</a></li>
                        <li class="text-white/60">/</li>
                        <li><a href="{{ route('kegiatan.index') }}" class="hover:text-emerald-100">Kegiatan</a></li>
                        <li class="text-white/60">/</li>
                        <li class="font-semibold text-white line-clamp-1">Detail</li>
                    </ol>
                </nav>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        {{ $kategori }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                        ⏱ {{ $endLabel ? 'Sesi' : 'Jadwal' }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                        💳 {{ $formatRupiah($biaya) }}
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/90">
                        ✅ {{ $status }}
                    </span>
                </div>

                <h1 class="font-heading mt-3 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
                    {{ $kegiatan->nama_kegiatan }}
                </h1>

                <div class="mt-3 grid gap-2 text-sm text-emerald-50/90 sm:grid-cols-2">
                    <div class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur">
                        🗓 <span class="font-semibold">{{ $startLabel }}</span>
                        @if($endLabel)
                            <span class="text-white/60">— {{ $endLabel }}</span>
                        @endif
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur">
                        📍 <span class="font-semibold line-clamp-1">{{ $kegiatan->lokasi ?? 'Lokasi menyusul' }}</span>
                    </div>
                </div>

                {{-- Quick actions (desktop) --}}
                <div class="mt-4 hidden flex-wrap gap-2 sm:flex">
                    @if($gcalLink)
                        <a href="{{ $gcalLink }}" target="_blank" rel="noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                            📅 Tambah ke Kalender
                        </a>
                    @endif

                    <button type="button" data-copy-link
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                        🔗 Salin Link
                    </button>

                    @if($waLink)
                        <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                            💬 WhatsApp Panitia
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-12">
                {{-- LEFT --}}
                <section class="lg:col-span-8">
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/95 text-slate-900 shadow-xl shadow-emerald-500/10">
                        <div class="border-b border-slate-200 bg-white px-5 py-4 sm:px-7">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600">
                                Detail Kegiatan
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                Informasi lengkap kegiatan & deskripsi.
                            </p>
                        </div>

                        <div class="px-5 py-6 sm:px-7">
                            <div class="prose max-w-none prose-slate prose-headings:font-bold prose-headings:text-slate-900 prose-a:text-emerald-700">
                                {!! nl2br(e($kegiatan->deskripsi ?? '')) !!}
                            </div>

                            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs text-slate-500">Kuota</p>
                                    <p class="mt-0.5 text-sm font-semibold text-slate-900">
                                        {{ !empty($kegiatan->kuota) ? $kegiatan->kuota.' orang' : 'Terbuka' }}
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs text-slate-500">Biaya</p>
                                    <p class="mt-0.5 text-sm font-semibold text-slate-900">
                                        {{ $formatRupiah($biaya) }}
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <p class="text-xs text-slate-500">Status</p>
                                    <p class="mt-0.5 text-sm font-semibold {{ $status === 'Selesai' ? 'text-slate-700' : 'text-emerald-700' }}">
                                        {{ $status }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- RIGHT --}}
                <aside class="lg:col-span-4">
                    <div class="sticky top-6 space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-xl shadow-emerald-500/10 backdrop-blur">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">Pendaftaran</p>
                                    <p class="mt-1 text-xs text-emerald-100/80">
                                        Daftar lebih cepat biar tidak ketinggalan info.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                @auth
                                    <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-700 active:scale-[0.99]">
                                            ✅ Daftar Kegiatan
                                        </button>
                                    </form>
                                @else
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                                        Login untuk mendaftar kegiatan.
                                    </div>
                                @endauth

                                <div class="grid grid-cols-2 gap-2">
                                    @if($gcalLink)
                                        <a href="{{ $gcalLink }}" target="_blank" rel="noreferrer"
                                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-3 py-3 text-sm font-semibold text-white hover:bg-white/10">
                                            📅 Kalender
                                        </a>
                                    @else
                                        <button type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-3 py-3 text-sm font-semibold text-white/70 cursor-not-allowed">
                                            📅 Kalender
                                        </button>
                                    @endif

                                    <button type="button" data-copy-link
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/5 px-3 py-3 text-sm font-semibold text-white hover:bg-white/10">
                                        🔗 Share
                                    </button>
                                </div>

                                @if($waLink)
                                    <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                                        💬 Tanya Panitia (WA)
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-xl shadow-emerald-500/10 backdrop-blur">
                            <p class="text-sm font-semibold text-white">Tips hadir</p>
                            <ul class="mt-3 space-y-2 text-sm text-emerald-50/90">
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Datang 10 menit lebih awal.</li>
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Bawa catatan / mushaf bila perlu.</li>
                                <li class="flex gap-2"><span class="mt-0.5">•</span> Parkir sesuai arahan panitia.</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        {{-- MOBILE STICKY CTA --}}
        <div class="fixed inset-x-0 bottom-0 z-50 border-t border-white/10 bg-slate-950/75 p-3 backdrop-blur lg:hidden">
            <div class="mx-auto max-w-7xl px-1">
                <div class="grid grid-cols-2 gap-2">
                    @auth
                        <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700 active:scale-[0.99]">
                                ✅ Daftar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-emerald-700 active:scale-[0.99]">
                            🔐 Login dulu
                        </a>
                    @endauth

                    @if($waLink)
                        <a href="{{ $waLink }}" target="_blank" rel="noreferrer"
                            class="w-full rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                            💬 WhatsApp
                        </a>
                    @else
                        <button type="button" data-copy-link
                            class="w-full rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                            🔗 Share
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Scripts --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buttons = document.querySelectorAll('[data-copy-link]');
                buttons.forEach(btn => {
                    btn.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(window.location.href);
                            btn.textContent = '✅ Tersalin';
                            setTimeout(() => (btn.textContent = '🔗 Salin Link'), 1200);
                        } catch (e) {
                            alert('Gagal menyalin link');
                        }
                    });
                });
            });
        </script>
    </div>
</x-front-layout>
