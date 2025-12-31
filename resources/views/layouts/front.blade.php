<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Web Masjid') }} - Beranda Jamaah</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logomasjidar.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logomasjidar.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  {{-- BACKGROUND FIX: solid #13392f, no gradient --}}
  <body class="font-sans antialiased bg-[#13392f] text-white">
    @unless($immersive ?? false)
      @php
        $navLinks = [
  ['label' => 'Beranda', 'route' => 'home'],
  ['label' => 'Artikel', 'route' => 'artikel.index'],
  ['label' => 'Kegiatan', 'route' => 'kegiatan.index'],
  ['label' => 'Sedekah Masjid', 'route' => 'sedekah.index'],
  ['label' => 'Galeri', 'route' => 'galeri.index'],
  ['label' => 'Perpustakaan', 'route' => 'perpustakaan.index'],
  ['label' => 'Tanya Ustadz', 'route' => 'tanya-ustadz.index'],
];
      @endphp
    @endunless

    <div class="min-h-screen flex flex-col overflow-x-hidden">
      @unless($immersive ?? false)
        {{-- TOP BAR: creamy/white tone, clean & premium --}}
        <header class="sticky top-0 z-30">
          <div class="border-b border-black/5 bg-[#F5F1E8]/90 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
              <nav class="flex items-center justify-between py-3">
                {{-- Brand --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                  <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#13392f] text-white shadow-sm">
                    <img src="{{ asset('images/logomasjidwhite.png') }}" alt="Logo Masjid" class="h-7 w-7 object-contain">
                  </span>
                  <div class="leading-tight">
                    <div class="text-base sm:text-lg font-semibold text-[#13392f]">
                      {{ config('app.name', 'Web Masjid') }}
                    </div>
                    <div class="text-xs text-[#13392f]/70">Portal Jamaah</div>
                  </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-1 rounded-full bg-white/70 p-1 border border-black/10">
                  {{-- DROPDOWN PROFIL MASJID --}}
                  <div class="relative group">
                    <a href="{{ route('mosque.profile') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-full transition
                              {{ request()->routeIs('mosque.*')
                                  ? 'bg-[#E7B14B] text-[#13392f] shadow-sm'
                                  : 'text-[#13392f]/80 hover:bg-[#E7B14B]/20 hover:text-[#13392f]' }}">
                      Profil Masjid
                    </a>

                    <div class="absolute left-0 mt-2 w-56 rounded-xl bg-white shadow-xl
                                border border-black/10 opacity-0 invisible
                                group-hover:opacity-100 group-hover:visible
                                transition duration-150 z-50">
                      <div class="p-2 space-y-1 text-sm text-[#13392f]">
                        <a href="{{ route('mosque.profile') }}" class="block px-3 py-2 rounded-lg hover:bg-black/5">
                          Profil Masjid
                        </a>
                        <a href="{{ route('mosque.struktur') }}" class="block px-3 py-2 rounded-lg hover:bg-black/5">
                          Struktur Organisasi
                        </a>
                        <a href="{{ route('mosque.profile') }}#sejarah" class="block px-3 py-2 rounded-lg hover:bg-black/5">
                          Sejarah Masjid
                        </a>
                        <a href="{{ route('mosque.work_program') }}" class="block px-3 py-2 rounded-lg hover:bg-black/5">
                          Program Kerja
                        </a>
                      </div>
                    </div>
                  </div>

                  @foreach ($navLinks as $link)
                    @php
                      $active = request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*');
                    @endphp

                    <a href="{{ route($link['route']) }}"
                       class="px-4 py-2 text-sm font-semibold rounded-full transition
                              {{ $active
                                  ? 'bg-[#E7B14B] text-[#13392f] shadow-sm'
                                  : 'text-[#13392f]/80 hover:bg-[#E7B14B]/20 hover:text-[#13392f]' }}">
                      {{ $link['label'] }}
                    </a>
                  @endforeach
                </div>

                {{-- Auth Actions --}}
                <div class="flex items-center gap-2 text-sm font-semibold">
                  @auth
                    <a href="{{ route('profile.edit') }}"
                       class="hidden sm:inline-flex px-3 py-2 rounded-full bg-white/70 border border-black/10 text-[#13392f] hover:bg-white">
                      Akun Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#13392f] text-white
                               border border-black/10 shadow-sm transition hover:-translate-y-0.5 hover:brightness-105">
                        Logout
                      </button>
                    </form>
                  @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-2 rounded-full bg-white/70 border border-black/10 text-[#13392f] hover:bg-white">
                      Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full bg-[#E7B14B] text-[#13392f] shadow-sm border border-black/10
                              transition hover:-translate-y-0.5 hover:brightness-105">
                      Daftar
                    </a>
                  @endauth
                </div>
                {{-- MOBILE MENU BUTTON --}}
<div class="lg:hidden flex items-center">
  <button
    type="button"
    class="inline-flex items-center justify-center rounded-xl bg-[#13392f] p-2 text-white shadow"
    onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
    aria-label="Buka Menu">
    {{-- ICON HAMBURGER --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
  </button>
</div>

              </nav>
            </div>
          </div>
        </header>
        {{-- MOBILE MENU --}}
<div id="mobileMenu" class="lg:hidden hidden border-b border-black/10 bg-[#F5F1E8]">
  <div class="px-4 py-4 space-y-2">
    {{-- MOBILE: PROFIL MASJID DROPDOWN --}}
<div class="rounded-xl border border-black/10 bg-white/70 overflow-hidden">
  <button
    type="button"
    class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-[#13392f]"
    onclick="document.getElementById('mobileProfilMasjid').classList.toggle('hidden')"
    aria-expanded="false"
  >
    <span>Profil Masjid</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
    </svg>
  </button>

  <div id="mobileProfilMasjid" class="hidden border-t border-black/10 bg-white">
    <a href="{{ route('mosque.profile') }}"
       class="block px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:bg-black/5">
      Profil Masjid
    </a>
    <a href="{{ route('mosque.struktur') }}"
       class="block px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:bg-black/5">
      Struktur Organisasi
    </a>
    <a href="{{ route('mosque.profile') }}#sejarah"
       class="block px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:bg-black/5">
      Sejarah Masjid
    </a>
    <a href="{{ route('mosque.work_program') }}"
       class="block px-4 py-2.5 text-sm font-semibold text-[#13392f] hover:bg-black/5">
      Program Kerja
    </a>
  </div>
</div>

    @foreach ($navLinks as $link)
      <a href="{{ route($link['route']) }}"
         class="block rounded-lg px-4 py-2 text-sm font-semibold text-[#13392f] hover:bg-[#E7B14B]/20">
        {{ $link['label'] }}
      </a>
    @endforeach

    <hr class="my-2 border-black/10">

    @auth
      <a href="{{ route('profile.edit') }}"
         class="block rounded-lg px-4 py-2 text-sm font-semibold text-[#13392f] hover:bg-black/5">
        Akun Saya
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="w-full text-left rounded-lg px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
          Logout
        </button>
      </form>
    @else
      <a href="{{ route('login') }}"
         class="block rounded-lg px-4 py-2 text-sm font-semibold text-[#13392f] hover:bg-black/5">
        Masuk
      </a>
      <a href="{{ route('register') }}"
         class="block rounded-lg px-4 py-2 text-sm font-semibold text-[#13392f] hover:bg-black/5">
        Daftar
      </a>
    @endauth
  </div>
</div>

      @endunless

      {{-- Page Content --}}
      <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-10">
          {{ $slot }}
        </div>
      </main>

      @unless($immersive ?? false)
        {{-- FOOTER: creamy/white also, biar konsisten --}}
        <footer class="mt-auto">
          <div class="bg-[#F5F1E8]/90 backdrop-blur border-t border-black/5">
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-4 gap-8 text-sm">
  <div>
    <h3 class="text-[#13392f] text-lg font-semibold mb-3">{{ config('app.name', 'Web Masjid') }}</h3>
    <p class="text-[#13392f]/80">
      Sarana informasi jamaah. Artikel, kegiatan, donasi, dan konsultasi ustadz dalam satu aplikasi.
    </p>
  </div>

  <div>
    <h4 class="text-[#13392f] font-semibold mb-3">Navigasi</h4>
    <ul class="space-y-2">
      @foreach ($navLinks as $link)
        <li>
          <a class="text-[#13392f]/80 hover:text-[#13392f] hover:underline decoration-[#E7B14B] decoration-2"
             href="{{ route($link['route']) }}">
            {{ $link['label'] }}
          </a>
        </li>
      @endforeach
    </ul>
  </div>

  <div>
    <h4 class="text-[#13392f] font-semibold mb-3">Kontak</h4>
    <p class="text-[#13392f]/80">Jl. Kesatrian No.16, Gianyar, Kec. Gianyar, Kabupaten Gianyar, Bali 80511, Indonesia</p>
    <p class="text-[#13392f]/80">Email: masjidagung.gianyar@gmail.com</p>
    <p class="text-[#13392f]/80">Telepon: 081337669467</p>
  </div>

  {{-- MAPS --}}
  <div>
    <h4 class="text-[#13392f] font-semibold mb-3">Lokasi Masjid</h4>
    <div class="overflow-hidden rounded-2xl border border-black/10 shadow-sm bg-white">
      <iframe
        title="Google Maps - Masjid Agung Al-A'la Gianyar"
        class="w-full h-48"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=Masjid%20Agung%20Al-A'la%20Gianyar&output=embed">
      </iframe>
    </div>

    <a
      href="https://www.google.com/maps/search/?api=1&query=Masjid%20Agung%20Al-A'la%20Gianyar"
      target="_blank"
      rel="noopener"
      class="mt-3 inline-flex items-center justify-center rounded-full bg-[#13392f] px-4 py-2 text-xs font-semibold text-white hover:brightness-105">
      Buka di Google Maps
    </a>
  </div>
</div>

            <div class="border-t border-black/10 text-center py-3 text-xs text-[#13392f]/70">
              &copy; {{ now()->year }} {{ config('app.name', 'Web Masjid') }}. All rights reserved.
            </div>
          </div>
        </footer>
      @endunless
    </div>
  </body>
</html>
