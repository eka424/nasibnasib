<header id="top-kegiatan" class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Kegiatan</h1>
            <p class="text-gray-600">
                Pengurus bisa membuat, mengubah jadwal, dan memantau pendaftar (tanpa hapus massal).
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kegiatans.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary/90">
                <span class="text-lg leading-none">+</span>
                Buat Kegiatan
            </a>
        </div>
    </div>
</header>

<main id="buat-kegiatan" class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <div class="text-sm font-medium">Total Kegiatan</div>
                    <div class="text-xs text-muted-foreground">📢</div>
                </div>
                <div class="px-4 pb-4">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Semua kegiatan yang tercatat</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <div class="text-sm font-medium">Mendatang</div>
                    <div class="text-xs text-muted-foreground">⏳</div>
                </div>
                <div class="px-4 pb-4">
                    <div class="text-2xl font-bold">{{ $stats['upcoming'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Sedang disiapkan</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <div class="text-sm font-medium">Berlangsung</div>
                    <div class="text-xs text-muted-foreground">✅</div>
                </div>
                <div class="px-4 pb-4">
                    <div class="text-2xl font-bold">{{ $stats['ongoing'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Sedang jalan</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <div class="text-sm font-medium">Peserta Terdaftar</div>
                    <div class="text-xs text-muted-foreground">👥</div>
                </div>
                <div class="px-4 pb-4">
                    <div class="text-2xl font-bold">{{ $stats['participants'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Total pendaftar semua kegiatan</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div class="border-b px-4 py-3">
                <h2 class="text-base font-semibold">Filter Kegiatan</h2>
                <p class="text-sm text-muted-foreground">
                    Saring kegiatan berdasarkan kriteria tertentu. Ubah status massal perlu persetujuan admin.
                </p>
            </div>
            <form method="GET" action="{{ route('admin.pengurus.kegiatan') }}"
                class="flex flex-col gap-4 px-4 py-4 md:flex-row">
                <div class="relative flex-1">
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Cari nama kegiatan..." />
                </div>

                <div>
                    <select name="status"
                        class="w-[180px] rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Status</option>
                        <option value="upcoming" @selected(($filters['status'] ?? '') === 'upcoming')>Mendatang</option>
                        <option value="ongoing" @selected(($filters['status'] ?? '') === 'ongoing')>Berlangsung</option>
                        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Selesai</option>
                    </select>
                </div>

                <div>
                    <select name="time"
                        class="w-[180px] rounded-md border bg-background px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Waktu</option>
                        <option value="today" @selected(($filters['time'] ?? '') === 'today')>Hari ini</option>
                        <option value="this_week" @selected(($filters['time'] ?? '') === 'this_week')>Minggu ini</option>
                        <option value="this_month" @selected(($filters['time'] ?? '') === 'this_month')>Bulan ini</option>
                        <option value="past" @selected(($filters['time'] ?? '') === 'past')>Lampau</option>
                    </select>
                </div>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-md border bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                    Filter
                </button>
            </form>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div class="border-b px-4 py-3">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📢</span>
                    <h2 class="text-base font-semibold">Daftar Kegiatan</h2>
                </div>
                <p class="text-sm text-muted-foreground">
                    Menampilkan {{ $kegiatans->count() }} dari {{ $kegiatans->total() }} kegiatan
                </p>
            </div>

            <div class="px-4 py-4">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b">
                            <tr class="text-xs text-muted-foreground">
                                <th class="py-2 pr-4 font-medium">Nama Kegiatan</th>
                                <th class="hidden py-2 pr-4 font-medium md:table-cell">Lokasi</th>
                                <th class="hidden py-2 pr-4 font-medium md:table-cell">Tanggal</th>
                                <th class="py-2 pr-4 font-medium">Status</th>
                                <th class="hidden py-2 pr-4 font-medium lg:table-cell">Peserta</th>
                                <th class="py-2 pr-4 font-medium text-right">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($kegiatans as $kegiatan)
                                @php
                                    $mulai = optional($kegiatan->tanggal_mulai)->format('Y-m-d');
                                    $selesai = optional($kegiatan->tanggal_selesai)->format('Y-m-d');
                                    $now = now();
                                    $statusLabel = 'Mendatang';
                                    $statusClass = 'bg-gray-100 text-gray-700';
                                    if ($kegiatan->tanggal_mulai && $kegiatan->tanggal_mulai->lte($now) && (!$kegiatan->tanggal_selesai || $kegiatan->tanggal_selesai->gte($now))) {
                                        $statusLabel = 'Berlangsung';
                                        $statusClass = 'bg-emerald-100 text-emerald-700';
                                    } elseif ($kegiatan->tanggal_selesai && $kegiatan->tanggal_selesai->lt($now)) {
                                        $statusLabel = 'Selesai';
                                        $statusClass = 'bg-slate-200 text-slate-800';
                                    }
                                @endphp
                                <tr>
                                    <td class="py-3 pr-4 align-top">
                                        <div class="font-medium">{{ $kegiatan->nama_kegiatan }}</div>
                                        <div class="text-sm text-muted-foreground md:hidden">
                                            {{ $kegiatan->lokasi ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="hidden py-3 pr-4 align-top md:table-cell">
                                        {{ $kegiatan->lokasi ?? '-' }}
                                    </td>
                                    <td class="hidden py-3 pr-4 align-top md:table-cell">
                                        {{ $mulai }}{{ $selesai ? ' - '.$selesai : '' }}
                                    </td>
                                    <td class="py-3 pr-4 align-top">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="hidden py-3 pr-4 align-top lg:table-cell">
                                        {{ $kegiatan->pendaftarans_count ?? 0 }}
                                    </td>
                                    <td class="py-3 pr-0 text-right align-top">
                                        <div class="flex justify-end gap-1 text-xs">
                                            <a href="{{ route('kegiatan.show', $kegiatan) }}"
                                                class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                                👁️ Preview
                                            </a>
                                            <a href="{{ route('admin.kegiatans.edit', $kegiatan) }}"
                                                class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                                🗓️ Edit
                                            </a>
                                            <a href="{{ route('admin.kegiatans.show', $kegiatan) }}"
                                                class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                                👥 Pendaftar
                                            </a>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-md border border-dashed px-2 py-1 text-muted-foreground"
                                                title="Hapus hanya oleh admin">
                                                🔒 Hapus
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 text-center text-sm text-muted-foreground">
                                        Belum ada kegiatan. Mulai dengan membuat kegiatan baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-2 flex items-center justify-between px-4 pb-4 pt-0">
                <div class="text-xs text-muted-foreground">
                    Menampilkan <strong>{{ $kegiatans->firstItem() ?? 0 }}-{{ $kegiatans->lastItem() ?? 0 }}</strong> dari <strong>{{ $kegiatans->total() }}</strong> kegiatan
                </div>
                <div class="flex items-center gap-2 text-xs">
                    {{ $kegiatans->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
