<x-front-layout>
    <div class="min-h-screen scroll-smooth bg-gradient-to-br from-slate-950 via-emerald-950/60 to-slate-900 text-slate-100">
        {{-- Palet warna halaman --}}
        <style>
            :root {
                --primary: #059669;
                --gold: #d4af37;
                --border: rgba(255,255,255,0.12);
                --surface: #0f172a;
                --text-primary: #f8fafc;
                --text-secondary: #cbd5e1;
            }
        </style>

        {{-- HERO --}}
        <section class="relative overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=1800&q=80" alt="Masjid" class="h-full w-full object-cover opacity-30" referrerpolicy="no-referrer">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-emerald-950/45 to-slate-900/90"></div>
            </div>
            <div class="relative max-w-6xl mx-auto px-4 pt-10 pb-8 sm:pt-12 sm:pb-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-center">
                    <div class="space-y-3 md:flex-1">
                        <p class="text-xs uppercase tracking-[0.35em] text-emerald-200/85">Konsultasi Jamaah</p>
                        <h1 class="text-3xl md:text-4xl font-bold text-white">Tanya Ustadz</h1>
                        <p class="text-[--text-secondary] max-w-2xl">
                            Ajukan pertanyaan seputar aqidah, fiqih, akhlak, hingga muamalah. Ustadz kami akan menjawab dengan rujukan yang insyaAllah shahih.
                        </p>
                        <div class="flex flex-col gap-2 pt-3 sm:flex-row sm:flex-wrap sm:gap-3">
                            <a href="#ajukan-pertanyaan"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/30 transition-transform hover:-translate-y-0.5 sm:w-auto"
                               style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                Ajukan Pertanyaan
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            @auth
                                <a href="{{ route('tanya-ustadz.my') }}"
                                   class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold border border-[--border] bg-white/5 text-white hover:text-emerald-200 transition-colors backdrop-blur sm:w-auto">
                                    Lihat Pertanyaan Saya
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold border border-[--border] bg-white/5 text-white hover:text-emerald-200 transition-colors backdrop-blur sm:w-auto">
                                    Masuk untuk bertanya
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 w-full md:w-72">
                        <div class="rounded-2xl border border-[--border] bg-white/10 p-4 shadow-sm backdrop-blur">
                            <p class="text-xs text-[--text-secondary]">Pertanyaan dijawab</p>
                            <p class="text-2xl font-bold text-white mt-1">
                                {{ number_format($pertanyaans->total()) }}
                            </p>
                            <p class="text-[11px] text-[--text-secondary]">Ditampilkan untuk jamaah</p>
                        </div>
                        <div class="rounded-2xl border border-[--border] bg-white/10 p-4 shadow-sm backdrop-blur">
                            <p class="text-xs text-[--text-secondary]">Topik</p>
                            <p class="text-2xl font-bold text-white mt-1">{{ count($categories) }}</p>
                            <p class="text-[11px] text-[--text-secondary]">Aqidah • Fiqih • Akhlak</p>
                        </div>
                        <div class="col-span-2 rounded-2xl border border-[--border] bg-white/10 p-4 shadow-sm backdrop-blur">
                            <p class="text-xs text-[--text-secondary]">Respon ustadz</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="h-8 w-8 rounded-full bg-[--primary]/15 text-[--primary] grid place-content-center text-xs font-bold">U</div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Ustadz terverifikasi</p>
                                    <p class="text-[11px] text-[--text-secondary]">Jawaban dicek sebelum tampil</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- LIST & FORM --}}
        <main class="max-w-6xl mx-auto px-4 pb-16 -mt-6 space-y-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @forelse ($pertanyaans as $pertanyaan)
                        @php
                            $categoryLabel = $categories[$pertanyaan->kategori] ?? ucfirst($pertanyaan->kategori ?? 'Umum');
                            $ustadzName = $pertanyaan->ustadz->name ?? 'Belum ditetapkan';
                            $penanya = $pertanyaan->penanya->name ?? 'Jamaah';
                            $initial = strtoupper(mb_substr($penanya, 0, 1));
                            $tanggal = optional($pertanyaan->created_at)->translatedFormat('d M Y');
                        @endphp

                        <article class="rounded-3xl border border-[--border] bg-white/5 p-5 shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all hover:-translate-y-0.5 backdrop-blur">
                            <div class="flex items-center gap-3 text-xs text-[--text-secondary]">
                                <div class="h-9 w-9 rounded-full bg-[--primary]/15 text-[--primary] grid place-content-center font-semibold">
                                    {{ $initial }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-white">{{ $penanya }}</p>
                                    <p>{{ $tanggal }}</p>
                                </div>
                                <div class="ml-auto flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-full bg-[--primary]/15 text-[--primary] font-semibold text-[11px] uppercase tracking-wide">
                                        {{ $categoryLabel }}
                                    </span>
                                    <span class="px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-100 font-semibold text-[11px] uppercase tracking-wide">
                                        Dijawab
                                    </span>
                                </div>
                            </div>

                            <h3 class="mt-3 text-lg md:text-xl font-semibold text-white leading-snug">
                                {{ $pertanyaan->pertanyaan }}
                            </h3>
                            <p class="mt-1 text-sm text-[--text-secondary]">
                                Dijawab oleh <span class="font-semibold text-white">{{ $ustadzName }}</span>
                            </p>

                            @if ($pertanyaan->jawaban)
                                <div class="mt-4 rounded-2xl border border-[--border] bg-white/10 p-4 text-sm text-[--text-primary] leading-relaxed backdrop-blur">
                                    <p class="text-xs font-semibold text-emerald-200 uppercase tracking-wide">Jawaban</p>
                                    <div class="mt-2 space-y-2 leading-relaxed text-emerald-50">
                                        {!! nl2br(e($pertanyaan->jawaban)) !!}
                                    </div>
                                </div>
                            @else
                                <p class="mt-3 text-sm text-amber-200">Jawaban sedang diproses oleh ustadz.</p>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-3xl border border-[--border] bg-white/5 p-8 text-center space-y-2 shadow-md shadow-emerald-500/10 backdrop-blur">
                            <p class="text-lg font-semibold text-white">Belum ada pertanyaan dijawab.</p>
                            <p class="text-[--text-secondary] text-sm">Jadilah yang pertama mengajukan pertanyaan untuk ustadz.</p>
                            <a href="#ajukan-pertanyaan"
                               class="inline-flex justify-center rounded-full px-4 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/30 transition-transform hover:-translate-y-0.5"
                               style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                Ajukan Pertanyaan
                            </a>
                        </div>
                    @endforelse

                    @if (method_exists($pertanyaans, 'links'))
                        <div class="pt-2">
                            {{ $pertanyaans->links() }}
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div id="ajukan-pertanyaan" class="rounded-3xl border border-[--border] bg-white/5 p-6 shadow-md shadow-emerald-500/10 backdrop-blur">
                        <h2 class="text-lg font-bold text-white">Ajukan Pertanyaan</h2>
                        <p class="mt-1 text-xs text-[--text-secondary]">Pertanyaan akan ditinjau sebelum dijawab dan ditampilkan.</p>

                        @auth
                            <form action="{{ route('tanya-ustadz.store') }}" method="POST" class="mt-4 space-y-3">
                                @csrf
                                <div>
                                    <label class="text-xs font-semibold text-[--text-secondary]">Kategori</label>
                                    <select name="kategori" class="mt-1 w-full rounded-xl border border-[--border] bg-white/5 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[--primary]">
                                        @foreach ($categories as $value => $label)
                                            <option value="{{ $value }}" @selected(old('kategori', 'umum') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-[--text-secondary]">Pertanyaan</label>
                                    <textarea
                                        name="pertanyaan"
                                        rows="6"
                                        class="mt-1 w-full rounded-xl border border-[--border] bg-white/5 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[--primary]"
                                        placeholder="Tulis pertanyaan Anda..."
                                        required>{{ old('pertanyaan') }}</textarea>
                                </div>

                                <div class="text-[11px] text-[--text-secondary]">
                                    Pertanyaan yang sudah diajukan akan muncul di halaman ini setelah dijawab.
                                </div>

                                <button
                                    type="submit"
                                    class="w-full rounded-2xl px-4 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/30 transition-transform hover:-translate-y-0.5"
                                    style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                    Kirim Pertanyaan
                                </button>
                            </form>
                        @else
                            <div class="mt-4 space-y-3 text-sm text-[--text-secondary]">
                                <p>Masuk terlebih dahulu untuk mengajukan pertanyaan.</p>
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl border border-[--border] bg-white/5 px-4 py-2 font-semibold text-white hover:text-emerald-200">Masuk</a>
                                    <a href="{{ route('register') }}" class="inline-flex justify-center rounded-xl px-4 py-2 font-semibold text-slate-900"
                                       style="background: linear-gradient(135deg, var(--gold) 0%, var(--primary) 100%);">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div class="rounded-3xl border border-[--border] bg-white/5 p-6 shadow-md shadow-emerald-500/10 space-y-3 backdrop-blur">
                        <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Panduan Singkat</h3>
                        <ul class="space-y-2 text-sm text-[--text-secondary] list-disc list-inside">
                            <li>Tulis pertanyaan dengan jelas agar ustadz mudah menjawab.</li>
                            <li>Pilih kategori paling relevan untuk topik Anda.</li>
                            <li>Jawaban akan dikirim dan ditampilkan setelah diverifikasi.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-front-layout>
