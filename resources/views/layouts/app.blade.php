<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logomasjidar.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logomasjidar.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="font-sans antialiased bg-[#13392f] text-white">
    {{-- Wrapper: desktop flex, mobile stack --}}
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">

      @auth
        {{-- MOBILE TOP BAR (hamburger) --}}
        <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between bg-white text-slate-900 border-b border-slate-200 px-4 py-3">
          <button type="button"
                  class="inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100"
                  @click="sidebarOpen = true"
                  aria-label="Buka menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <div class="text-base font-semibold text-emerald-700 truncate">
            {{ config('app.name', 'Laravel') }}
          </div>

          <div class="w-10"></div>
        </div>

        {{-- MOBILE OVERLAY --}}
        <div x-show="sidebarOpen" x-transition.opacity
             class="lg:hidden fixed inset-0 z-40 bg-black/40"
             @click="sidebarOpen = false"
             aria-hidden="true"></div>

        {{-- MOBILE DRAWER SIDEBAR --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="lg:hidden fixed left-0 top-0 z-50 h-full w-72 bg-white text-slate-900 border-r border-slate-200"
             @keydown.escape.window="sidebarOpen = false">

          {{-- tombol tutup --}}
          <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <div class="font-semibold text-emerald-700 truncate">
              {{ config('app.name', 'Laravel') }}
            </div>

            <button type="button"
                    class="rounded-md p-2 text-slate-700 hover:bg-slate-100"
                    @click="sidebarOpen = false"
                    aria-label="Tutup menu">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          {{-- sidebar component --}}
          <div class="h-[calc(100vh-56px)] overflow-y-auto">
            <x-sidebar />
          </div>
        </div>

        {{-- DESKTOP SIDEBAR (tetap seperti sekarang) --}}
        <div class="hidden lg:block w-64 shrink-0">
  <x-sidebar />
</div>
      @endauth

      {{-- RIGHT SIDE (navbar + content) --}}
      <div class="flex-1 flex flex-col min-h-screen min-w-0">
        {{-- Navbar kamu tetap dipakai (desktop tidak berubah) --}}
        <x-navbar />

        {{-- Konten: di mobile padding lebih kecil, desktop tetap 6 --}}
        <main class="flex-1 px-4 py-4 sm:px-6 sm:py-6 text-slate-900">

          <x-flash />
          @isset($slot)
            {{ $slot }}
          @else
            @yield('content')
          @endisset
        </main>
      </div>
    </div>
  </body>
</html>
