<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\Donasi;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\MasjidProfile;
use App\Models\PendaftaranKegiatan;
use App\Models\Perpustakaan;
use App\Models\PertanyaanUstadz;
use App\Models\TransaksiDonasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PertanyaanUstadz::truncate();
        TransaksiDonasi::truncate();
        PendaftaranKegiatan::truncate();
        Donasi::truncate();
        Kegiatan::truncate();
        Artikel::truncate();
        Galeri::truncate();
        Perpustakaan::truncate();
        MasjidProfile::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $password = Hash::make('password');

        $admin = User::updateOrCreate(
            ['email' => 'admin@masjid.test'],
            [
                'name' => 'Admin Masjid',
                'password' => $password,
                'role' => 'admin',
            ]
        );

        $pengurus = User::updateOrCreate(
            ['email' => 'pengurus@masjid.test'],
            [
                'name' => 'Pengurus Utama',
                'password' => $password,
                'role' => 'pengurus',
            ]
        );

        $ustadz = User::updateOrCreate(
            ['email' => 'ustadz@masjid.test'],
            [
                'name' => 'Ustadz Ahmad Najib',
                'password' => $password,
                'role' => 'ustadz',
            ]
        );

        $jamaahUsers = collect([
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@masjid.test'],
            ['name' => 'Siti Rahmawati', 'email' => 'siti@masjid.test'],
            ['name' => 'Muhammad Ridho', 'email' => 'ridho@masjid.test'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@masjid.test'],
        ])->map(function (array $data) use ($password) {
            return User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $password,
                    'role' => 'jamaah',
                ]
            );
        })->values();

        MasjidProfile::create([
            'nama' => 'Masjid Al Ala Gianyar',
            'alamat' => 'Jl. Masjid Raya No. 1, Gianyar, Bali',
            'sejarah' => 'Masjid Al Ala berdiri sejak tahun 1984 sebagai pusat pembinaan keislaman masyarakat Gianyar. Masjid ini menjadi saksi berkembangnya kegiatan dakwah dan sosial yang melibatkan seluruh lapisan jamaah.',
            'visi' => 'Menjadi masjid yang makmur, ramah jamaah, dan berkontribusi untuk kemajuan umat serta masyarakat sekitar.',
            'misi' => "1. Menyelenggarakan ibadah jamaah yang tertib dan khusyuk.\n2. Menguatkan dakwah bil hikmah melalui pengajian rutin.\n3. Membangun solidaritas sosial dan pemberdayaan ekonomi umat.\n4. Mengelola sarana prasarana masjid secara profesional.",
        ]);

        $artikelData = [
            [
                'title' => 'Merawat Kesucian Masjid Melalui Program Bersih-Bersih Pekanan',
                'content' => 'Program ini mengajak jamaah untuk menjaga kebersihan area ibadah dan fasilitas masjid sehingga suasana selalu nyaman bagi seluruh pengunjung.',
                'thumbnail' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=500&q=80',
                'status' => 'published',
            ],
            [
                'title' => 'Catatan Kajian Tafsir Surah Yasin Bersama Ustadz Ahmad Najib',
                'content' => 'Ringkasan kajian tafsir yang merangkum pesan utama Surah Yasin tentang keagungan Al-Qur\'an serta pentingnya dakwah yang penuh kasih sayang.',
                'thumbnail' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=500&q=80',
                'status' => 'published',
            ],
            [
                'title' => 'Gerakan Sedekah Subuh untuk Santunan Anak Yatim',
                'content' => 'Pengurus masjid mengajak jamaah untuk bersedekah setiap selesai shalat subuh guna membantu biaya pendidikan anak-anak yatim di sekitar masjid.',
                'thumbnail' => 'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?auto=format&fit=crop&w=500&q=80',
                'status' => 'draft',
            ],
        ];

        foreach ($artikelData as $index => $artikel) {
            Artikel::create([
                'user_id' => $pengurus->id,
                'title' => $artikel['title'],
                'slug' => Str::slug($artikel['title']) . '-' . ($index + 1),
                'content' => $artikel['content'],
                'thumbnail' => $artikel['thumbnail'],
                'status' => $artikel['status'],
            ]);
        }

        $kegiatanData = [
            [
                'nama' => 'Kajian Subuh Tematik',
                'deskripsi' => 'Kajian tafsir Juz Amma yang dikemas ringan setiap Ahad subuh.',
                'mulai' => '2024-12-01 05:00:00',
                'selesai' => '2024-12-01 06:30:00',
                'lokasi' => 'Ruang Utama Masjid',
                'poster' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'nama' => 'Pelatihan Kader Remaja Masjid',
                'deskripsi' => 'Pembekalan dakwah dan kepemimpinan untuk remaja masjid.',
                'mulai' => '2024-12-08 09:00:00',
                'selesai' => '2024-12-08 12:00:00',
                'lokasi' => 'Aula Serbaguna',
                'poster' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=500&q=80',
            ],
            [
                'nama' => 'Pengajian Muslimah Rutin',
                'deskripsi' => 'Pengajian tematik khusus muslimah setiap hari Rabu.',
                'mulai' => '2024-12-04 14:00:00',
                'selesai' => '2024-12-04 16:00:00',
                'lokasi' => 'Ruang Muslimah',
                'poster' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=500&q=80',
            ],
        ];

        $kegiatanRecords = collect();
        foreach ($kegiatanData as $data) {
            $kegiatanRecords->push(
                Kegiatan::create([
                    'nama_kegiatan' => $data['nama'],
                    'deskripsi' => $data['deskripsi'],
                    'tanggal_mulai' => Carbon::parse($data['mulai']),
                    'tanggal_selesai' => Carbon::parse($data['selesai']),
                    'lokasi' => $data['lokasi'],
                    'poster' => $data['poster'],
                ])
            );
        }

        foreach ($kegiatanRecords as $kegiatan) {
            foreach ($jamaahUsers as $index => $jamaahUser) {
                PendaftaranKegiatan::create([
                    'user_id' => $jamaahUser->id,
                    'kegiatan_id' => $kegiatan->id,
                    'status' => $index % 2 === 0 ? 'diterima' : 'menunggu',
                ]);
            }
        }

        $donasiData = [
            [
                'judul' => 'Renovasi Wudhu dan Toilet',
                'deskripsi' => 'Perbaikan area wudhu agar lebih nyaman dan higienis.',
                'target' => 75000000,
                'selesai' => '2025-01-30',
            ],
            [
                'judul' => 'Beasiswa Santri Tahfidz',
                'deskripsi' => 'Mendukung biaya pendidikan santri penghafal Al-Qur\'an.',
                'target' => 50000000,
                'selesai' => '2025-03-15',
            ],
            [
                'judul' => 'Program Santunan Keluarga Dhuafa',
                'deskripsi' => 'Menyalurkan paket sembako dan modal usaha kecil.',
                'target' => 60000000,
                'selesai' => '2025-02-20',
            ],
        ];

        $donasiRecords = collect();
        foreach ($donasiData as $data) {
            $donasiRecords->push(
                Donasi::create([
                    'judul' => $data['judul'],
                    'deskripsi' => $data['deskripsi'],
                    'target_dana' => $data['target'],
                    'dana_terkumpul' => 0,
                    'tanggal_selesai' => Carbon::parse($data['selesai']),
                ])
            );
        }

        foreach ($donasiRecords as $donasi) {
            foreach ($jamaahUsers as $index => $jamaahUser) {
                TransaksiDonasi::create([
                    'user_id' => $jamaahUser->id,
                    'donasi_id' => $donasi->id,
                    'jumlah' => $index % 2 === 0 ? 500000 : 250000,
                    'status_pembayaran' => 'berhasil',
                ]);
            }

            TransaksiDonasi::create([
                'user_id' => $pengurus->id,
                'donasi_id' => $donasi->id,
                'jumlah' => 150000,
                'status_pembayaran' => 'pending',
            ]);

            $donasi->syncDanaTerkumpul();
        }

        $galeriData = [
            [
                'judul' => 'Suasana Shalat Subuh',
                'deskripsi' => 'Dokumentasi jamaah saat shalat subuh berjamaah.',
                'url_file' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'image',
            ],
            [
                'judul' => 'Kegiatan Remaja Masjid',
                'deskripsi' => 'Pelatihan kepemimpinan untuk remaja masjid.',
                'url_file' => 'https://images.unsplash.com/photo-1488198941622-cbd3900c8ead?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'image',
            ],
            [
                'judul' => 'Video Dokumentasi Buka Puasa',
                'deskripsi' => 'Momen kebersamaan saat berbuka puasa Ramadhan.',
                'url_file' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'video',
            ],
            [
                'judul' => 'Galeri Kaligrafi Santri',
                'deskripsi' => 'Hasil karya kaligrafi dari santri TPQ.',
                'url_file' => 'https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'image',
            ],
            [
                'judul' => 'Pengajian Akbar',
                'deskripsi' => 'Kajian akbar bersama ulama nasional.',
                'url_file' => 'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'image',
            ],
            [
                'judul' => 'Video Renovasi Masjid',
                'deskripsi' => 'Progress renovasi menara dan kubah masjid.',
                'url_file' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=800&q=80',
                'tipe' => 'video',
            ],
        ];

        foreach ($galeriData as $item) {
            Galeri::create($item);
        }

        $perpustakaanData = [
            [
                'judul' => 'Fiqih Sunnah',
                'penulis' => 'Sayyid Sabiq',
                'kategori' => 'Fiqih',
                'isbn' => '978-602-8499-23-4',
                'deskripsi' => 'Pembahasan fiqih ibadah dengan gaya bahasa ringan.',
                'file_ebook' => 'https://example.com/ebooks/fiqih-sunnah.pdf',
                'cover' => 'https://images.unsplash.com/photo-1528208079124-a23807da15da?auto=format&fit=crop&w=400&q=80',
                'stok_total' => 5,
                'stok_tersedia' => 3,
            ],
            [
                'judul' => 'Sirah Nabawiyah',
                'penulis' => 'Shafiyyurrahman Al-Mubarakfuri',
                'kategori' => 'Sirah',
                'isbn' => '978-979-1254-16-0',
                'deskripsi' => 'Biografi Rasulullah SAW yang komprehensif.',
                'file_ebook' => 'https://example.com/ebooks/sirah-nabawiyah.pdf',
                'cover' => 'https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?auto=format&fit=crop&w=400&q=80',
                'stok_total' => 4,
                'stok_tersedia' => 4,
            ],
            [
                'judul' => 'Tafsir Al-Muyassar',
                'penulis' => 'Kementerian Agama Saudi',
                'kategori' => 'Tafsir',
                'isbn' => '978-602-425-398-4',
                'deskripsi' => 'Tafsir singkat dan mudah dipahami untuk seluruh muslim.',
                'file_ebook' => 'https://example.com/ebooks/tafsir-al-muyassar.pdf',
                'cover' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=400&q=80',
                'stok_total' => 6,
                'stok_tersedia' => 5,
            ],
            [
                'judul' => 'Aqidah Wasithiyah',
                'penulis' => 'Ibnu Taimiyah',
                'kategori' => 'Aqidah',
                'isbn' => '978-602-8545-55-8',
                'deskripsi' => 'Pembahasan aqidah ahlussunnah wal jamaah secara ringkas.',
                'file_ebook' => 'https://example.com/ebooks/aqidah-wasithiyah.pdf',
                'cover' => 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=400&q=80',
                'stok_total' => 3,
                'stok_tersedia' => 1,
            ],
        ];

        foreach ($perpustakaanData as $item) {
            Perpustakaan::create($item);
        }

        $pertanyaanData = [
            [
                'user' => $jamaahUsers[0],
                'ustadz' => $ustadz,
                'kategori' => 'fiqih',
                'pertanyaan' => 'Bagaimana hukum penggunaan dana infak untuk renovasi kamar mandi masjid?',
                'jawaban' => 'Dana infak boleh digunakan untuk renovasi fasilitas penunjang ibadah selama disepakati pengurus dan jamaah.',
                'status' => 'dijawab',
            ],
            [
                'user' => $jamaahUsers[1],
                'ustadz' => $ustadz,
                'kategori' => 'keluarga',
                'pertanyaan' => 'Apakah boleh zakat fitrah diantar ke rumah para lansia yang tidak mampu hadir ke masjid?',
                'jawaban' => 'Boleh. Justru itu lebih utama karena memudahkan mustahik untuk menerima haknya.',
                'status' => 'dijawab',
            ],
            [
                'user' => $jamaahUsers[2],
                'ustadz' => $ustadz,
                'kategori' => 'ibadah',
                'pertanyaan' => 'Apa yang sebaiknya disiapkan ketika memimpin shalat jenazah, terutama terkait bacaan doa?',
                'jawaban' => null,
                'status' => 'menunggu',
            ],
            [
                'user' => $jamaahUsers[2],
                'ustadz' => null,
                'kategori' => 'muamalah',
                'pertanyaan' => 'Apa saja syarat sah akad arisan online agar tidak terjerumus riba?',
                'jawaban' => null,
                'status' => 'menunggu',
            ],
            [
                'user' => $jamaahUsers[3],
                'ustadz' => null,
                'kategori' => 'aqidah',
                'pertanyaan' => 'Bagaimana cara menumbuhkan semangat hijrah dalam keluarga?',
                'jawaban' => null,
                'status' => 'menunggu',
            ],
        ];

        foreach ($pertanyaanData as $data) {
            PertanyaanUstadz::create([
                'user_id' => $data['user']->id,
                'ustadz_id' => $data['ustadz'] ? $data['ustadz']->id : null,
                'kategori' => $data['kategori'],
                'pertanyaan' => $data['pertanyaan'],
                'jawaban' => $data['jawaban'],
                'status' => $data['status'],
            ]);
        }

        // ✅ Panggil seeder Program Kerja DI SINI (bukan di dalam create())
        $this->call(\Database\Seeders\WorkProgramSeeder::class);

        // ✅ Panggil seeder tambahan DI SINI
$this->call([
    \Database\Seeders\WorkProgramSeeder::class,
    \Database\Seeders\ManagementMubesVI2022Seeder::class,
]);
$this->call(AccountSeeder::class);

    }
    
}
