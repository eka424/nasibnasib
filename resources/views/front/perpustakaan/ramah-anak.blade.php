<x-front-layout>
    @php
        $heroBg = 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=2000&q=80';
        $glass = 'border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10 backdrop-blur';
        $tab = request('tab', 'video'); // video|dongeng|kata
        use Illuminate\Support\Facades\Storage;
    @endphp
\


    <style>
        :root { --primary:#10b981; --gold:#f6c453; }
        .floaty { animation: floaty 4s ease-in-out infinite; }
        @keyframes floaty { 0%,100% { transform: translateY(0) } 50% { transform: translateY(-6px) } }
        .wiggle { animation: wiggle 1.2s ease-in-out infinite; }
        @keyframes wiggle { 0%,100% { transform: rotate(-2deg) } 50% { transform: rotate(2deg) } }
        .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">
        {{-- HERO --}}
        <header class="relative overflow-hidden">
            <div class="absolute inset-0" aria-hidden="true">
                <img src="{{ $heroBg }}" class="h-full w-full object-cover opacity-30" alt="Ramah Anak" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-emerald-950/45 to-slate-900/90"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center text-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-300 to-emerald-500 text-slate-950 grid place-items-center shadow-lg shadow-emerald-500/30 floaty">
                            {{-- icon: smile --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 9.5h.01M9.5 9.5h.01M8 14s1.5 2 4 2 4-2 4-2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs uppercase tracking-[0.35em] text-emerald-200/85">Perpustakaan</p>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Ramah Anak</h1>
                        </div>
                    </div>

                    <p class="max-w-2xl text-emerald-100/90 text-sm sm:text-base">
                        Video animasi Islami, dongeng PDF, dan kata-kata Islami untuk anak—ringan, seru, dan bermanfaat.
                    </p>

                    {{-- Quick Stats --}}
                    <div class="w-full max-w-4xl rounded-3xl p-4 sm:p-5 {{ $glass }}">
                        <div class="grid grid-cols-3 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                                <p class="text-[11px] text-white/60">Video</p>
                                <p class="mt-1 text-lg font-extrabold text-white">{{ number_format($stats['video'] ?? 0) }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                                <p class="text-[11px] text-white/60">Dongeng</p>
                                <p class="mt-1 text-lg font-extrabold text-white">{{ number_format($stats['dongeng'] ?? 0) }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-left">
                                <p class="text-[11px] text-white/60">Kata Islami</p>
                                <p class="mt-1 text-lg font-extrabold text-white">{{ number_format($stats['kata'] ?? 0) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <form method="GET" class="w-full max-w-3xl mt-2">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-emerald-200/80">
                                🔎
                            </span>
                            <input name="q" value="{{ $q ?? '' }}" placeholder="Cari video / dongeng / kata islami..."
                                class="h-11 w-full rounded-2xl border border-white/15 bg-white/5 pl-10 pr-3 text-sm text-white placeholder:text-emerald-100/50 focus:outline-none focus:ring-2 focus:ring-emerald-400/50">
                        </div>
                    </form>

                    {{-- Tabs --}}
                    <div class="mt-3 inline-grid grid-cols-3 overflow-hidden rounded-full border border-white/15 bg-white/10 shadow-sm shadow-emerald-500/10">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'video']) }}"
                           class="px-4 py-2 text-sm font-semibold transition {{ $tab==='video' ? 'bg-emerald-500/70 text-white' : 'text-white/85 hover:bg-white/10' }}">
                           🎬 Video
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'dongeng']) }}"
                           class="px-4 py-2 text-sm font-semibold transition {{ $tab==='dongeng' ? 'bg-emerald-500/70 text-white' : 'text-white/85 hover:bg-white/10' }}">
                           📖 Dongeng
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'kata']) }}"
                           class="px-4 py-2 text-sm font-semibold transition {{ $tab==='kata' ? 'bg-emerald-500/70 text-white' : 'text-white/85 hover:bg-white/10' }}">
                           💬 Kata
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 -mt-6">
            <div class="rounded-3xl p-4 sm:p-6 {{ $glass }}">

                {{-- VIDEO TAB --}}
                @if($tab === 'video')
                    @if(($videos ?? collect())->isEmpty())
                        <div class="py-10 text-center">
                            <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-white/10 grid place-items-center text-2xl wiggle">🎬</div>
                            <p class="text-white font-semibold">Belum ada video.</p>
                            <p class="text-sm text-emerald-100/70 mt-1">Admin bisa menambahkan link YouTube dari dashboard.</p>
                        </div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($videos as $v)
                                @php
                                    $yt = $v->youtube_url ?? $v->url ?? null;
                                    // ambil id youtube dari url
                                    $ytId = null;
                                    if ($yt) {
                                        parse_str(parse_url($yt, PHP_URL_QUERY) ?? '', $qs);
                                        $ytId = $qs['v'] ?? null;
                                        if (!$ytId && str_contains($yt, 'youtu.be/')) {
                                            $ytId = trim(parse_url($yt, PHP_URL_PATH) ?? '', '/');
                                        }
                                    }
                                @endphp

                                <article class="overflow-hidden rounded-2xl border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/10">
                                    <div class="aspect-video bg-black/20">
                                        @if($ytId)
                                            <iframe class="w-full h-full"
                                                src="https://www.youtube.com/embed/{{ $ytId }}"
                                                title="{{ $v->title ?? 'Video' }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen></iframe>
                                        @else
                                            <div class="w-full h-full grid place-items-center text-sm text-white/70 p-4">
                                                Link YouTube tidak valid.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-white font-semibold line-clamp-2">{{ $v->title ?? 'Video Anak' }}</h3>
                                        @if(!empty($v->description))
                                            <p class="mt-1 text-sm text-emerald-100/70 line-clamp-2">{{ $v->description }}</p>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                @endif

                {{-- DONGENG TAB --}}
