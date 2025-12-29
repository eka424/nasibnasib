@php
    $search = $search ?? '';
    $statusFilter = $statusFilter ?? '';
    $artikels = $artikels
        ?? new \Illuminate\Pagination\LengthAwarePaginator(
            collect(),
            0,
            10,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    $latestArtikel = $latestArtikel ?? $artikels->first();
    $totalArtikel = $totalArtikel ?? $artikels->total();
    $publishedArtikel = $publishedArtikel ?? 0;
    $draftArtikel = $draftArtikel ?? 0;
@endphp

<header id="top-artikel"
    class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Artikel</h2>
            <p class="text-gray-600">Pengurus bisa menulis, mengedit, dan mem-publish artikel (tanpa hapus).</p>
        </div>

        <div class="flex items-center gap-2">
            <div class="hidden text-xs text-muted-foreground sm:inline-flex sm:items-center sm:gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-400"></span> Preview sebelum publish, hapus hanya oleh admin.
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.artikels.create') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    <span class="h-4 w-4 flex items-center justify-center text-xs font-bold">+</span>
                    Tulis Artikel Baru
                </a>
                @if($latestArtikel ?? null)
                <a href="{{ route('admin.artikels.show', $latestArtikel) }}"
                    class="hidden sm:inline-flex items-center gap-2 rounded-md border bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                    👁️ Preview Terakhir
                </a>
                @endif
            </div>
        </div>
    </div>
</header>

<main id="buat-artikel" class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 border-b px-4 py-3">
                    <h3 class="text-sm font-medium">Total Artikel</h3>
                    <span class="h-4 w-4 text-muted-foreground text-xs">📄</span>
                </div>
                <div class="px-4 py-3">
                    <div class="text-2xl font-bold">{{ $totalArtikel ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Artikel yang Anda kelola</p>
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 border-b px-4 py-3">
                    <h3 class="text-sm font-medium">Dipublikasikan</h3>
                    <span class="h-4 w-4 text-muted-foreground text-xs">✅</span>
                </div>
                <div class="px-4 py-3">
                    <div class="text-2xl font-bold">{{ $publishedArtikel ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Dapat diakses publik</p>
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 border-b px-4 py-3">
                    <h3 class="text-sm font-medium">Draft</h3>
                    <span class="h-4 w-4 text-muted-foreground text-xs">✏️</span>
                </div>
                <div class="px-4 py-3">
                    <div class="text-2xl font-bold">{{ $draftArtikel ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Dalam pengerjaan</p>
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 border-b px-4 py-3">
                    <h3 class="text-sm font-medium">Perlu Review</h3>
                    <span class="h-4 w-4 text-muted-foreground text-xs">👁️</span>
                </div>
                <div class="px-4 py-3">
                    <div class="text-2xl font-bold">{{ max(($totalArtikel ?? 0) - ($publishedArtikel ?? 0) - ($draftArtikel ?? 0), 0) }}</div>
                    <p class="text-xs text-muted-foreground">Menunggu persetujuan</p>
                </div>
            </div>
        </div>

        <div id="daftar-artikel" class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="px-4 py-3 border-b">
                <h3 class="text-base font-semibold">Filter Artikel</h3>
                <p class="text-sm text-muted-foreground">
                    Saring artikel berdasarkan kriteria tertentu.
                </p>
            </div>
            <form method="GET" action="{{ route('admin.pengurus.artikel') }}" class="flex flex-col md:flex-row gap-4 px-4 py-4">
                <div class="relative flex-1">
                    <span class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground text-xs">🔍</span>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul artikel..."
                        class="w-full rounded-lg border bg-background pl-8 pr-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" />
                </div>

                <div>
                    <select name="status"
                        class="w-[180px] rounded-md border bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <option value="">Semua Status</option>
                        @foreach (['published' => 'Dipublikasikan', 'draft' => 'Draft'] as $value => $label)
                            <option value="{{ $value }}" @selected(($statusFilter ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-md border bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                    Filter
                </button>
            </form>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="px-4 py-3 border-b flex items-center gap-2">
                <span class="h-5 w-5 text-muted-foreground text-xs">📄</span>
                <div>
                    <h3 class="text-base font-semibold">Daftar Artikel</h3>
                    <p class="text-sm text-muted-foreground">
                        Menampilkan {{ $artikels->count() }} dari {{ $artikels->total() }} artikel
                    </p>
                </div>
            </div>

            <div class="px-4 py-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b">
                        <tr class="text-left text-xs text-muted-foreground">
                            <th class="py-2 pr-4">Judul Artikel</th>
                            <th class="py-2 px-4 hidden md:table-cell">Penulis</th>
                            <th class="py-2 px-4 hidden md:table-cell">Kategori</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4 hidden lg:table-cell">Tgl Dimodifikasi</th>
                            <th class="py-2 px-4 text-right">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artikels as $artikel)
                            @php
                                $statusLabel = [
                                    'published' => ['bg' => 'bg-green-500 text-white', 'text' => 'Dipublikasikan'],
                                    'draft' => ['bg' => 'bg-slate-100 text-slate-700', 'text' => 'Draft'],
                                ][$artikel->status] ?? ['bg' => 'bg-amber-100 text-amber-700', 'text' => $artikel->status];
                            @endphp
                            <tr class="border-b last:border-0">
                                <td class="py-3 pr-4 align-top">
                                    <div class="font-medium">
                                        {{ $artikel->title }}
                                    </div>
                                    <div class="text-sm text-muted-foreground md:hidden">
                                        {{ $artikel->user->name ?? 'Pengurus' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell align-top">
                                    {{ $artikel->user->name ?? 'Pengurus' }}
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell align-top">
                                    -
                                </td>
                                <td class="py-3 px-4 align-top">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusLabel['bg'] }}">
                                        {{ $statusLabel['text'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 hidden lg:table-cell align-top">
                                    {{ optional($artikel->updated_at)->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-4 text-right align-top">
                                    <div class="flex justify-end gap-1 text-xs">
                                        <a href="{{ route('artikel.show', $artikel) }}"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                            👁️ Preview
                                        </a>
                                        <a href="{{ route('admin.artikels.edit', $artikel) }}"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                            ✏️ Edit
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
                                    Belum ada artikel. Mulai dengan menulis artikel baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between mt-4 px-6 pb-4 pt-0">
                <div class="text-xs text-muted-foreground">
                    Menampilkan <strong>{{ $artikels->firstItem() ?? 0 }}-{{ $artikels->lastItem() ?? 0 }}</strong> dari <strong>{{ $artikels->total() }}</strong> artikel
                </div>
                <div class="flex items-center gap-2 text-xs">
                    {{ $artikels->links() }}
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col gap-2 border-b px-4 py-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-base font-semibold">Panduan Wewenang Pengurus</h3>
                <span class="text-xs rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-700">Artikel</span>
            </div>
            <div class="grid gap-4 p-4 md:grid-cols-3">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Bisa dilakukan</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Lihat draft & terbit</li>
                        <li>• Tulis dan edit artikel</li>
                        <li>• Preview sebelum publish</li>
                        <li>• Minta review admin sebelum tayang</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Dibatasi</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Tidak bisa hapus artikel</li>
                        <li>• Tidak ubah user/peran atau donasi</li>
                        <li>• Tidak ubah konfigurasi situs</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Alur cepat</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Buat / ubah konten</li>
                        <li>• Preview</li>
                        <li>• (Opsional) kirim untuk review</li>
                        <li>• Publish tanpa hapus</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
