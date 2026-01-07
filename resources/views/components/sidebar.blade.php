@php
    $user = auth()->user();
    $role = $user?->role;

    $kidsRouteName = 'admin.kids.index';

    $baseMenus = [
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Users', 'route' => 'admin.users.index'],
            ['label' => 'Profil Masjid', 'route' => 'admin.profil.edit'],
            ['label' => 'Struktur Masjid', 'route' => 'admin.mosque_structure.index'],
            ['label' => 'Kelola Proker', 'route' => 'admin.work_program.all'],
            ['label' => 'Artikel', 'route' => 'admin.artikels.index'],
            ['label' => 'Kegiatan', 'route' => 'admin.kegiatans.index'],
            ['label' => 'Pendaftar Kegiatan', 'route' => 'admin.pendaftaran-kegiatans.index'],
            ['label' => 'Donasi', 'route' => 'admin.donasis.index'],
            ['label' => 'Transaksi Donasi', 'route' => 'admin.transaksi-donasis.index'],
            ['label' => 'Galeri', 'route' => 'admin.galeris.index'],
            ['label' => 'Perpustakaan', 'route' => 'admin.perpustakaans.index'],
            ['label' => 'Ramah Anak', 'route' => $kidsRouteName],
            ['label' => 'Moderasi Tanya Ustadz', 'route' => 'admin.moderasi.index'],
        ],
        'pengurus' => [
            ['label' => 'Dashboard', 'route' => 'admin.pengurus.dashboard'],
            ['label' => 'Artikel', 'route' => 'admin.pengurus.artikel'],
            ['label' => 'Kegiatan', 'route' => 'admin.pengurus.kegiatan'],
            ['label' => 'Perpustakaan', 'route' => 'admin.pengurus.perpustakaan'],
            ['label' => 'Ramah Anak', 'route' => $kidsRouteName],
        ],
        'ustadz' => [
            ['label' => 'Pertanyaan Ditugaskan', 'route' => 'ustadz.pertanyaan.index'],
            ['label' => 'Riwayat Jawaban', 'route' => 'ustadz.pertanyaan.riwayat'],
            ['label' => 'Profil Saya', 'route' => 'profile.edit'],
        ],
        'jamaah' => [
            ['label' => 'Beranda', 'route' => 'home'],
            ['label' => 'Artikel', 'route' => 'artikel.index'],
            ['label' => 'Kegiatan', 'route' => 'kegiatan.index'],
            ['label' => 'Riwayat Kegiatan', 'route' => 'kegiatan.riwayat'],
            ['label' => 'Donasi', 'route' => 'donasi.index'],
            ['label' => 'Riwayat Donasi', 'route' => 'donasi.riwayat'],
            ['label' => 'Galeri', 'route' => 'galeri.index'],
            ['label' => 'Perpustakaan', 'route' => 'perpustakaan.index'],
            ['label' => 'Tanya Ustadz', 'route' => 'tanya-ustadz.index'],
            ['label' => 'Pertanyaan Saya', 'route' => 'tanya-ustadz.my'],
            ['label' => 'Profil Saya', 'route' => 'profile.edit'],
        ],
    ];

    $menus = $user ? ($baseMenus[$role] ?? []) : [];

    $brand = config('app.name', 'Web Masjid');

    // helper buat render list menu
    $renderMenu = function () use ($menus) {
        foreach ($menus as $menu) {
            $isActive =
                request()->routeIs($menu['route']) ||
                request()->routeIs($menu['route'].'.*');

            $href = '#';
            if (is_string($menu['route']) && \Illuminate\Support\Facades\Route::has($menu['route'])) {
                $href = route($menu['route']);
            }

            echo '<a href="'.$href.'" class="flex items-center justify-between rounded px-3 py-2 text-sm font-medium '.
                ($isActive ? 'bg-emerald-100 text-emerald-800' : 'text-gray-600 hover:bg-gray-100').
                '">';
            echo '<span>'.$menu['label'].'</span>';

            if (($menu['label'] ?? '') === 'Ramah Anak') {
                echo '<span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-emerald-900">ANAK</span>';
            }

            echo '</a>';
        }
    };
@endphp

@if($user)
<div x-data="{ open: false }" class="w-full">

    {{-- MOBILE TOP BAR --}}
    <div class="lg:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3">
        <button
            type="button"
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100"
            @click="open = true"
            aria-label="Buka menu"
        >
            {{-- icon hamburger --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="text-base font-semibold text-emerald-700">
            {{ $brand }}
        </div>

        <div class="w-10"></div>
    </div>

    {{-- MOBILE OVERLAY --}}
    <div
        x-show="open"
        x-transition.opacity
        class="lg:hidden fixed inset-0 z-40 bg-black/40"
        @click="open = false"
        aria-hidden="true"
    ></div>

    {{-- MOBILE DRAWER --}}
    <aside
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="lg:hidden fixed left-0 top-0 z-50 h-full w-72 bg-white border-r border-gray-200 px-4 py-6"
        @keydown.escape.window="open = false"
    >
        <div class="flex items-center justify-between">
            <div class="text-xl font-semibold text-emerald-700">
                {{ $brand }}
            </div>

            <button
                type="button"
                class="rounded-md p-2 text-gray-700 hover:bg-gray-100"
                @click="open = false"
                aria-label="Tutup menu"
            >
                {{-- icon X --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="mt-8 space-y-2">
            {!! $renderMenu() !!}
        </nav>
    </aside>

    {{-- DESKTOP SIDEBAR --}}
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200 px-4 py-6">
        <div class="flex items-center gap-2 px-2 text-xl font-semibold text-emerald-700">
            <span>{{ $brand }}</span>
        </div>

        <nav class="mt-8 space-y-2">
            {!! $renderMenu() !!}
        </nav>
    </aside>

</div>
@endif
