<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MosqueStructureNode;

class MosqueStructureSeeder extends Seeder
{
    public function run(): void
    {
        if (MosqueStructureNode::count() > 0) return;

        // ROOT
        $root = MosqueStructureNode::create([
            'parent_id' => null,
            'jabatan' => "Dewan Kemakmuran Masjid",
            'nama' => "Masjid Agung Al-A'la Kabupaten Gianyar",
            'urutan' => 0,
        ]);

        // TOP
        $mustasyar = MosqueStructureNode::create(['parent_id' => $root->id, 'jabatan' => 'Mustasyar', 'nama' => null, 'urutan' => 1]);
        $dewanSyuro = MosqueStructureNode::create(['parent_id' => $root->id, 'jabatan' => 'Dewan Syuro', 'nama' => null, 'urutan' => 2]);

        $ketua = MosqueStructureNode::create(['parent_id' => $dewanSyuro->id, 'jabatan' => 'Ketua Umum', 'nama' => 'Agus Arianto', 'urutan' => 1]);
        $wakil = MosqueStructureNode::create(['parent_id' => $ketua->id, 'jabatan' => 'Wakil Ketua Umum', 'nama' => null, 'urutan' => 1]);

        $sekum = MosqueStructureNode::create(['parent_id' => $wakil->id, 'jabatan' => 'Sekretaris Umum', 'nama' => null, 'urutan' => 1]);
        $bendum = MosqueStructureNode::create(['parent_id' => $wakil->id, 'jabatan' => 'Bendahara Umum', 'nama' => null, 'urutan' => 2]);

        // BIDANG
        $idarah = MosqueStructureNode::create(['parent_id' => $sekum->id, 'jabatan' => 'Ketua Bid. Idarah', 'nama' => null, 'urutan' => 1]);
        MosqueStructureNode::create(['parent_id' => $idarah->id, 'jabatan' => 'Sek. Bid. Idarah', 'nama' => null, 'urutan' => 1]);
        MosqueStructureNode::create(['parent_id' => $idarah->id, 'jabatan' => 'Bend. Bid. Idarah', 'nama' => null, 'urutan' => 2]);

        $imarah = MosqueStructureNode::create(['parent_id' => $sekum->id, 'jabatan' => 'Ketua Bid. Imarah', 'nama' => null, 'urutan' => 2]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Sek. Bid. Imarah', 'nama' => null, 'urutan' => 1]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Bend. Bid. Imarah', 'nama' => null, 'urutan' => 2]);

        $riayah = MosqueStructureNode::create(['parent_id' => $sekum->id, 'jabatan' => 'Ketua Bid. Riayah', 'nama' => null, 'urutan' => 3]);
        MosqueStructureNode::create(['parent_id' => $riayah->id, 'jabatan' => 'Sek. Bid. Riayah', 'nama' => null, 'urutan' => 1]);

        // SEKSI (contoh, bisa tambah lewat admin)
        MosqueStructureNode::create(['parent_id' => $idarah->id, 'jabatan' => 'Ketua Seksi Dokumentasi, Perpustakaan & Penerbitan', 'nama' => null, 'urutan' => 10]);
        MosqueStructureNode::create(['parent_id' => $idarah->id, 'jabatan' => 'Ketua Seksi Humas, Informasi & Komunikasi', 'nama' => null, 'urutan' => 11]);

        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Ketua Seksi Peribadatan', 'nama' => null, 'urutan' => 10]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Ketua Seksi Pendidikan, Pelatihan & Kaderisasi', 'nama' => null, 'urutan' => 11]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Ketua Seksi Pemberdayaan Perempuan & Sosial', 'nama' => null, 'urutan' => 12]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Ketua Seksi Kesehatan', 'nama' => null, 'urutan' => 13]);
        MosqueStructureNode::create(['parent_id' => $imarah->id, 'jabatan' => 'Ketua Seksi Perekonomian', 'nama' => null, 'urutan' => 14]);

        MosqueStructureNode::create(['parent_id' => $riayah->id, 'jabatan' => 'Ketua Seksi Pembangunan, Pemeliharaan & Perawatan', 'nama' => null, 'urutan' => 10]);
        MosqueStructureNode::create(['parent_id' => $riayah->id, 'jabatan' => 'Ketua Seksi Keamanan, Ketertiban & Kebersihan', 'nama' => null, 'urutan' => 11]);

        // LEMBAGA (dijadikan anak root agar tampil sebagai kolom)
        $lembaga = MosqueStructureNode::create(['parent_id' => $root->id, 'jabatan' => 'Lembaga', 'nama' => null, 'urutan' => 3]);
        MosqueStructureNode::create(['parent_id' => $lembaga->id, 'jabatan' => 'LBH', 'nama' => null, 'urutan' => 1]);
        MosqueStructureNode::create(['parent_id' => $lembaga->id, 'jabatan' => "TPQ Al A'la", 'nama' => null, 'urutan' => 2]);
        MosqueStructureNode::create(['parent_id' => $lembaga->id, 'jabatan' => "Madin Al A'la", 'nama' => null, 'urutan' => 3]);

        // BANOM
        $banom = MosqueStructureNode::create(['parent_id' => $root->id, 'jabatan' => 'Banom', 'nama' => null, 'urutan' => 4]);
        foreach (['MT. Al Ikhlas','MT. Al A\'la','GEMMA','HBMI','PHBI','UPZ','URPUD'] as $i => $x) {
            MosqueStructureNode::create(['parent_id' => $banom->id, 'jabatan' => $x, 'nama' => null, 'urutan' => $i+1]);
        }
    }
}
