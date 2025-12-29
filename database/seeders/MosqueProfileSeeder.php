<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MosqueProfile;

class MosqueProfileSeeder extends Seeder
{
    public function run(): void
    {
        if (MosqueProfile::count() > 0) return;

        MosqueProfile::create([
            'nama' => "Masjid AGUNG AL-A'LA",
            'tipe' => 'Masjid',
            'kategori' => 'Masjid Agung',
            'no_id_masjid' => '01.2.17.04.03.000001',
            'tahun_berdiri' => 1967,

            'alamat' => 'Jl. Kesatrian No. 16 Lingkungan Candi Baru Kelurahan Gianyar Kecamatan Gianyar Kabupaten Gianyar Provinsi Bali Kode Pos 80511',
            'kelurahan' => 'Gianyar',
            'kecamatan' => 'Gianyar',
            'kabupaten' => 'Kab. Gianyar',
            'provinsi' => 'Bali',
            'kode_pos' => '80511',

            'email' => 'masjidagung.gianyar@gmail.com',
            'website' => 'masjidagunggianyar.blogspot.com',
            'url_website' => 'https://masjidagunggianyar.blogspot.com',

            'sejarah' => "Masjid ini didirikan pada tahun 1967...\n\n(Tulis sejarah lengkap di sini)",
            'visi' => 'Menjadi masjid yang makmur, nyaman, dan berperan aktif dalam membina umat.',
            'misi' => [
                'Menyelenggarakan ibadah yang khusyuk dan tertib.',
                'Mengembangkan program dakwah, pendidikan, dan sosial.',
                'Mendorong partisipasi jamaah dalam memakmurkan masjid.',
                'Meningkatkan transparansi dan profesionalisme pengelolaan masjid.',
            ],

            'jumlah_pengurus' => 6,
            'jumlah_imam' => 7,
            'jumlah_khatib' => 11,
            'jumlah_muazin' => 42,
            'jumlah_remaja' => 80,

            'luas_tanah_m2' => 780,
            'status_tanah' => 'Wakaf',
            'luas_bangunan_m2' => 0,
            'daya_tampung' => 1000,
        ]);
    }
}
