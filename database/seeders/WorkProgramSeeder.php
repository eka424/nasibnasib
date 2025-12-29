<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkProgramSection;
use App\Models\WorkProgramPart;
use App\Models\WorkProgramItem;

class WorkProgramSeeder extends Seeder
{
    public function run(): void
    {
        // kalau sudah ada data, jangan isi ulang
        if (WorkProgramSection::count() > 0) return;

        $makeSection = function (int $urutan, string $nama, array $parts) {
            $sec = WorkProgramSection::create([
                'nama' => $nama,
                'urutan' => $urutan,
                'is_active' => true,
            ]);

            foreach ($parts as $pIndex => $p) {
                $part = WorkProgramPart::create([
                    'section_id' => $sec->id,
                    'judul' => $p['judul'],
                    'urutan' => $p['urutan'] ?? ($pIndex + 1),
                ]);

                foreach (($p['items'] ?? []) as $iIndex => $teks) {
                    $teks = trim((string)$teks);
                    if ($teks === '' || $teks === '-' || $teks === '...') continue;

                    WorkProgramItem::create([
                        'part_id' => $part->id,
                        'teks' => $teks,
                        'urutan' => $iIndex + 1,
                    ]);
                }
            }

            return $sec;
        };

        // 1) SEKSI DOKUMENTASI, PERPUSTAKAAN DAN PENERBITAN
        $makeSection(1, 'SEKSI DOKUMENTASI, PERPUSTAKAAN DAN PENERBITAN', [
            [
                'judul' => 'MENDATA DAN MENGARSIPKAN DOKUMEN PENTING MILIK MASJID',
                'items' => [
                    'Penyelesaian Sertifikat dan Wakaf Masjid Bagian Belakang',
                    'Digitalisasi Surat-Surat Penting Milik Masjid',
                ],
            ],
            [
                'judul' => 'MENDATA DAN MENGARSIPKAN BARANG INVENTARIS MASJID',
                'items' => [
                    'Merekap penerimaan barang untuk masjid',
                ],
            ],
            [
                'judul' => 'BANG-UPDATE DATABASE',
                'items' => [
                    'Pendataan dan Administrasi Proses Muallaf',
                ],
            ],
            [
                'judul' => 'MEMAKSIMALKAN FUNGSI DAN PERANAN PERPUSTAKAAN MASJID',
                'items' => [
                    "Mendata buku-buku perpustakaan dan menumbuhkan minat baca pada jama'ah",
                ],
            ],
            [
                'judul' => 'MENERBITKAN MEDIA INFORMASI DAN EDUKASI SECARA BERKALA',
                'items' => [
                    'Menerbitkan Pamflet Pengumuman di Setiap Acara Kegiatan Realisasi Program',
                    'Spanduk publikasi di space depan',
                    'Menerbitkan media online sebagai media informasi',
                ],
            ],
        ]);

        // 2) SEKSI HUMAS, INFORMASI DAN KOMUNIKASI
        $makeSection(2, 'SEKSI HUMAS, INFORMASI DAN KOMUNIKASI', [
            [
                'judul' => "PENGHUBUNG DAN MEDIA INFORMASI ANTARA PENGURUS DAN JAMA'AH",
                'items' => [
                    'Sosialisasi Program dan Pendataan',
                    "Transport Humas untuk 3 orang (Antar Undangan, Jadwal, Ta'jil dll)",
                ],
            ],
            [
                'judul' => 'PERAWATAN BARANG ELEKTRONIK',
                'items' => [
                    'Perawatan Print dan Computer',
                    'Perawatan wifi dan CCTV',
                    'Perawatan TV display',
                    'Perawatan Proyektor dan layar',
                    'Pembelian 2 monitor (untuk kantor sekretariat dan soundman)',
                    'Pembelian lensa dan memori card untuk kamera masjid',
                    'Pembelian stabilizer atau gimbal untuk kamera masjid',
                    'Pembelian HP untuk kehumasan dan live streaming',
                ],
            ],
            [
                'judul' => 'MENGELOLA APLIKASI MASJID BERBASIS ANDROID (ADMIN GWA)',
                'items' => [],
            ],
            [
                'judul' => 'MENDOKUMENTASIKAN KEGIATAN YANG MENJADI PROGRAM DKM',
                'items' => [],
            ],
            [
                'judul' => 'MENGELOLA DAKWAH VIRTUAL MELALUI SOSIAL MEDIA',
                'items' => [
                    'Podcast seputar masjid',
                    'Film Masjid Ramah Anak',
                ],
            ],
        ]);

        // 3) SEKSI PERIBADATAN
        $makeSection(3, 'SEKSI PERIBADATAN', [
            [
                'judul' => 'MENYELENGGARAKAN KEGIATAN PERIBADATAN DENGAN BAIK',
                'items' => [
                    "Pelaksanaan Sholat Maktubah secara berjama'ah",
                    'Pelaksanaan Sholat Tarawih bulan Ramadhan (Imam Tarawih)',
                    'Pelaksanaan Sholat Tarawih bulan Ramadhan (Operasional/Provos) @15 hari',
                    "Pelaksanaan Sholat 'Id (Bekerjasama dengan PHBI)",
                    'Pelaksanaan Sholat sunnah lainnya (Sholat Gerhana dll)',
                ],
            ],
            [
                'judul' => "MENY. KEGIATAN TADARUS/KHOTMIL QUR'AN, SHOLAWAT, DZIKIR DLL",
                'items' => [
                    "Pelaksanaan Tadarus Al-Qur'an rutin/khotmil",
                    "Pelaksanaan Tadarus Al-Qur'an bulan suci Ramadhan",
                    'Pelaksanaan kegiatan Istighotsah',
                    'Pelaksanaan kegiatan pembacaan Dzikir Rotibul Haddad',
                    "Pelaksanaan kegiatan pembacaan Sholawat (Maulid Diba'/Al Barzanji)",
                    "Pelaksanaan kegiatan kirim do'a arwah (Yasin Tahlil)",
                    'Pelaksanaan pembacaan surat-surat pilihan',
                    'Pelaksanaan kegiatan puasa bersama (Bulan Ramadhan) + Jadwal Ta’jil',
                    "Pelaksanaan i'tikaf (10 hari akhir Ramadhan)",
                ],
            ],
            [
                'judul' => "MENYELENGGARAKAN KEGIATAN IBADAH JUM'AT",
                'items' => [
                    "Menjadwal serta mengatur petugas-petugas Jum'at",
                    "Menghubungi petugas-petugas Jum'at",
                    "Memimpin kegiatan dzikir sebelum dan setelah Jum'at",
                    'Dana Cadangan untuk Khotib luar Gianyar',
                ],
            ],
        ]);

        // 4) SEKSI PENDIDIKAN, PELATIHAN DAN KADERISASI
        $makeSection(4, 'SEKSI PENDIDIKAN, PELATIHAN DAN KADERISASI', [
            [
                'judul' => 'MENYELENGGARAKAN KAJIAN KITAB DAN HALAQOH SECARA RUTIN TERJADWAL',
                'items' => [
                    "Kajian Rutin Ahad ba'da Shubuh (Mu'allim Rp. 50.000 - Konsumsi Rp. 300.000)",
                    'Kajian Keagamaan (MT. HBMI)',
                    "Kajian khusus untuk Remaja (Gemma Al-A'la)",
                    "Ngaji Syahril Rotibul Haddad (MT. Ibu Al-A'la)",
                    'Pengajian Umum Rutin Bulanan (Sunnatur Rasul dan Al-Khidmah)',
                ],
            ],
            [
                'judul' => "MENYELENGGARAKAN PEMBINAAN BACA TULIS AL-QUR'AN UTK SEMUA JENJANG USIA",
                'items' => [
                    "Madrasatuna (Pemb. Tilawah, Syahril dan Kaligrafi)",
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN KEG. PEMBELAJARAN PAI PADA SISWA SEKOLAH UMUM',
                'items' => [
                    'Melakukan Kegiatan Belajar Mengajar PAI',
                    'Melakukan Kegiatan Praktik Pendidikan Agama Islam',
                    'Melakukan Penilaian Semester dan mengirim hasil nilai ke masing-masing sekolah',
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN KEGIATAN-KEGIATAN PELATIHAN, PEMBINAAN, KURSUS DLL',
                'items' => [
                    'Seminar Mental Health bagi usia remaja sekolah',
                    'Pelatihan Baca Kitab Metode Al-Miftah',
                    'Seminar Zakat',
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN DAN MEMFASILITASI KEG. PENGKADERAN & PEMB. PEMUDA',
                'items' => [
                    "Penyelenggaraan Lomba Gemma Takbir 'Idul Adha (Kerjasama PHBI dan Gemma)",
                    'Penyelenggaraan Festival Kreasi Santri (Kerjasama dengan FKDT)',
                    'Pembinaan dan Pelatihan Seni Hadrah, Qasidah dan Sholawat',
                    'Penyelenggaraan Kreasi Siswa PAI',
                ],
            ],
        ]);

        // 5) SEKSI PEMBERDAYAAN PEREMPUAN DAN SOSIAL
        $makeSection(5, 'SEKSI PEMBERDAYAAN PEREMPUAN DAN SOSIAL', [
            [
                'judul' => 'MENYELENGGARAKAN KEGIATAN PEMBERDAYAAN PEREMPUAN',
                'items' => [
                    'Penyelenggaraan Lomba Gebyar Sholawat Se-Kab. Gianyar (Kerjasama FKMTI dan LPSBI)',
                    'Seminar Kesehatan Reproduksi (Kerjasama dengan Puskesmas)',
                    'Parenting tentang hak perempuan dan anak',
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN KEGIATAN DAKWAH SOSIAL KEAGAMAAN DAN KEMASYARAKATAN',
                'items' => [
                    "Kajian Fiqhun Nisa' / Kajian Fiqh Kedaruratan",
                ],
            ],
        ]);

        // 6) SEKSI KESEHATAN
        $makeSection(6, 'SEKSI KESEHATAN', [
            [
                'judul' => 'MENYELENGGARAKAN PELAYANAN KESEHATAN SECARA RUTIN DAN TERJADWAL',
                'items' => [
                    'Cek kesehatan gratis (terjadwal)',
                    'Perawatan Peralatan Kesehatan',
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN PEMBINAAN DAN PELAYANAN PENGOBATAN',
                'items' => [
                    'Pelayanan Thibbun Nabawi - Gurah dan Bekam (Terjadwal)',
                ],
            ],
            [
                'judul' => 'MENGADAKAN KERJASAMA DGN PIHAK TERKAIT DALAM MENJALANKAN PROG. KERJANYA',
                'items' => [
                    'Ruqyah Massal Kerjasama dengan JRA dan KBRA (terjadwal)',
                    'Khitan Massal bekerjasama dengan BUMN dan pihak terkait',
                ],
            ],
        ]);

        // 7) SEKSI PEREKONOMIAN
        $makeSection(7, 'SEKSI PEREKONOMIAN', [
            [
                'judul' => "MENYELENGGARAKAN KEG. PEMBERDAYAAN EKONOMI UNTUK KEMASLAHATAN JAMA'AH",
                'items' => [
                    'Koperasi untuk kebutuhan umat',
                ],
            ],
            [
                'judul' => 'MENYELENGGARAKAN USAHA UNTUK MENUNJANG PENDANAAN KEG. DAN PROG. KERJA',
                'items' => [],
            ],
        ]);

        // 8) SEKSI PEMBANGUNAN, PEMELIHARAAN DAN PERAWATAN
        $makeSection(8, 'SEKSI PEMBANGUNAN, PEMELIHARAAN DAN PERAWATAN', [
            [
                'judul' => 'PERENCANAAN PEMBANGUNAN, PEMELIHARAAN DAN PERAWATAN FISIK MASJID',
                'items' => [
                    'Rehabilitasi atap Ruangan Guru Tugas dan Toilet atas',
                    'Renovasi atap koperasi dan genset',
                    'Penggantian sikat halaman depan dengan batu alam',
                    'Membuat kaligrafi masjid (50.000.000)',
                    'Membongkar daun jendela lantai 3',
                    'Pengadaan Pos Keamanan (10.000.000)',
                ],
            ],
            [
                'judul' => 'PENGADAAN SARANA DAN PRASARANA',
                'items' => [
                    'Penambahan speaker sebelah pengumuman 1-2 dan mic wireless/kabel utk lantai 1',
                    'Pengadaan kipas angin ruang laktasi',
                    'Peremajaan sekat atau satir',
                    'Penyediaan air mineral kemasan, galon, kopi, gula, teh, gelas dll',
                    'Pengadaan bedug dan pengadaan kentongan baru (15.000.000)',
                    'Pengadaan loker barang berbayar (10.000.000)',
                ],
            ],
            [
                'judul' => 'MENJAGA, MERAWAT DAN MENGGANTI SARANA DAN PRASARANA YANG RUSAK',
                'items' => [
                    'Pengecatan daun kipas angin lantai 1 dan 2',
                    'Kegiatan kerja bakti menyambut bulan Ramadhan (500.000)',
                    'Perawatan saluran limbah setiap 6 bulan',
                    'Kerja bakti setiap 3 bulan (1x Rp. 400.000 per kegiatan)',
                    'Penanganan kerusakan ringan dan pemeliharaan sarana prasarana masjid',
                    'Perawatan dan peremajaan mesin sedot beud lantai 2',
                    'Perawatan atau perbaikan instalasi panel pembatas, COS, dan Genset',
                ],
            ],
        ]);

        // 9) SEKSI KEAMANAN, KETERTIBAN DAN KEBERSIHAN
        $makeSection(9, 'SEKSI KEAMANAN, KETERTIBAN DAN KEBERSIHAN', [
            [
                'judul' => 'MENJAGA DAN MENGELOLA KEAMANAN DAN KETERTIBAN DI LINGK. MASJID',
                'items' => [
                    'Perawatan Repeater masjid',
                    'Pengadaan lampu lain',
                    'Pengadaan jas hujan keamanan',
                ],
            ],
            [
                'judul' => 'MENJAGA DAN MENGELOLA KESUCIAN, KEBERSIHAN, KEINDAHAN DAN KENYAMANAN',
                'items' => [
                    'Pengadaan barang-barang sarana kebersihan selama 1 tahun',
                    'Laundry mukena masjid setiap bulan (1x Rp. 50.000 per kegiatan)',
                    'Pembelian hanger dan 2 jemuran kecil untuk sarung',
                ],
            ],
            [
                'judul' => 'DANA TAK TERDUGA',
                'items' => [],
            ],
        ]);

        // 10) KESEKRETARIATAN
        $makeSection(10, 'KESEKRETARIATAN', [
            [
                'judul' => 'PROGRAM',
                'items' => [
                    'ATK dan Foto Copy selama 1 Tahun',
                    'Biaya Rapat-Rapat selama 1 Tahun (Rapat Harian, Rapat Bidang, Rapat Pleno)',
                    'Biaya penyelenggaraan Musker IV - Bulan Januari Tahun 2025',
                    'Biaya penyelenggaraan Mubes - Bulan Desember Tahun 2025',
                ],
            ],
        ]);

        // 11) KEBENDAHARAAN
        $makeSection(11, 'KEBENDAHARAAN', [
            [
                'judul' => 'PROGRAM',
                'items' => [
                    'Rekening Listrik Meteran / LAMPU ID: 5512 0001 0551',
                    'Rekening Listrik Prabayar / AIR ID: 3200 2538 521',
                    'Rekening Listrik Meteran / AC ID: 5520 0194 6222',
                    'Pembayaran Rekening Telpon dan Indihome',
                    'Gaji untuk Imam Tetap',
                    'Subsidi Bisyaroh Guru Tugas (dihitung 11 bulan)',
                    'Administrasi, Antar Jemput dan Purna Tugas GT Sidogiri',
                    'Gaji untuk 2 Marbot @Rp. 2.250.000',
                    'Dana tak terduga',
                ],
            ],
        ]);
    }
}
