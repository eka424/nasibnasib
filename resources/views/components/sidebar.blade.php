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
            ['label' => 'Galeri', 'route' => 'galeri.index'],
            ['label' => 'Perpustakaan', 'route' => 'perpustakaan.index'],
            ['label' => 'Sedekahmu', 'route' => 'sedekah.riwayat'],
            ['label' => 'Tanya Ustadz', 'route' => 'tanya-ustadz.index'],
            ['label' => 'Pertanyaan Saya', 'route' => 'tanya-ustadz.my'],
            ['label' => 'Profil Saya', 'route' => 'profile.edit'],
        ],
    ];

    $menus = $user ? ($baseMenus[$role] ?? []) : [];

    // helper label role
    $roleLabel = match($role) {
        'admin' => 'Admin',
        'pengurus' => 'Pengurus',
        'ustadz' => 'Ustadz',
        'jamaah' => 'Jamaah',
        default => 'User',
    };
@endphp

@if($user)
<aside class="w-64 bg-white border-r border-slate-200 text-slate-900">
    {{-- header/sidebar brand --}}
    <div class="px-4 py-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-700 text-white font-bold">
                {{ mb_substr(config('app.name', 'WM'), 0, 1) }}
            </div>
            <div class="min-w-0">
                <div class="truncate text-base font-semibold text-emerald-700">
                    {{ config('app.name', 'Web Masjid') }}
                </div>
                <div class="text-xs text-slate-500">
                    Panel {{ $roleLabel }}
                </div>
            </div>
        </div>
    </div>

    <div class="px-4">
        <div class="h-px bg-slate-200/70"></div>
    </div>

    {{-- menu --}}
    <nav class="px-3 py-4">
        <div class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
            Menu
        </div>

        <div class="space-y-1">
            @foreach ($menus as $menu)
                @php
                    $routeName = $menu['route'];

                    $isActive =
                        request()->routeIs($routeName) ||
                        request()->routeIs($routeName . '.*');

                    $href = '#';
                    if (is_string($routeName) && \Illuminate\Support\Facades\Route::has($routeName)) {
                        $href = route($routeName);
                    }

                    $isKids = (($menu['label'] ?? '') === 'Ramah Anak');
                @endphp

                <a href="{{ $href }}"
                   class="group flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition
                          {{ $isActive
                                ? 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200'
                                : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="truncate">{{ $menu['label'] }}</span>

                    @if($isKids)
                        <span class="ml-2 shrink-0 rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-emerald-900">
                            ANAK
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- footer kecil --}}
        <div class="mt-6 rounded-xl bg-slate-50 px-3 py-3">
            <div class="text-xs font-semibold text-slate-600">Login sebagai</div>
            <div class="mt-1 truncate text-sm text-slate-900">{{ $user->name ?? 'User' }}</div>
            <div class="text-xs text-slate-500 truncate">{{ $user->email ?? '' }}</div>
        </div>
    </nav>
</aside>
@endif