@if($tab === 'dongeng')
    @if(($dongeng ?? collect())->isEmpty())
        <div class="py-10 text-center">
            <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-white/10 grid place-items-center text-2xl wiggle">📖</div>
            <p class="text-white font-semibold">Belum ada dongeng.</p>
            <p class="text-sm text-emerald-100/70 mt-1">Admin bisa upload PDF dongeng dari dashboard.</p>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($dongeng as $d)
                @php
                    // ✅ ini yang benar: pdf_path
                    $pdfUrl = $d->pdf_path ? Storage::url($d->pdf_path) : null;
                @endphp

                <article class="rounded-2xl border border-white/15 bg-white/10 p-4 shadow-lg shadow-emerald-500/10">
                    <div class="flex items-start gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-white/10 grid place-items-center text-2xl floaty">🦄</div>
                        <div class="min-w-0">
                            <h3 class="text-white font-semibold line-clamp-2">{{ $d->title ?? 'Dongeng Islami' }}</h3>
                            <p class="text-xs text-emerald-100/70 mt-1 line-clamp-2">
                                {{ $d->description ?? 'Dongeng untuk anak-anak.' }}
                            </p>
                        </div>
                    </div>

                    @if($pdfUrl)
                        {{-- ✅ PREVIEW PDF --}}
                        <div class="mt-3 overflow-hidden rounded-2xl border border-white/15 bg-white">
                            <iframe
                                src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=1"
                                class="h-64 w-full"
                                loading="lazy"
                            ></iframe>
                        </div>

                        {{-- ✅ BUTTONS --}}
                        <div class="mt-3 flex gap-2">
                            <a href="{{ $pdfUrl }}" target="_blank" rel="noreferrer"
                               class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700">
                               👀 Buka PDF
                            </a>

                            <a href="{{ $pdfUrl }}" download
                               class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-3 py-2 text-xs font-bold text-white/85 hover:bg-white/10">
                               📥 Unduh
                            </a>
                        </div>
                    @else
                        <p class="mt-3 text-sm text-white/70">File PDF belum ada.</p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
@endif


                {{-- KATA TAB --}}
                @if($tab === 'kata')
                    @if(($kata ?? collect())->isEmpty())
                        <div class="py-10 text-center">
                            <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-white/10 grid place-items-center text-2xl wiggle">💬</div>
                            <p class="text-white font-semibold">Belum ada kata-kata.</p>
                            <p class="text-sm text-emerald-100/70 mt-1">Admin bisa menambahkan quote dari dashboard.</p>
                        </div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($kata as $k)
                                <article class="rounded-2xl border border-white/15 bg-white/10 p-4 shadow-lg shadow-emerald-500/10">
                                    <div class="flex items-center gap-2 text-xs text-emerald-100/70">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/10">✨</span>
                                        <span>Kata Islami</span>
                                    </div>
                                    <p class="mt-3 text-white font-semibold leading-relaxed">
                                        “{{ $k->quote_text ?? $k->description ?? $k->title ?? 'Semoga Allah memberkahimu.' }}”
                                    </p>
                                    @if(!empty($k->title))
                                        <p class="mt-2 text-xs text-emerald-100/70">— {{ $k->title }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @endif
                @endif

            </div>
        </main>
    </div>
</x-front-layout>
