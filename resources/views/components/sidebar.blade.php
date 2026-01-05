@php
    $user = auth()->user();
    $role = $user?->role;

    /**
     * Pilih route admin ramah anak yang benar.
     * Kamu sebelumnya pakai:
     * Route::get('/ramah-anak', ...)->name('kids.index');
     * jadi route name = admin.kids.index
     */
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

            // ✅ MENU BARU: RAMAH ANAK
            ['label' => 'Ramah Anak', 'route' => $kidsRouteName],

            ['label' => 'Moderasi Tanya Ustadz', 'route' => 'admin.moderasi.index'],
        ],

        'pengurus' => [
            ['label' => 'Dashboard', 'route' => 'admin.pengurus.dashboard'],
            ['label' => 'Artikel', 'route' => 'admin.pengurus.artikel'],
            ['label' => 'Kegiatan', 'route' => 'admin.pengurus.kegiatan'],
            ['label' => 'Perpustakaan', 'route' => 'admin.pengurus.perpustakaan'],

            // ✅ MENU BARU: RAMAH ANAK (kalau pengurus boleh kelola)
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
@endphp

@if($user)
    <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200 px-4 py-6">
        <div class="flex items-center gap-2 px-2 text-xl font-semibold text-emerald-700">
            <span>{{ config('app.name', 'Web Masjid') }}</span>
        </div>

        <nav class="mt-8 space-y-2">
            @foreach ($menus as $menu)
                @php
                    // active state
                    $isActive =
                        request()->routeIs($menu['route']) ||
                        request()->routeIs($menu['route'].'.*');

                    // aman kalau route belum ada
                    $href = '#';
                    if (is_string($menu['route']) && \Illuminate\Support\Facades\Route::has($menu['route'])) {
                        $href = route($menu['route']);
                    }
                @endphp

                <a href="{{ $href }}"
                   class="flex items-center justify-between rounded px-3 py-2 text-sm font-medium
                          {{ $isActive ? 'bg-emerald-100 text-emerald-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span>{{ $menu['label'] }}</span>

                    @if($menu['label'] === 'Ramah Anak')
                        <span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-emerald-900">
                            ANAK
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>
    </aside>
@endif
