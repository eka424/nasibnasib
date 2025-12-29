<header id="top-perpustakaan" class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
    <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Perpustakaan</h1>
            <p class="text-gray-600">Pengurus bisa lihat katalog, tambah koleksi, dan edit info/status (tanpa hapus).</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.perpustakaans.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                <span class="text-base">➕</span>
                Tambah Buku
            </a>
            <span class="hidden text-xs text-muted-foreground sm:inline-flex sm:items-center sm:gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-400"></span> Penghapusan koleksi dibatasi admin.
            </span>
        </div>
    </div>
</header>

<main id="tambah-buku" class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
    <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <h3 class="text-sm font-medium">Total Judul Buku</h3>
                    <span class="text-lg text-muted-foreground">📚</span>
                </div>
                <div class="p-4 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['titles'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">+{{ $stats['new_titles'] ?? 0 }} judul baru bulan ini</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <h3 class="text-sm font-medium">Total Eksemplar</h3>
                    <span class="text-lg text-muted-foreground">📖</span>
                </div>
                <div class="p-4 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['copies'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Total semua kopi</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <h3 class="text-sm font-medium">Buku Dipinjam</h3>
                    <span class="text-lg text-muted-foreground">📦</span>
                </div>
                <div class="p-4 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['borrowed'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Sedang dipinjam jamaah</p>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                    <h3 class="text-sm font-medium">Tersedia</h3>
                    <span class="text-lg text-muted-foreground">👥</span>
                </div>
                <div class="p-4 pt-0">
                    <div class="text-2xl font-bold">{{ $stats['available'] ?? 0 }}</div>
                    <p class="text-xs text-muted-foreground">Eksemplar siap dipinjam</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div class="p-4 pb-2">
                <h3 class="text-base font-semibold leading-none tracking-tight">Filter Buku</h3>
                <p class="text-sm text-muted-foreground">Saring koleksi buku perpustakaan.</p>
            </div>
            <form method="GET" action="{{ route('admin.pengurus.perpustakaan') }}" class="flex flex-col gap-4 p-4 pt-2 md:flex-row">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute left-2.5 top-2.5 text-sm text-muted-foreground">🔍</span>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari judul, penulis, atau ISBN..."
                        class="w-full rounded-md border border-input bg-background px-3 py-2 pl-8 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" />
                </div>

                <div>
                    <select name="status"
                        class="w-[180px] rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        <option value="">Semua Status</option>
                        <option value="available" @selected(($filters['status'] ?? '') === 'available')>Tersedia</option>
                        <option value="borrowed" @selected(($filters['status'] ?? '') === 'borrowed')>Dipinjam</option>
                    </select>
                </div>

                <div>
                    <select name="kategori"
                        class="w-[180px] rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoriOptions as $kategori)
                            <option value="{{ $kategori }}" @selected(($filters['kategori'] ?? '') === $kategori)>{{ ucfirst($kategori) }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    Terapkan
                </button>
            </form>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div class="p-4 pb-2">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📚</span>
                    <h3 class="text-base font-semibold leading-none tracking-tight">Daftar Koleksi Buku</h3>
                </div>
                <p class="mt-1 text-sm text-muted-foreground">
                    Menampilkan {{ $perpustakaans->count() }} dari {{ $perpustakaans->total() }} judul buku
                </p>
            </div>

            <div class="p-4 pt-2 overflow-x-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="bg-muted/50">
                        <tr class="border-b">
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Judul Buku</th>
                            <th class="hidden h-10 px-4 text-left align-middle font-medium text-muted-foreground md:table-cell">
                                Penulis
                            </th>
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                            <th class="hidden h-10 px-4 text-left align-middle font-medium text-muted-foreground lg:table-cell">
                                Eksemplar
                            </th>
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="[&>tr:nth-child(even)]:bg-muted/30">
                        @forelse ($perpustakaans as $book)
                            @php
                                $available = (int) ($book->stok_tersedia ?? 0);
                                $total = (int) ($book->stok_total ?? 0);
                                $borrowed = max($total - $available, 0);
                                $statusAvailable = $available > 0;
                                $statusClass = $statusAvailable
                                    ? 'bg-green-500 text-white'
                                    : 'bg-gray-200 text-gray-800';
                                $statusText = $statusAvailable ? 'Tersedia' : 'Dipinjam';
                            @endphp
                            <tr class="border-b">
                                <td class="p-4 align-middle">
                                    <div class="font-medium">{{ $book->judul }}</div>
                                    <div class="text-sm text-muted-foreground md:hidden">{{ $book->kategori ?: '-' }}</div>
                                </td>
                                <td class="hidden p-4 align-middle md:table-cell">{{ $book->penulis ?: '-' }}</td>
                                <td class="p-4 align-middle">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="hidden p-4 align-middle lg:table-cell">
                                    {{ $available }} / {{ $total }}
                                    <span class="text-xs text-muted-foreground">({{ $borrowed }} dipinjam)</span>
                                </td>
                                <td class="p-4 align-middle">
                                    <div class="flex flex-wrap justify-end gap-1 text-xs lg:justify-start">
                                        <a href="{{ route('perpustakaan.show', $book) }}"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                            👁️ Preview
                                        </a>
                                        <a href="{{ route('admin.perpustakaans.edit', $book) }}"
                                            class="inline-flex items-center gap-1 rounded-md border px-2 py-1 hover:bg-accent">
                                            ✏️ Edit Info
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
                                <td colspan="5" class="p-4 text-center text-sm text-muted-foreground">
                                    Belum ada koleksi. Tambahkan buku pertama Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between p-6 pt-0">
                <div class="text-xs text-muted-foreground">
                    Menampilkan <strong>{{ $perpustakaans->firstItem() ?? 0 }}-{{ $perpustakaans->lastItem() ?? 0 }}</strong> dari <strong>{{ $perpustakaans->total() }}</strong> judul
                </div>
                <div class="inline-flex items-center gap-1 text-sm font-medium">
                    {{ $perpustakaans->links() }}
                </div>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col gap-2 border-b px-4 py-3 md:flex-row md:items-center md:justify-between">
                <h3 class="text-base font-semibold">Panduan Wewenang Pengurus</h3>
                <span class="text-xs rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">Perpustakaan</span>
            </div>
            <div class="grid gap-4 p-4 md:grid-cols-3">
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Bisa dilakukan</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Lihat katalog koleksi</li>
                        <li>• Tambah buku/koleksi baru</li>
                        <li>• Edit informasi & status ketersediaan</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Dibatasi</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Tidak bisa hapus data koleksi</li>
                        <li>• Tidak kelola user/peran atau donasi</li>
                        <li>• Tidak ubah konfigurasi situs</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Alur cepat</p>
                    <ul class="space-y-1 text-sm text-muted-foreground">
                        <li>• Tambah/ubah data buku</li>
                        <li>• Perbarui status & deskripsi</li>
                        <li>• Publikasikan perubahan (tanpa hapus)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
