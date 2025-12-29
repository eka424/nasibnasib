<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Web Masjid') }} - Beranda Jamaah</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="font-sans antialiased bg-[#70978C] text-[#1E4D40]">
    @unless($immersive ?? false)
@php
$navLinks = [

  ['label' => 'Beranda', 'route' => 'home'], // ✅ paling ujung
    ['label' => 'Artikel', 'route' => 'artikel.index'],
    ['label' => 'Kegiatan', 'route' => 'kegiatan.index'],
    ['label' => 'Donasi', 'route' => 'donasi.index'],
    ['label' => 'Galeri', 'route' => 'galeri.index'],
    ['label' => 'Perpustakaan', 'route' => 'perpustakaan.index'],
    ['label' => 'Tanya Ustadz', 'route' => 'tanya-ustadz.index'],
];

@endphp

    @endunless

    <div class="min-h-screen flex flex-col">
      @unless($immersive ?? false)
        <!-- Top Bar -->
        <header class="sticky top-0 z-30">
          <div class="border-b border-white/25 bg-white/75 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4">
              <nav class="flex items-center justify-between py-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                  <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#1E4D40] text-white shadow-sm">
                    <x-application-logo class="w-6 h-6 text-white" />
                  </span>
                  <div class="leading-tight">
                    <div class="text-base sm:text-lg font-semibold text-[#1E4D40]">
                      {{ config('app.name', 'Web Masjid') }}
                    </div>
                    <div class="text-xs text-[#1E4D40]/70">Portal Jamaah</div>
                  </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden lg:flex items-center gap-1 rounded-full bg-white/80 p-1 border border-white/40">
                  {{-- DROPDOWN PROFIL MASJID --}}
<div class="relative group">
  <a href="{{ route('mosque.profile') }}"
     class="px-4 py-2 text-sm font-semibold rounded-full transition
            {{ request()->routeIs('mosque.*')
                ? 'bg-[#EBB04D] text-[#1E4D40] shadow-sm'
                : 'text-[#1E4D40]/80 hover:bg-[#EBB04D]/30 hover:text-[#1E4D40]' }}">
    Profil Masjid
  </a>

  <div class="absolute left-0 mt-2 w-56 rounded-xl bg-white shadow-xl
              border border-gray-200 opacity-0 invisible
              group-hover:opacity-100 group-hover:visible
              transition duration-150 z-50">
    <div class="p-2 space-y-1 text-sm">

      <a href="{{ route('mosque.profile') }}"
         class="block px-3 py-2 rounded-lg hover:bg-emerald-50">
        Profil Masjid
      </a>

      <a href="{{ route('mosque.struktur') }}"
         class="block px-3 py-2 rounded-lg hover:bg-emerald-50">
        Struktur Organisasi
      </a>

      <a href="{{ route('mosque.profile') }}#sejarah"
         class="block px-3 py-2 rounded-lg hover:bg-emerald-50">
        Sejarah Masjid
      </a>

      <a href="{{ route('mosque.work_program') }}"
         class="block px-3 py-2 rounded-lg hover:bg-emerald-50">
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
                                  ? 'bg-[#EBB04D] text-[#1E4D40] shadow-sm'
                                  : 'text-[#1E4D40]/80 hover:bg-[#EBB04D]/30 hover:text-[#1E4D40]' }}">
                      {{ $link['label'] }}
                    </a>
                  @endforeach
                </div>

                <!-- Auth Actions -->
                <div class="flex items-center gap-2 text-sm font-semibold">
                  @auth
                    <a href="{{ route('profile.edit') }}"
                       class="hidden sm:inline-flex px-3 py-2 rounded-full bg-white/70 border border-white/40 text-[#1E4D40] hover:bg-white">
                      Akun Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#1E4D40] text-white border border-white/25 shadow-sm transition hover:-translate-y-0.5 hover:bg-[#163b31]">
                        Logout
                      </button>
                    </form>
                  @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-2 rounded-full bg-white/70 border border-white/40 text-[#1E4D40] hover:bg-white">
                      Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full bg-[#EBB04D] text-[#1E4D40] shadow-sm border border-white/30 transition hover:-translate-y-0.5 hover:brightness-105">
                      Daftar
                    </a>
                  @endauth
                </div>
              </nav>
            </div>
          </div>
        </header>
      @endunless

        <!-- Page Content -->
        <div class="max-w-6xl mx-auto px-4 pb-10">
          {{ $slot }}
        </div>
      </main>

      @unless($immersive ?? false)
        <footer class="mt-auto">
          <div class="bg-white/70 backdrop-blur border-t border-white/25">
            <div class="max-w-6xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8 text-sm">
              <div>
                <h3 class="text-[#1E4D40] text-lg font-semibold mb-3">{{ config('app.name', 'Web Masjid') }}</h3>
                <p class="text-[#1E4D40]/80">
                  Sarana informasi jamaah. Artikel, kegiatan, donasi, dan konsultasi ustadz dalam satu aplikasi.
                </p>
              </div>

              <div>
                <h4 class="text-[#1E4D40] font-semibold mb-3">Navigasi</h4>
                <ul class="space-y-2">
                  @foreach ($navLinks as $link)
                    <li>
                      <a class="text-[#1E4D40]/80 hover:text-[#1E4D40] hover:underline decoration-[#EBB04D] decoration-2"
                         href="{{ route($link['route']) }}">
                        {{ $link['label'] }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>

              <div>
                <h4 class="text-[#1E4D40] font-semibold mb-3">Kontak</h4>
                <p class="text-[#1E4D40]/80">Jl. Masjid Raya No.1, Gianyar</p>
                <p class="text-[#1E4D40]/80">Email: info@masjidala.id</p>
                <p class="text-[#1E4D40]/80">Telepon: 0812-3456-7890</p>
              </div>
            </div>

            <div class="border-t border-white/25 text-center py-3 text-xs text-[#1E4D40]/70">
              &copy; {{ now()->year }} {{ config('app.name', 'Web Masjid') }}. All rights reserved.
            </div>
          </div>
        </footer>
      @endunless
    </div>

    <!-- Floating: Tanya Ustadz (route tetap) -->
    @unless(request()->routeIs('tanya-ustadz.*'))
      <a href="{{ route('tanya-ustadz.index') }}" aria-label="Tanya Ustadz"
         class="fixed bottom-4 right-4 z-40 inline-flex items-center gap-2 rounded-full bg-[#EBB04D] px-4 py-2 text-sm font-bold text-[#1E4D40]
                shadow-xl shadow-black/15 border border-white/30 transition hover:-translate-y-0.5 hover:brightness-105 md:bottom-6 md:right-6">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/70 text-[#1E4D40] border border-white/40">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9a4 4 0 1 1 8 0c0 1.673-.833 2.504-2.005 3.557-.535.49-.995.913-.995 1.693V16m0 2h.01" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18Z" />
          </svg>
        </span>
        <span class="pr-1">Tanya Ustadz</span>
      </a>
    @endunless
  </body>
</html>
