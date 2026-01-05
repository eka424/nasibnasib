<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="icon" type="image/png" href="{{ asset('images/logomasjidar.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logomasjidar.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#13392f] text-white">
        <div class="min-h-screen flex">
            @auth
                <x-sidebar />
            @endauth

            <div class="flex-1 flex flex-col min-h-screen">
                <x-navbar />

                <main class="flex-1 p-6">
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
