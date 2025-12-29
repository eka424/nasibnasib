# Web Masjid Starter (Laravel 11 + Breeze)

Starter kit untuk platform manajemen konten masjid (artikel, kegiatan, donasi, galeri, perpustakaan, dan tanya ustadz) dengan Breeze (Blade + Alpine) tanpa Livewire. Mendukung peran `admin`, `pengurus`, `ustadz`, dan `jamaah` lengkap dengan middleware, policy, seeding akun demo, serta feature & unit test dasar.

## Stack & Fitur

- Laravel 11 + PHP 8.3, Breeze Blade + Alpine, Tailwind.
- MySQL sebagai database utama, storage publik (`php artisan storage:link`) untuk thumbnail/poster/file.
- Middleware `role:*`, policy per modul, gate helper role.
- Modul:
  - **Users**: CRUD + proteksi admin terakhir.
  - **Profil Masjid**: single row editable.
  - **Artikel, Kegiatan, Donasi, Transaksi Donasi, Galeri, Perpustakaan**: CRUD komplet dengan Form Request & upload file.
  - **Pendaftaran Kegiatan**: unik per user/kegiatan, status filter.
  - **Moderasi Tanya Ustadz**: assign ke ustadz + hapus.
  - **Ustadz Portal**: daftar tugas & riwayat jawaban.
  - **Frontend publik**: Artikel/Kegiatan/Donasi/Galeri/Perpustakaan/Tanya Ustadz + aksi jamaah (daftar kegiatan, donasi, pertanyaan).
- Seeder akun demo:
  - `admin@example.com` (admin)
  - `pengurus@example.com` (pengurus)
  - `ustadz@example.com` (ustadz)
  - `jamaah@example.com` (jamaah)
  - Password seluruhnya: `password`

## Setup Lokal

Pastikan MySQL tersedia dan update variabel lingkungan (`.env`) sesuai kredensial Anda (gunakan `DB_DATABASE=web_masjid`, `DB_USERNAME`, `DB_PASSWORD`). Kemudian jalankan:

```bash
composer create-project laravel/laravel web-masjid
cd web-masjid
php artisan breeze:install blade
npm install && npm run build
php artisan migrate --seed
php artisan storage:link
```

> Repo ini sudah berada pada langkah akhir di atas. Untuk menjalankan ulang setelah clone:

```bash
cp .env.example .env          # sesuaikan kredensial MySQL
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Frontend publik tersedia di `/`, backoffice di `/admin`, portal ustadz di `/ustadz`. Pastikan akun memiliki email terverifikasi agar dapat login (Seeder sudah mengatur `email_verified_at`).

## Testing

Unit & Feature test tersedia untuk:

- akses kontrol per role (`tests/Feature/AccessControlTest.php`);
- proteksi admin terakhir (`tests/Feature/AdminProtectionTest.php`);
- pendaftaran kegiatan unik;
- agregasi donasi & aksi recalc;
- alur moderasi tanya ustadz;
- relasi model (User→Artikel, Donasi→Transaksi, dst.).

Jalankan:

```bash
php artisan test
```

> Dibutuhkan database uji (misalnya MySQL terpisah atau SQLite dengan ekstensi `pdo_sqlite` aktif). Jika driver tidak tersedia, konfigurasi dulu sebelum menjalankan test.

## Struktur Navigasi

- **Sidebar per role**:
  - Admin: Dashboard, Users, Profil Masjid, Artikel, Kegiatan, Pendaftar, Donasi, Transaksi Donasi, Galeri, Perpustakaan, Moderasi Tanya Ustadz.
  - Pengurus: Semua modul operasional (tanpa Users).
  - Ustadz: Pertanyaan ditugaskan, riwayat jawaban, profil.
  - Jamaah: Beranda publik, daftar/riwayat kegiatan & donasi, galeri, perpustakaan, tanya ustadz, profil.
- **Navbar** berisi identitas aplikasi + aksi login/logout, sedangkan `x-flash` menampilkan alert sukses/error.

## Catatan Tambahan

- Semua upload disimpan di disk `public` (jalankan `php artisan storage:link`).
- Role guard menggunakan middleware `role:*` dan pengecekan policy pada setiap resource controller.
- Menu publik memanfaatkan slug artikel (`/artikel/{slug}`) dan route model binding untuk detail entitas lain.
- Business rule donasi: `Donasi::syncDanaTerkumpul()` dipanggil saat update status transaksi atau trigger manual *Recalc*.
"# masjidagungalala29des" 
"# nasibnasib" 
