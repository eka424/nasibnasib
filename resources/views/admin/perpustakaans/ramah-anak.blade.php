<x-front-layout>
    @php
        $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2200&q=80';
        $glass = 'border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur';
        $primary = '#10b981';
        $gold = '#d4af37';

        // helper embed youtube dari url / id
        $youtubeId = function (?string $urlOrId) {
            $s = trim((string) $urlOrId);
            if ($s === '') return null;

            // sudah id
            if (preg_match('/^[a-zA-Z0-9_-]{6,15}$/', $s)) return $s;

            // youtu.be/ID
            if (preg_match('~youtu\.be/([a-zA-Z0-9_-]{6,15})~', $s, $m)) return $m[1];

            // youtube.com/watch?v=ID
            if (preg_match('~v=([a-zA-Z0-9_-]{6,15})~', $s, $m)) return $m[1];

            // youtube.com/embed/ID
            if (preg_match('~/embed/([a-zA-Z0-9_-]{6,15})~', $s, $m)) return $m[1];

            return null;
        };
    @endphp

    <style>
        :root { --primary: {{ $primary }}; --gold: {{ $gold }}; }
        .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
        .scrollbar-none::-webkit-scrollbar{display:none;}
        .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
        .floaty { animation: floaty 4s ease-in-out infinite; }
        @keyframes floaty { 0%,100%{ transform: translateY(0);} 50%{ transform: translateY(-8px);} }
        .wiggle { animation: wiggle 1.6s ease-in-out infinite; transform-origin: center; }
        @keyframes wiggle { 0%,100%{ transform: rotate(-1deg);} 50%{ transform: rotate(1deg);} }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950/55 to-slate-900 text-slate-100">

        {{-- HERO --}}
        <header class="relative overflow-hidden">
            <div class="absolute inset-0" aria-hidden="true">
                <img src="{{ $heroBg }}" class="h-full w-full object-cover opacity-30" referrerpolicy="no-referrer" alt="Ramah Anak">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-emerald-950/45 to-slate-900/90"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <nav class="text-xs text-emerald-100/80">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-100">Beranda</a></li>
                        <li class="text-white/60">/</li>
                        <li><a href="{{ route('perpustakaan.index') }}" class="hover:text-emerald-100">Perpustakaan</a></li>
                        <li class="text-white/60">/</li>
                        <li class="font-semibold text-white">Ramah Anak</li>
                    </ol>
                </nav>

                <div class="mt-5 grid gap-6 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90 {{ $glass }}">
                            <span class="wiggle">🧒</span>
                            <span>Perpustakaan Ramah Anak</span>
                            <span class="floaty">✨</span>
                        </div>

                        <h1 class="mt-4 text-3xl font-extrabold text-white sm:text-4xl">
                            Jelajahi konten anak: video, dongeng, & kata-kata islami
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-emerald-100/85">
                            Konten dipilih untuk menemani anak belajar dengan cara yang menyenangkan.
                        </p>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'video']) }}"
                               class="rounded-full px-4 py-2 text-sm font-semibold border transition
                               {{ $tab === 'video' ? 'border-emerald-400 bg-emerald-500/25 text-white' : 'border-white/15 bg-white/10 text-white/85 hover:bg-white/15' }}">
                                🎬 Video Animasi
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'dongeng']) }}"
                               class="rounded-full px-4 py-2 text-sm font-semibold border transition
                               {{ $tab === 'dongeng' ? 'border-emerald-400 bg-emerald-500/25 text-white' : 'border-white/15 bg-white/10 text-white/85 hover:bg-white/15' }}">
                                📖 Dongeng Islami
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'kata']) }}"
                               class="rounded-full px-4 py-2 text-sm font-semibold border transition
                               {{ $tab === 'kata' ? 'border-emerald-400 bg-emerald-500/25 text-white' : 'border-white/15 bg-white/10 text-white/85 hover:bg-white/15' }}">
                                💬 Kata-kata Islami
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="rounded-3xl p-5 {{ $glass }}">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                    <p class="text-xs text-white/60">Video</p>
                                    <p class="mt-1 text-xl font-extrabold text-white">{{ number_format($videos->total()) }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                    <p class="text-xs text-white/60">Dongeng</p>
                                    <p class="mt-1 text-xl font-extrabold text-white">{{ number_format($dongeng->total()) }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                    <p class="text-xs text-white/60">Kata</p>
                                    <p class="mt-1 text-xl font-extrabold text-white">{{ number_format($kata->total()) }}</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white/80">
                                <span class="font-semibold text-white">Tips orang tua:</span>
                                dampingi anak saat menonton, lalu ajak ngobrol tentang pelajaran yang didapat 😊
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            @if($tab === 'video')
                <section class="mt-8">
                    <div class="mb-4 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200/80">Video</p>
                            <h2 class="text-xl font-extrabold text-white">Video animasi pilihan</h2>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($videos as $item)
                            @php
                                $id = $youtubeId($item->youtube_url ?? $item->source ?? $item->link ?? $item->value ?? null);
                                $thumb = $id ? "https://i.ytimg.com/vi/{$id}/hqdefault.jpg" : null;
                            @endphp

                            <article class="overflow-hidden rounded-3xl {{ $glass }} transition hover:-translate-y-0.5">
                                <div class="relative aspect-video bg-black/30">
                                    @if($id)
                                        <button
                                            type="button"
                                            class="absolute inset-0 group"
                                            data-open-video="{{ $id }}"
                                            aria-label="Putar video"
                                        >
                                            <img src="{{ $thumb }}" class="h-full w-full object-cover opacity-90" referrerpolicy="no-referrer" alt="Thumbnail">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                                            <div class="absolute left-4 bottom-4 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-slate-900">
                                                ▶ Putar
                                            </div>
                                        </button>
                                    @else
                                        <div class="grid h-full w-full place-items-center text-sm text-white/70">Link YouTube tidak valid</div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="text-base font-extrabold text-white line-clamp-2">
                                        {{ $item->title ?? $item->judul ?? 'Video Anak' }}
                                    </h3>
                                    @if(!empty($item->description ?? $item->deskripsi))
                                        <p class="mt-1 text-sm text-white/75 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->description ?? $item->deskripsi), 110) }}
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl p-8 text-center {{ $glass }}">
                                <p class="text-white font-semibold">Belum ada video.</p>
                                <p class="text-sm text-white/70 mt-1">Tambahkan dari dashboard admin (Kids Content).</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $videos->appends(request()->query())->links() }}
                    </div>
                </section>
            @endif

            @if($tab === 'dongeng')
                <section class="mt-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200/80">Dongeng</p>
                    <h2 class="text-xl font-extrabold text-white">Dongeng islami (PDF)</h2>

                    <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($dongeng as $item)
                            @php
                                $pdf = $item->pdf_url ?? $item->file ?? $item->source ?? null;
                                if ($pdf && !str_starts_with($pdf, 'http')) $pdf = \Illuminate\Support\Facades\Storage::url($pdf);
                            @endphp

                            <article class="rounded-3xl p-5 {{ $glass }} hover:-translate-y-0.5 transition">
                                <div class="flex items-start gap-3">
                                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white/10 text-2xl floaty">📄</div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-extrabold text-white line-clamp-2">
                                            {{ $item->title ?? $item->judul ?? 'Dongeng Islami' }}
                                        </h3>
                                        <p class="mt-1 text-sm text-white/70 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->description ?? $item->deskripsi ?? ''), 120) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex gap-2">
                                    <a href="{{ $pdf ?: '#' }}" target="_blank"
                                       class="{{ $pdf ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-white/20 text-white/50 cursor-not-allowed' }}
                                              inline-flex flex-1 items-center justify-center rounded-2xl px-3 py-2 text-sm font-semibold text-white transition">
                                        Buka PDF
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl p-8 text-center {{ $glass }}">
                                <p class="text-white font-semibold">Belum ada dongeng.</p>
                                <p class="text-sm text-white/70 mt-1">Tambahkan PDF dari dashboard admin.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $dongeng->appends(request()->query())->links() }}
                    </div>
                </section>
            @endif

            @if($tab === 'kata')
                <section class="mt-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200/80">Kata-kata</p>
                    <h2 class="text-xl font-extrabold text-white">Kata-kata islami untuk anak</h2>

                    <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($kata as $item)
                            <article class="rounded-3xl p-5 {{ $glass }} hover:-translate-y-0.5 transition">
                                <div class="flex items-start gap-3">
                                    <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white/10 text-2xl wiggle">💛</div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-extrabold text-white line-clamp-2">
                                            {{ $item->title ?? $item->judul ?? 'Pesan Baik' }}
                                        </h3>
                                        <p class="mt-2 text-sm text-white/85 leading-relaxed">
                                            {{ $item->content ?? $item->isi ?? $item->quote ?? $item->text ?? '-' }}
                                        </p>
                                        @if(!empty($item->source_label ?? $item->sumber))
                                            <p class="mt-2 text-xs text-white/60">Sumber: {{ $item->source_label ?? $item->sumber }}</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl p-8 text-center {{ $glass }}">
                                <p class="text-white font-semibold">Belum ada kata-kata.</p>
                                <p class="text-sm text-white/70 mt-1">Tambahkan dari dashboard admin.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $kata->appends(request()->query())->links() }}
                    </div>
                </section>
            @endif
        </main>

        {{-- MODAL YOUTUBE --}}
        <div id="ytModal" class="fixed inset-0 z-[80] hidden">
            <div class="absolute inset-0 bg-black/70" data-close></div>
            <div class="relative mx-auto mt-16 w-[92vw] max-w-4xl overflow-hidden rounded-3xl border border-white/15 bg-slate-950 shadow-2xl">
                <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                    <p class="text-sm font-semibold text-white">Putar Video</p>
                    <button class="rounded-xl border border-white/10 bg-white/5 px-3 py-1.5 text-sm font-semibold text-white hover:bg-white/10" data-close>
                        Tutup
                    </button>
                </div>
                <div class="aspect-video">
                    <iframe id="ytFrame" class="h-full w-full" src="" title="YouTube player"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('ytModal');
                const frame = document.getElementById('ytFrame');

                const open = (id) => {
                    frame.src = `https://www.youtube.com/embed/${id}?autoplay=1&rel=0`;
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                };

                const close = () => {
                    frame.src = '';
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                };

                document.querySelectorAll('[data-open-video]').forEach(btn => {
                    btn.addEventListener('click', () => open(btn.getAttribute('data-open-video')));
                });

                modal.querySelectorAll('[data-close]').forEach(el => el.addEventListener('click', close));
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
            });
        </script>
    </div>
</x-front-layout>
