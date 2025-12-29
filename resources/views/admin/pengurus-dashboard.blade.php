@extends('layouts.app')

@section('content')
    <div class="-m-6">
        <div class="flex min-h-screen w-full flex-col bg-muted/40">
            <header class="sticky top-0 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6">
                <nav
                    class="hidden flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm lg:gap-6">
                    <a href="{{ route('admin.pengurus.dashboard') }}" class="flex items-center gap-2 text-lg font-semibold md:text-base">
                        <span
                            class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-primary text-primary-foreground text-xs font-bold">
                            PM
                        </span>
                        <span class="sr-only">Pengurus Dashboard</span>
                    </a>
                    <a href="{{ route('admin.pengurus.dashboard') }}" class="text-foreground transition-colors hover:text-foreground">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pengurus.artikel') }}" class="text-muted-foreground transition-colors hover:text-foreground">
                        Artikel
                    </a>
                    <a href="{{ route('admin.pengurus.kegiatan') }}" class="text-muted-foreground transition-colors hover:text-foreground">
                        Kegiatan
                    </a>
                    <a href="{{ route('admin.pengurus.perpustakaan') }}" class="text-muted-foreground transition-colors hover:text-foreground">
                        Perpustakaan
                    </a>
                </nav>

                <button type="button"
                    class="inline-flex items-center justify-center rounded-md border bg-background px-2 py-2 text-sm md:hidden">
                    <span class="sr-only">Toggle navigation menu</span>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="flex w-full items-center gap-4 md:ml-auto md:gap-2 lg:gap-4">
                    <h1 class="flex-1 text-lg font-semibold">
                        Dashboard Pengurus
                    </h1>

                    <button type="button"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-secondary text-secondary-foreground">
                        <span class="sr-only">Toggle user menu</span>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </header>

            <main class="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
                <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                            <h3 class="text-sm font-medium">
                                Kegiatan Akan Datang
                            </h3>
                            <div class="text-muted-foreground">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 5h16v14H4z" stroke="currentColor" stroke-width="2" />
                                    <path d="M4 9h16" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <div class="text-2xl font-bold">5</div>
                            <p class="text-xs text-muted-foreground">
                                Dalam 7 hari ke depan
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                            <h3 class="text-sm font-medium">
                                Buku Dipinjam
                            </h3>
                            <div class="text-muted-foreground">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M6 4h11v16H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" stroke="currentColor"
                                        stroke-width="2" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <div class="text-2xl font-bold">85</div>
                            <p class="text-xs text-muted-foreground">
                                2 buku melewati batas waktu
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                            <h3 class="text-sm font-medium">
                                Artikel Perlu Review
                            </h3>
                            <div class="text-muted-foreground">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 4h9l5 5v11H5z" stroke="currentColor" stroke-width="2" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <div class="text-2xl font-bold">3</div>
                            <p class="text-xs text-muted-foreground">
                                Menunggu untuk dipublikasi
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-4 pb-2">
                            <h3 class="text-sm font-medium">
                                Pertanyaan Masuk
                            </h3>
                            <div class="text-muted-foreground">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M9 9a3 3 0 0 1 6 0c0 2-3 2-3 4"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                    <circle cx="12" cy="17" r="1" fill="currentColor" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-4 pt-0">
                            <div class="text-2xl font-bold">+5</div>
                            <p class="text-xs text-muted-foreground">
                                Menunggu untuk dijawab
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col gap-2 border-b px-4 py-3 md:flex-row md:items-center md:justify-between">
                        <h2 class="text-base font-semibold">Batasan Akses & Alur Kerja Pengurus</h2>
                        <span class="text-xs rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-700">Role: Pengurus</span>
                    </div>
                    <div class="grid gap-4 p-4 md:grid-cols-3">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Tidak bisa</p>
                            <ul class="space-y-1 text-sm text-muted-foreground">
                                <li>• Manajemen pengguna/peran</li>
                                <li>• Ubah pengaturan sistem/profil masjid</li>
                                <li>• Akses/manipulasi donasi & laporan keuangan</li>
                                <li>• Hapus data sensitif (artikel/kegiatan/buku)</li>
                                <li>• Menjawab "Tanya Ustadz" (hanya ustadz)</li>
                            </ul>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Alur cepat</p>
                            <ul class="space-y-1 text-sm text-muted-foreground">
                                <li>• Artikel: Buat/Ubah → Preview → (opsional) minta review → Publish</li>
                                <li>• Kegiatan: Buat/Ubah jadwal → Publish → Pantau pendaftar → Kelola pelaksanaan</li>
                                <li>• Perpustakaan: Tambah/Ubah data & status → Simpan (tanpa hapus koleksi)</li>
                            </ul>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">Butuh bantuan admin?</p>
                            <ul class="space-y-1 text-sm text-muted-foreground">
                                <li>• Penghapusan konten atau koleksi</li>
                                <li>• Perubahan konfigurasi situs & tema</li>
                                <li>• Penanganan donasi/keuangan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:gap-8 lg:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm xl:col-span-2">
                        <div class="flex flex-row items-center p-6 pb-4">
                            <div class="grid gap-2">
                                <h2 class="text-lg font-semibold">
                                    Tugas Saya
                                </h2>
                                <p class="text-sm text-muted-foreground">
                                    Item yang memerlukan tindakan segera dari Anda.
                                </p>
                            </div>
                            <a href="#"
                                class="ml-auto inline-flex items-center gap-1 rounded-md bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:opacity-90">
                                Lihat Semua
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 17L17 7M7 7h10v10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                        <div class="p-6 pt-0">
                            <div class="w-full overflow-x-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="border-b">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <th class="h-10 px-2 text-left align-middle font-medium text-muted-foreground">
                                                Tugas
                                            </th>
                                            <th
                                                class="hidden h-10 px-2 text-left align-middle font-medium text-muted-foreground xl:table-cell">
                                                Kategori
                                            </th>
                                            <th
                                                class="hidden h-10 px-2 text-left align-middle font-medium text-muted-foreground xl:table-cell">
                                                Status
                                            </th>
                                            <th class="h-10 px-2 text-right align-middle font-medium text-muted-foreground">
                                                Tenggat
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&>tr:last-child]:border-0">
                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-2 align-middle">
                                                <div class="font-medium">
                                                    Review Artikel &quot;Fiqih Puasa&quot;
                                                </div>
                                                <div class="hidden text-sm text-muted-foreground md:inline">
                                                    Oleh: Ustadz Fulan
                                                </div>
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                Artikel
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-secondary px-2 py-0.5 text-[10px] font-semibold text-secondary-foreground">
                                                    Perlu Review
                                                </span>
                                            </td>
                                            <td class="p-2 text-right align-middle">
                                                25 Nov 2025
                                            </td>
                                        </tr>

                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-2 align-middle">
                                                <div class="font-medium">
                                                    Jawab Pertanyaan &quot;Hukum Waris&quot;
                                                </div>
                                                <div class="hidden text-sm text-muted-foreground md:inline">
                                                    Oleh: Jamaah Anonim
                                                </div>
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                Tanya Ustadz
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-destructive px-2 py-0.5 text-[10px] font-semibold text-destructive-foreground">
                                                    Segera
                                                </span>
                                            </td>
                                            <td class="p-2 text-right align-middle">
                                                24 Nov 2025
                                            </td>
                                        </tr>

                                        <tr class="border-b transition-colors hover:bg-muted/50">
                                            <td class="p-2 align-middle">
                                                <div class="font-medium">
                                                    Konfirmasi Peminjaman Buku
                                                </div>
                                                <div class="hidden text-sm text-muted-foreground md:inline">
                                                    Peminjam: Ahmad
                                                </div>
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                Perpustakaan
                                            </td>
                                            <td class="hidden p-2 align-middle xl:table-cell">
                                                <span
                                                    class="inline-flex items-center rounded-full bg-secondary px-2 py-0.5 text-[10px] font-semibold text-secondary-foreground">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="p-2 text-right align-middle">
                                                26 Nov 2025
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
                        <div class="p-6 pb-4">
                            <h2 class="text-lg font-semibold">
                                Aktivitas Terakhir
                            </h2>
                        </div>
                        <div class="grid gap-6 p-6 pt-0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="hidden h-9 w-9 items-center justify-center rounded-full bg-muted text-xs font-medium sm:flex">
                                    AD
                                </div>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        Anda
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Membuat kegiatan baru: &quot;Kajian Rutin Ba&apos;da Isya&quot;.
                                    </p>
                                </div>
                                <div class="ml-auto text-sm text-muted-foreground">
                                    1 jam lalu
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div
                                    class="hidden h-9 w-9 items-center justify-center rounded-full bg-muted text-xs font-medium sm:flex">
                                    UF
                                </div>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        Ustadz Fulan
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Submit artikel baru untuk direview.
                                    </p>
                                </div>
                                <div class="ml-auto text-sm text-muted-foreground">
                                    2 jam lalu
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div
                                    class="hidden h-9 w-9 items-center justify-center rounded-full bg-muted text-xs font-medium sm:flex">
                                    SA
                                </div>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        Siti Aminah
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Meminjam buku &quot;Fiqih Sunnah&quot;.
                                    </p>
                                </div>
                                <div class="ml-auto text-sm text-muted-foreground">
                                    5 jam lalu
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <section class="mt-8 w-full rounded-2xl border border-muted bg-muted/40">
                <div class="flex flex-col sm:gap-4 sm:py-4 w-full">
                    @include('admin.partials.pengurus-artikel-content')
                </div>
            </section>
        </div>
    </div>
@endsection
