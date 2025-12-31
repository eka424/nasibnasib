<x-front-layout>
  @php
    $tab = request('tab', 'video'); // video|dongeng|kata
    $q = $q ?? request('q');

    // warna tema layout kamu
    $base = '#13392f';
    $cream = '#F5F1E8';
    $gold = '#E7B14B';

    // style helper
    $card = 'border border-black/10 bg-[#F5F1E8]/95 shadow-[0_18px_55px_-35px_rgba(0,0,0,0.45)]';
    $chip = 'border border-black/10 bg-white/70';
    $soft = 'border border-black/10 bg-white/70';
  @endphp

  <style>
    .line-clamp-2{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;}
    .line-clamp-3{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:3;overflow:hidden;}

    /* subtle premium motion */
    .floaty{animation:floaty 4.2s ease-in-out infinite}
    @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
    .sparkle{animation:sparkle 2.8s ease-in-out infinite}
    @keyframes sparkle{
      0%,100%{transform:scale(1); filter:drop-shadow(0 0 0 rgba(231,177,75,0))}
      50%{transform:scale(1.03); filter:drop-shadow(0 10px 20px rgba(231,177,75,.25))}
    }

    /* tabs */
    .tab{transition:.16s ease}
    .tab:hover{transform:translateY(-1px)}
    .tab-active{
      background: linear-gradient(135deg, rgba(231,177,75,.35), rgba(19,57,47,.08));
      border-color: rgba(231,177,75,.65);
      color: #13392f;
      box-shadow: 0 10px 30px -18px rgba(231,177,75,.6);
    }

    /* card hover */
    .kid-card{transition:transform .16s ease, box-shadow .16s ease, border-color .16s ease;}
    .kid-card:hover{
      transform: translateY(-3px);
      border-color: rgba(231,177,75,.60);
      box-shadow: 0 18px 55px -35px rgba(231,177,75,.55);
    }

    /* decorative blobs */
    .blob{filter: blur(1px); opacity:.45; animation: blob 10s ease-in-out infinite;}
    @keyframes blob{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(10px,-10px) scale(1.04)}}
  </style>

  {{-- wrapper: ikut layout solid bg --}}
  <div class="relative overflow-hidden">
    {{-- decor --}}
    <div aria-hidden class="pointer-events-none absolute -top-12 -left-14 h-56 w-56 rounded-full bg-[#E7B14B]/15 blob"></div>
    <div aria-hidden class="pointer-events-none absolute top-32 -right-16 h-72 w-72 rounded-full bg-white/10 blob" style="animation-delay:-2.2s"></div>
    <div aria-hidden class="pointer-events-none absolute bottom-10 left-1/3 h-52 w-52 rounded-full bg-[#E7B14B]/10 blob" style="animation-delay:-4.6s"></div>

    {{-- Header --}}
    <section class="pt-10 pb-6">
      <div class="rounded-3xl p-5 sm:p-7 {{ $card }}">
        <nav class="text-xs text-[#13392f]/70">
          <ol class="flex items-center gap-2">
            <li><a class="hover:text-[#13392f] hover:underline decoration-[#E7B14B] decoration-2" href="{{ route('home') }}">Beranda</a></li>
            <li class="text-black/25">/</li>
            <li><a class="hover:text-[#13392f] hover:underline decoration-[#E7B14B] decoration-2" href="{{ route('perpustakaan.index') }}">Perpustakaan</a></li>
            <li class="text-black/25">/</li>
            <li class="font-semibold text-[#13392f]">Ramah Anak</li>
          </ol>
        </nav>

        <div class="mt-4 grid gap-5 lg:grid-cols-12 lg:items-start">
          {{-- Left --}}
          <div class="lg:col-span-8">
            <div class="flex items-center gap-3">
              <div class="h-12 w-12 rounded-2xl bg-[#13392f] text-white grid place-items-center shadow floaty">
                {{-- flaticon-like: kid face --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none">
                  <path d="M12 21c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7 3.582 7 8 7Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                  <path d="M9 13.2h.01M15 13.2h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                  <path d="M8.6 16.2s1.4 1.5 3.4 1.5 3.4-1.5 3.4-1.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M7.5 11.2c.6-2 2.4-3.4 4.5-3.4s3.9 1.4 4.5 3.4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
              </div>

              <div>
                <p class="text-[11px] uppercase tracking-[0.30em] text-[#13392f]/70">Perpustakaan</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#13392f] leading-tight">
                  Jelajahi Ramah Anak
                </h1>
              </div>
            </div>

            <p class="mt-3 text-sm sm:text-base text-[#13392f]/80">
              Konten Islami untuk anak: <span class="font-semibold text-[#13392f]">video animasi</span>,
              <span class="font-semibold text-[#13392f]">dongeng PDF</span>, dan
              <span class="font-semibold text-[#13392f]">kata-kata islami</span>.
              Ringan, seru, dan mendidik.
            </p>

            {{-- Stats --}}
            <div class="mt-5 grid grid-cols-3 gap-3">
              <div class="rounded-2xl p-4 {{ $soft }}">
                <div class="flex items-center gap-2 text-[#13392f]">
                  <span class="h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center sparkle">
                    {{-- video icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                      <path d="M4.5 7.5A2.5 2.5 0 0 1 7 5h8a2.5 2.5 0 0 1 2.5 2.5v9A2.5 2.5 0 0 1 15 19H7a2.5 2.5 0 0 1-2.5-2.5v-9Z" stroke="currentColor" stroke-width="1.7"/>
                      <path d="M17.5 10.2 21 8.3v7.4l-3.5-1.9v-3.6Z" fill="currentColor" opacity=".9"/>
                    </svg>
                  </span>
                  <p class="text-xs text-[#13392f]/70 font-semibold">Video</p>
                </div>
                <p class="mt-2 text-2xl font-extrabold text-[#13392f]">{{ number_format($stats['video'] ?? 0) }}</p>
              </div>

              <div class="rounded-2xl p-4 {{ $soft }}">
                <div class="flex items-center gap-2 text-[#13392f]">
                  <span class="h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center sparkle" style="animation-delay:-.6s">
                    {{-- book icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                      <path d="M6 4.5h10A2.5 2.5 0 0 1 18.5 7v12.5H8A2.5 2.5 0 0 0 5.5 22V7A2.5 2.5 0 0 1 8 4.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                      <path d="M8 8h7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                      <path d="M8 11h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" opacity=".85"/>
                    </svg>
                  </span>
                  <p class="text-xs text-[#13392f]/70 font-semibold">Dongeng</p>
                </div>
                <p class="mt-2 text-2xl font-extrabold text-[#13392f]">{{ number_format($stats['dongeng'] ?? 0) }}</p>
              </div>

              <div class="rounded-2xl p-4 {{ $soft }}">
                <div class="flex items-center gap-2 text-[#13392f]">
                  <span class="h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center sparkle" style="animation-delay:-1.1s">
                    {{-- quote icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                      <path d="M7.5 11.5c0-2.2 1.3-4 3.5-4v3c-1 0-1.5.6-1.5 1.5V16H6.8c.4-1.1.7-2.5.7-4.5Z" fill="currentColor" opacity=".9"/>
                      <path d="M14.5 11.5c0-2.2 1.3-4 3.5-4v3c-1 0-1.5.6-1.5 1.5V16h-2.7c.4-1.1.7-2.5.7-4.5Z" fill="currentColor" opacity=".75"/>
                    </svg>
                  </span>
                  <p class="text-xs text-[#13392f]/70 font-semibold">Kata Islami</p>
                </div>
                <p class="mt-2 text-2xl font-extrabold text-[#13392f]">{{ number_format($stats['kata'] ?? 0) }}</p>
              </div>
            </div>
          </div>

          {{-- Right --}}
          <div class="lg:col-span-4">
            <div class="rounded-3xl p-5 {{ $soft }}">
              <p class="text-sm font-extrabold text-[#13392f]">Cari & Jelajahi</p>
              <p class="mt-1 text-xs text-[#13392f]/70">Pilih tab lalu cari judul / keyword.</p>

              <form method="GET" class="mt-4">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative">
                  <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[#13392f]/70">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                      <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                      <path d="M10.5 18a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15Z" stroke="currentColor" stroke-width="1.7"/>
                    </svg>
                  </span>
                  <input name="q" value="{{ $q ?? '' }}" placeholder="Cari konten anak..."
                         class="h-11 w-full rounded-2xl border border-black/10 bg-white px-11 pr-3 text-sm text-[#13392f] placeholder:text-[#13392f]/40 focus:outline-none focus:ring-2 focus:ring-[#E7B14B]/60">
                </div>
              </form>

              <div class="mt-4 grid grid-cols-3 gap-2">
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'video']) }}"
                   class="tab rounded-2xl border px-3 py-3 text-center text-xs font-extrabold {{ $tab==='video' ? 'tab-active' : 'border-black/10 bg-white text-[#13392f]/80 hover:bg-black/5' }}">
                  <div class="mx-auto mb-1 h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center">🎬</div>
                  Video
                </a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'dongeng']) }}"
                   class="tab rounded-2xl border px-3 py-3 text-center text-xs font-extrabold {{ $tab==='dongeng' ? 'tab-active' : 'border-black/10 bg-white text-[#13392f]/80 hover:bg-black/5' }}">
                  <div class="mx-auto mb-1 h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center">📖</div>
                  Dongeng
                </a>
                <a href="{{ request()->fullUrlWithQuery(['tab' => 'kata']) }}"
                   class="tab rounded-2xl border px-3 py-3 text-center text-xs font-extrabold {{ $tab==='kata' ? 'tab-active' : 'border-black/10 bg-white text-[#13392f]/80 hover:bg-black/5' }}">
                  <div class="mx-auto mb-1 h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center">💬</div>
                  Kata
                </a>
              </div>

              <div class="mt-4 rounded-2xl border border-black/10 bg-white p-4 text-xs text-[#13392f]/70">
                <div class="flex items-center gap-2">
                  <span class="floaty">🌟</span>
                  <span>Konten dikurasi admin agar aman & nyaman untuk anak.</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- CONTENT --}}
    <section class="pb-14">
      <div class="rounded-3xl p-4 sm:p-6 {{ $card }}">
        {{-- VIDEO --}}
        @if($tab === 'video')
          <div class="mb-4">
            <h2 class="text-lg font-extrabold text-[#13392f]">Video Animasi Islami</h2>
            <p class="text-sm text-[#13392f]/70">Tonton langsung tanpa keluar halaman.</p>
          </div>

          @if(($videos ?? collect())->isEmpty())
            <div class="py-12 text-center">
              <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-[#E7B14B]/25 grid place-items-center text-2xl">🎬</div>
              <p class="text-[#13392f] font-extrabold">Belum ada video.</p>
              <p class="text-sm text-[#13392f]/70 mt-1">Admin bisa menambahkan link YouTube dari dashboard.</p>
            </div>
          @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              @foreach($videos as $v)
                @php
                  $yt = $v->youtube_url ?? $v->url ?? null;
                  $ytId = null;
                  if ($yt) {
                      parse_str(parse_url($yt, PHP_URL_QUERY) ?? '', $qs);
                      $ytId = $qs['v'] ?? null;
                      if (!$ytId && str_contains($yt, 'youtu.be/')) {
                          $ytId = trim(parse_url($yt, PHP_URL_PATH) ?? '', '/');
                      }
                  }
                @endphp

                <article class="kid-card overflow-hidden rounded-3xl border border-black/10 bg-white shadow-sm">
                  <div class="aspect-video bg-black/10">
                    @if($ytId)
                      <iframe class="w-full h-full"
                        src="https://www.youtube.com/embed/{{ $ytId }}"
                        title="{{ $v->title ?? 'Video' }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                    @else
                      <div class="w-full h-full grid place-items-center text-sm text-[#13392f]/70 p-4">
                        Link YouTube tidak valid.
                      </div>
                    @endif
                  </div>

                  <div class="p-4">
                    <div class="flex items-center gap-2 text-xs text-[#13392f]/70">
                      <span class="h-8 w-8 rounded-2xl bg-[#E7B14B]/25 grid place-items-center sparkle">🎈</span>
                      <span class="font-semibold">Video Anak</span>
                    </div>
                    <h3 class="mt-2 text-[#13392f] font-extrabold line-clamp-2">{{ $v->title ?? 'Video Anak' }}</h3>
                    @if(!empty($v->description))
                      <p class="mt-1 text-sm text-[#13392f]/70 line-clamp-3">{{ $v->description }}</p>
                    @endif
                  </div>
                </article>
              @endforeach
            </div>
          @endif
        @endif

        {{-- DONGENG --}}
        @if($tab === 'dongeng')
          <div class="mb-4">
            <h2 class="text-lg font-extrabold text-[#13392f]">Dongeng Islami (PDF)</h2>
            <p class="text-sm text-[#13392f]/70">Unduh atau baca untuk menemani waktu bersama anak.</p>
          </div>

          @if(($dongeng ?? collect())->isEmpty())
            <div class="py-12 text-center">
              <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-[#E7B14B]/25 grid place-items-center text-2xl">📖</div>
              <p class="text-[#13392f] font-extrabold">Belum ada dongeng.</p>
              <p class="text-sm text-[#13392f]/70 mt-1">Admin bisa upload PDF dongeng dari dashboard.</p>
            </div>
          @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              @foreach($dongeng as $d)
                @php
                  $pdf = $d->file_pdf ?? $d->pdf ?? null;
                  if ($pdf && !str_starts_with($pdf, 'http')) $pdf = Storage::url($pdf);
                @endphp

                <article class="kid-card rounded-3xl border border-black/10 bg-white p-4 shadow-sm">
                  <div class="flex items-start gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-[#E7B14B]/25 grid place-items-center text-2xl floaty">🧩</div>
                    <div class="min-w-0">
                      <h3 class="text-[#13392f] font-extrabold line-clamp-2">{{ $d->title ?? 'Dongeng Islami' }}</h3>
                      <p class="mt-1 text-xs text-[#13392f]/70 line-clamp-3">{{ $d->description ?? 'Dongeng untuk anak-anak.' }}</p>
                    </div>
                  </div>

                  <div class="mt-4 flex gap-2">
                    @if($pdf)
                      <a href="{{ $pdf }}" target="_blank"
                         class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#13392f] px-3 py-2 text-xs font-extrabold text-white hover:brightness-110">
                        Unduh PDF
                      </a>
                      <a href="{{ $pdf }}" target="_blank"
                         class="inline-flex items-center justify-center rounded-2xl border border-black/10 bg-white px-3 py-2 text-xs font-extrabold text-[#13392f] hover:bg-black/5">
                        Baca
                      </a>
                    @else
                      <div class="text-sm text-[#13392f]/70">File PDF belum ada.</div>
                    @endif
                  </div>
                </article>
              @endforeach
            </div>
          @endif
        @endif

        {{-- KATA --}}
        @if($tab === 'kata')
          <div class="mb-4">
            <h2 class="text-lg font-extrabold text-[#13392f]">Kata-kata Islami untuk Anak</h2>
            <p class="text-sm text-[#13392f]/70">Kalimat pendek yang bisa jadi pengingat harian.</p>
          </div>

          @if(($kata ?? collect())->isEmpty())
            <div class="py-12 text-center">
              <div class="mx-auto mb-3 h-14 w-14 rounded-2xl bg-[#E7B14B]/25 grid place-items-center text-2xl">💬</div>
              <p class="text-[#13392f] font-extrabold">Belum ada kata-kata.</p>
              <p class="text-sm text-[#13392f]/70 mt-1">Admin bisa menambahkan quote dari dashboard.</p>
            </div>
          @else
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              @foreach($kata as $k)
                <article class="kid-card rounded-3xl border border-black/10 bg-white p-5 shadow-sm">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-[#13392f]/70">
                      <span class="h-9 w-9 rounded-2xl bg-[#E7B14B]/25 grid place-items-center sparkle">✨</span>
                      <span class="font-semibold">Kata Islami</span>
                    </div>
                    <span class="text-[11px] text-[#13392f]/55">Untuk Anak</span>
                  </div>

                  <p class="mt-4 text-[#13392f] font-extrabold leading-relaxed">
                    “{{ $k->quote_text ?? $k->description ?? $k->title ?? 'Semoga Allah memberkahimu.' }}”
                  </p>

                  @if(!empty($k->title))
                    <p class="mt-3 text-xs text-[#13392f]/70">— {{ $k->title }}</p>
                  @endif
                </article>
              @endforeach
            </div>
          @endif
        @endif
      </div>
    </section>
  </div>
</x-front-layout>
