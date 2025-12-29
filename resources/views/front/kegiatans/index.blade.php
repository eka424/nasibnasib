<x-front-layout>
    @php
        // ====== Data helper (opsional) ======
        // Asumsi: $kegiatans = paginate() dari controller
        // Pastikan kamu eager load pendaftarans_count:
        // Kegiatan::withCount('pendaftarans')...

        $chips = ['Semua', 'Kajian', 'Pelatihan', 'Perlombaan', 'Sosial', 'Lainnya'];

        $q = request('q');
        $chipActive = request('chip', 'Semua');

        $from = request('from');
        $to = request('to');

        $jenisList = ['Kajian', 'Pelatihan', 'Perlombaan', 'Sosial', 'Lainnya'];
        $statusList = [
            'dibuka' => 'Pendaftaran Dibuka',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
        ];

        // default checkbox ON kalau belum ada query
        $jenisChecked = request('jenis', $jenisList);
        $statusChecked = request('status', array_keys($statusList));

        $glass = 'rounded-3xl border border-white/15 bg-white/10 shadow-lg shadow-emerald-500/15 backdrop-blur';
    @endphp

    <style>
        :root { --primary: #059669; --gold: #D4AF37; }
        .scrollbar-none{-ms-overflow-style:none; scrollbar-width:none;}
        .scrollbar-none::-webkit-scrollbar{display:none;}
    </style>

    <div class="min-h-screen scroll-smooth bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">

        {{-- HERO / HEADER --}}
        <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 opacity-0 translate-y-6 transition duration-700 ease-out" data-animate>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-emerald-200/80">Agenda Masjid</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Kegiatan Masjid Al-A'la
                    </h1>
                    <p class="text-sm text-emerald-100/85">
                        Temukan kegiatan terbaik untuk belajar, berbagi, dan berkontribusi bersama jamaah lainnya.
                    </p>
                </div>

                {{-- Search + Action --}}
                <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <div class="relative flex-1 min-w-[240px]">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-emerald-100/70">🔍</span>
                        <input
                            name="q"
                            value="{{ $q }}"
                            placeholder="Cari kegiatan, lokasi, atau deskripsi..."
                            class="w-full rounded-2xl border border-white/20 bg-white/10 pl-9 pr-3 py-2.5 text-sm text-white placeholder:text-emerald-100/70 focus:outline-none focus:ring-2 focus:ring-emerald-400/70" />
                    </div>

                    {{-- simpan filter lain saat search --}}
                    <input type="hidden" name="chip" value="{{ $chipActive }}">
                    <input type="hidden" name="from" value="{{ $from }}">
                    <input type="hidden" name="to" value="{{ $to }}">
                    @foreach ((array) $jenisChecked as $j)
                        <input type="hidden" name="jenis[]" value="{{ $j }}">
                    @endforeach
                    @foreach ((array) $statusChecked as $s)
                        <input type="hidden" name="status[]" value="{{ $s }}">
                    @endforeach

                    <a href="{{ route('kegiatan.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-emerald-400/70 bg-emerald-500/10 px-4 py-2.5 text-sm font-semibold text-emerald-100 shadow-sm shadow-emerald-500/20 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:bg-emerald-400/20">
                        📅 Lihat Kalender
                    </a>

                    {{-- Mobile filter button --}}
                    <button type="button" data-open-sheet
                        class="lg:hidden inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white/90 transition hover:bg-white/15">
                        ⚙️ Filter
                    </button>
                </form>
            </div>

            {{-- Glass info --}}
            <div class="mt-4 p-5 {{ $glass }}">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-100">
                        🔔 Halaman Kegiatan
                    </span>
                    <p class="text-sm text-emerald-50">
                        Pantau dan ikuti berbagai agenda masjid. Filter tersedia untuk memudahkan pencarian.
                    </p>
                </div>
            </div>

            {{-- Chips (GET) --}}
            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold">
                @foreach ($chips as $c)
                    @php $active = $c === $chipActive; @endphp
                    <a href="{{ request()->fullUrlWithQuery(['chip' => $c]) }}"
                       class="px-3 py-1.5 rounded-full border transition
                       {{ $active ? 'border-emerald-300 text-emerald-100 bg-emerald-500/20' : 'border-white/15 text-emerald-50 hover:text-white hover:border-emerald-200/60' }}">
                        {{ $c }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- MAIN --}}
        <main class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 pb-16 sm:px-6 lg:grid-cols-[18rem_1fr] lg:px-8">

            {{-- DESKTOP ASIDE FILTER --}}
            <aside class="hidden lg:block lg:sticky lg:top-28 lg:h-fit rounded-2xl border border-emerald-200/60 bg-white/95 p-5 shadow-lg shadow-emerald-500/15 text-slate-800">
                <form method="GET" class="space-y-6 text-sm">
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="chip" value="{{ $chipActive }}">

                    <div>
                        <p class="mb-2 font-semibold text-slate-800">Tanggal</p>
                        <div class="grid gap-2">
                            <input type="date" name="from" value="{{ $from }}" class="rounded-xl border border-slate-200 w-full px-3 py-2 text-xs" />
                            <input type="date" name="to" value="{{ $to }}" class="rounded-xl border border-slate-200 w-full px-3 py-2 text-xs" />
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 font-semibold text-slate-800">Jenis Kegiatan</p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($jenisList as $j)
                                <label class="inline-flex items-center gap-2 text-xs">
                                    <input type="checkbox" name="jenis[]"
                                        value="{{ $j }}"
                                        class="h-3.5 w-3.5 rounded border-slate-300"
                                        {{ in_array($j, (array)$jenisChecked) ? 'checked' : '' }} />
                                    {{ $j }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 font-semibold text-slate-800">Status</p>
                        <div class="grid gap-2 text-xs">
                            @foreach ($statusList as $key => $label)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="status[]"
                                        value="{{ $key }}"
                                        class="h-3.5 w-3.5 rounded border-slate-300"
                                        {{ in_array($key, (array)$statusChecked) ? 'checked' : '' }} />
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-2 text-xs font-semibold">
                        <a href="{{ route('kegiatan.index') }}"
                           class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-center text-slate-700">
                            Reset
                        </a>
                        <button class="flex-1 rounded-xl px-3 py-2 text-white"
                            style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                            Terapkan
                        </button>
                    </div>
                </form>
            </aside>

            {{-- CONTENT --}}
            <section>
                {{-- Banner --}}
                <div class="mb-3 overflow-hidden rounded-2xl border border-emerald-200/50 shadow-lg shadow-emerald-500/15">
                    <div class="relative isolate">
                        <img src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1600&q=80"
                             alt="Banner Kegiatan Masjid"
                             class="h-32 w-full object-cover opacity-80" referrerpolicy="no-referrer">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-emerald-900/70 to-emerald-700/40"></div>
                        <div class="relative px-5 py-4 text-sm text-emerald-50">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-200">Kegiatan</p>
                            <p class="text-lg font-bold text-white leading-tight">Semua agenda masjid dalam satu tempat</p>
                            <p class="mt-1 text-emerald-100/85">Swipe di mobile, grid di desktop. Klik detail untuk info lengkap.</p>
                        </div>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2 scrollbar-none md:grid md:grid-cols-2 xl:grid-cols-3 md:overflow-visible"
                     style="WebkitOverflowScrolling:touch;">
                    @forelse ($kegiatans as $kegiatan)
                        @php
                            $start = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);
                            $end = \Carbon\Carbon::parse($kegiatan->tanggal_selesai ?? $kegiatan->tanggal_mulai);

                            $status = $start->isFuture() ? 'dibuka' : ($end->isPast() ? 'selesai' : 'berlangsung');

                            $statusLabel = [
                                'dibuka' => 'Pendaftaran Dibuka',
                                'berlangsung' => 'Sedang Berlangsung',
                                'selesai' => 'Selesai',
                            ][$status];

                            $statusClass = match ($status) {
                                'dibuka' => 'bg-emerald-50/80 text-emerald-700 border-emerald-200/70',
                                'berlangsung' => 'bg-amber-50 text-amber-800 border-amber-200',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };

                            $count = $kegiatan->pendaftarans_count ?? 0;
                            $quota = max($count + 20, 20);
                            $remaining = max(0, $quota - $count);
                            $progress = min(100, round(($count / max(1, $quota)) * 100));
                        @endphp

                        <article class="group w-[85vw] max-w-sm flex-none snap-start overflow-hidden rounded-2xl border border-white/15 bg-white/95 shadow-lg shadow-emerald-500/10 transition hover:-translate-y-0.5 hover:shadow-xl md:w-auto md:max-w-none md:flex-auto">
                            <div class="relative aspect-video overflow-hidden">
                                <img loading="lazy"
                                     src="{{ $kegiatan->poster ?? 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1400&q=80' }}"
                                     alt="{{ $kegiatan->nama_kegiatan }}"
                                     class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                     referrerpolicy="no-referrer">
                                <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full border border-emerald-200/70 bg-white/90 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-700">
                                    Program Masjid
                                </span>
                            </div>

                            <div class="p-4 space-y-3 text-slate-900">
                                <h3 class="text-base font-semibold line-clamp-2">
                                    {{ $kegiatan->nama_kegiatan }}
                                </h3>

                                <div class="space-y-1 text-xs text-slate-500">
                                    <p class="flex items-center gap-2">
                                        <span>📅</span>
                                        <span>{{ $start->translatedFormat('D, d M Y') }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span>⏰ {{ $start->translatedFormat('H:i') }} WITA</span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <span>📍</span>
                                        <span>{{ $kegiatan->lokasi }}</span>
                                    </p>
                                </div>

                                <p class="text-sm text-slate-600 line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($kegiatan->deskripsi), 140) }}
                                </p>

                                <div>
                                    <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full"
                                             style="width: {{ $progress }}%; background: linear-gradient(90deg, var(--gold), var(--primary));"></div>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>{{ $remaining }} kursi tersisa</span>
                                        <span>{{ $count }}/{{ $quota }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-semibold rounded-full px-2 py-1 border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('kegiatan.show', $kegiatan) }}"
                                           class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold text-white shadow-sm shadow-emerald-500/30"
                                           style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                            Detail
                                        </a>

                                        @auth
                                            @if ($status === 'dibuka')
                                                <form action="{{ route('kegiatan.daftar', $kegiatan) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                                                        Daftar
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="w-full {{ $glass }} p-6">
                            <p class="text-sm text-emerald-50">Tidak ada kegiatan ditemukan.</p>
                        </div>
                    @endforelse
                </div>

                <p class="mt-2 text-xs text-emerald-100/80 md:hidden">
                    Geser kiri/kanan untuk melihat kegiatan lainnya.
                </p>

                <div class="mt-8">
                    {{ $kegiatans->appends(request()->query())->links() }}
                </div>
            </section>
        </main>

        {{-- MOBILE BOTTOM SHEET FILTER --}}
        <div id="filterSheet" class="fixed inset-0 z-[80] lg:hidden pointer-events-none">
            <div id="filterBackdrop"
                 class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-300"></div>

            <div id="filterPanel"
                 class="absolute inset-x-0 bottom-0 translate-y-full transition-transform duration-300">
                <div class="mx-auto max-w-xl rounded-t-3xl border border-white/10 bg-slate-950/90 p-5 shadow-2xl backdrop-blur">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-200/80">Filter</p>
                            <p class="mt-1 text-lg font-extrabold text-white">Atur Pencarian</p>
                        </div>
                        <button type="button" data-close-sheet
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white transition hover:bg-white/10">
                            ✕
                        </button>
                    </div>

                    <form method="GET" class="mt-4 space-y-4">
                        <input type="hidden" name="q" value="{{ $q }}">
                        <input type="hidden" name="chip" value="{{ $chipActive }}">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-white/70">Dari</label>
                                <input type="date" name="from" value="{{ $from }}"
                                    class="mt-1 h-11 w-full rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-white/70">Sampai</label>
                                <input type="date" name="to" value="{{ $to }}"
                                    class="mt-1 h-11 w-full rounded-xl border border-white/10 bg-white/5 px-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-400/60" />
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-white/70">Jenis Kegiatan</p>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                @foreach ($jenisList as $j)
                                    <label class="inline-flex items-center gap-2 text-white/85">
                                        <input type="checkbox" name="jenis[]" value="{{ $j }}"
                                            class="h-4 w-4 rounded border-white/20 bg-white/10"
                                            {{ in_array($j, (array)$jenisChecked) ? 'checked' : '' }}>
                                        {{ $j }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-white/70">Status</p>
                            <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                @foreach ($statusList as $key => $label)
                                    <label class="inline-flex items-center gap-2 text-white/85">
                                        <input type="checkbox" name="status[]" value="{{ $key }}"
                                            class="h-4 w-4 rounded border-white/20 bg-white/10"
                                            {{ in_array($key, (array)$statusChecked) ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex gap-2 pt-1">
                            <a href="{{ route('kegiatan.index') }}"
                               class="inline-flex flex-1 items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Reset
                            </a>
                            <button type="submit"
                                class="inline-flex flex-1 items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold text-white"
                                style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                Terapkan
                            </button>
                        </div>

                        <p class="text-xs text-white/50">
                            Bottom-sheet ini nyaman dipakai satu tangan di mobile.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        {{-- JS: animate + bottom sheet --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // ===== animate on scroll (data-animate) =====
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.remove('opacity-0', 'translate-y-6');
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.18 });

                document.querySelectorAll('[data-animate]').forEach((el, idx) => {
                    el.style.transitionDelay = `${Math.min(idx * 80, 600)}ms`;
                    observer.observe(el);
                });

                // ===== mobile sheet =====
                const sheet = document.getElementById('filterSheet');
                const panel = document.getElementById('filterPanel');
                const backdrop = document.getElementById('filterBackdrop');

                const openBtns = document.querySelectorAll('[data-open-sheet]');
                const closeBtns = document.querySelectorAll('[data-close-sheet]');

                const openSheet = () => {
                    sheet.classList.remove('pointer-events-none');
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                    panel.classList.remove('translate-y-full');
                    panel.classList.add('translate-y-0');
                    document.body.style.overflow = 'hidden';
                };

                const closeSheet = () => {
                    backdrop.classList.add('opacity-0');
                    backdrop.classList.remove('opacity-100');
                    panel.classList.add('translate-y-full');
                    panel.classList.remove('translate-y-0');
                    document.body.style.overflow = '';
                    setTimeout(() => sheet.classList.add('pointer-events-none'), 250);
                };

                openBtns.forEach(btn => btn.addEventListener('click', openSheet));
                closeBtns.forEach(btn => btn.addEventListener('click', closeSheet));
                backdrop.addEventListener('click', closeSheet);

                // esc to close
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') closeSheet();
                });
            });
        </script>
    </div>
</x-front-layout>
