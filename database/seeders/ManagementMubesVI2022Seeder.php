<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagementMubesVI2022Seeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /**
             * OPTIONAL (kalau kamu mau seed ulang tanpa dobel data)
             * Hapus term yang sama berdasarkan decision_number
             * (akan cascade delete kalau FK kamu cascadeOnDelete sudah benar)
             */
            $existing = DB::table('management_terms')
                ->where('decision_number', '013 / MUBES-VI / I / 2022')
                ->first();

            if ($existing) {
                // delete term -> otomatis hapus units/positions/members kalau FK cascade bener
                DB::table('management_terms')->where('id', $existing->id)->delete();
            }

            // 1) buat term (dokumen/periode)
            $termId = DB::table('management_terms')->insertGetId([
                'title' => 'SUSUNAN PENGURUS DKM MASJID AGUNG AL-A’LA KAB. GIANYAR',
                'decision_title' => 'KEPUTUSAN MUSYAWARAH BESAR VI DEWAN KEMAKMURAN MASJID AGUNG AL-A’LA KABUPATEN GIANYAR TAHUN 2022',
                'decision_number' => '013 / MUBES-VI / I / 2022',
                'period_label' => '2022-2026',
                'valid_from' => '2022-01-28',
                'valid_to' => '2026-12-31',
                'location' => 'Gianyar',
                'decision_date_hijri' => '25 Jumadil Akhir 1443 H',
                'decision_date_masehi' => '2022-01-28',
                'status' => 'published',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // helper buat unit
            $makeUnit = function (string $name, ?int $parentId = null, string $type = 'group', int $sort = 0) use ($termId) {
                return DB::table('management_units')->insertGetId([
                    'term_id' => $termId,
                    'parent_id' => $parentId,
                    'name' => $name,
                    'type' => $type,
                    'sort_order' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            };

            // helper buat position
            $makePos = function (int $unitId, string $title, ?string $notes = null, int $sort = 0) {
                return DB::table('management_positions')->insertGetId([
                    'unit_id' => $unitId,
                    'title' => $title,
                    'notes' => $notes,
                    'sort_order' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            };

            // helper buat member
            $addMembers = function (int $positionId, array $names, int $startSort = 0) {
                foreach ($names as $i => $name) {
                    DB::table('management_members')->insert([
                        'position_id' => $positionId,
                        'name' => $name,
                        'sort_order' => $startSort + $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            };

            // =========================
            // MUSTASYAR (PENASEHAT)
            // =========================
            $uMustasyar = $makeUnit('MUSTASYAR (PENASEHAT)', null, 'group', 1);
            $pMustasyar = $makePos($uMustasyar, 'ANGGOTA', null, 1);
            $addMembers($pMustasyar, [
                "MUHAMMAD HASYIM ASY’ARIE",
                "ABDUL MUHRI MULYONO",
                "MUHAMMAD HOSEN",
                "SULAIMAN, S.Ag",
                "Drs.H.ANSORI, A.Ma",
                "SETYO",
                "BAMBANG HERMANTO",
                "H.MOCH.SUMADI ARIFIN",
                "R. KATIDJO SOSRO WIJOYO",
            ]);

            // =========================
            // DEWAN SYURO
            // =========================
            $uSyu = $makeUnit('DEWAN SYURO', null, 'group', 2);

            $pos = $makePos($uSyu, 'RAIS', null, 1);
            $addMembers($pos, ["H.IBNU ATHO’ILLAH, ST.MT."]);

            $pos = $makePos($uSyu, 'WAKIL RAIS', null, 2);
            $addMembers($pos, ["H.MUHAMMAD YASIN"]);

            $pos = $makePos($uSyu, 'KATIB', null, 3);
            $addMembers($pos, ["NURWIDYASWANTO, ATD.MT."]);

            $pos = $makePos($uSyu, 'ANGGOTA', null, 4);
            $addMembers($pos, [
                "HARI SUBAMBANG",
                "H.ABDUL MALIK",
                "H.PARNIANTO",
                "SUKISNO SUWANDI",
            ]);

            // =========================
            // DEWAN TANFIDZ
            // =========================
            $uTanfidz = $makeUnit('DEWAN TANFIDZ', null, 'group', 3);

            $pos = $makePos($uTanfidz, 'KETUA UMUM', null, 1);
            $addMembers($pos, ["AGUS ARIANTO"]);

            $pos = $makePos($uTanfidz, 'WAKIL KETUA UMUM', null, 2);
            $addMembers($pos, ["Drs.LALU UCIN, AMd.GIZI"]);

            $pos = $makePos($uTanfidz, 'KETUA 1', '(BID. IDARAH)', 3);
            $addMembers($pos, ["EKA HASTA PATMAJA, S.E"]);

            $pos = $makePos($uTanfidz, 'KETUA 2', '(BID. IMARAH)', 4);
            $addMembers($pos, ["FIRMAN AYANI, M.Pd.I"]);

            $pos = $makePos($uTanfidz, 'KETUA 3', '(BID. RI’AYAH)', 5);
            $addMembers($pos, ["SUKENDI SANTOSO"]);

            $pos = $makePos($uTanfidz, 'SEKRETARIS UMUM', null, 6);
            $addMembers($pos, ["MUHAMMAD SUYANA"]);

            $pos = $makePos($uTanfidz, 'SEKRETARIS 1', null, 7);
            $addMembers($pos, ["AGUS SURYADI, S.S."]);

            $pos = $makePos($uTanfidz, 'SEKRETARIS 2', null, 8);
            $addMembers($pos, ["ARI SAPUTRO"]);

            $pos = $makePos($uTanfidz, 'SEKRETARIS 3', null, 9);
            $addMembers($pos, ["MOHAMAD DEDY PRATAMA YYS, S.IP."]);

            $pos = $makePos($uTanfidz, 'BENDAHARA UMUM', null, 10);
            $addMembers($pos, ["MUNADI"]);

            $pos = $makePos($uTanfidz, 'BENDAHARA 1', null, 11);
            $addMembers($pos, ["H.M.MASHURI ARIFIN"]);

            $pos = $makePos($uTanfidz, 'BENDAHARA 2', null, 12);
            $addMembers($pos, ["ABDUL MUHITH"]);

            // =========================
            // BIDANG IDARAH
            // =========================
            $uIdarah = $makeUnit('I. BIDANG IDARAH', null, 'field', 4);

            $uDok = $makeUnit('SEKSI DOK. PERPUSTAKAAN DAN PENERBITAN', $uIdarah, 'section', 1);
            $pos = $makePos($uDok, 'ANGGOTA', null, 1);
            $addMembers($pos, ["MUHAMMAD ZANUAR AFANDHI"]);

            $uHumas = $makeUnit('SEKSI HUMAS INFORMASI DAN KOMUNIKASI', $uIdarah, 'section', 2);
            $pos = $makePos($uHumas, 'ANGGOTA', null, 1);
            $addMembers($pos, ["ROSYIDI", "SHOLEHUDIN", "ANDIK SUPRIYANTO"]);

            // =========================
            // BIDANG IMARAH
            // =========================
            $uImarah = $makeUnit('II. BIDANG IMARAH', null, 'field', 5);

            $uIbadah = $makeUnit('SEKSI PERIBADATAN', $uImarah, 'section', 1);
            $pos = $makePos($uIbadah, 'ANGGOTA', null, 1);
            $addMembers($pos, ["H.MUZAMMIL", "ABDUL AZIS, S.PdI", "ALI PARDI", "YUDI GUNTARA"]);

            $uDiklat = $makeUnit('SEKSI PENDIDIKAN PELATIHAN DAN KADERISASI', $uImarah, 'section', 2);
            $pos = $makePos($uDiklat, 'ANGGOTA', null, 1);
            $addMembers($pos, [
                "H.MIFTAHUL HUDA",
                "LAILATUL QODAR",
                "INGGAR GALUH SHAFIRA HONEY, S.PdI, MA",
                "AHMAD AINUL YAQIN",
                "FIFIN LUJJATIL BAHRIL WAHDATI, S.H.",
            ]);

            $uPerempuan = $makeUnit('SEKSI PEMBERDAYAAN PEREMPUAN DAN SOSIAL', $uImarah, 'section', 3);
            $pos = $makePos($uPerempuan, 'ANGGOTA', null, 1);
            $addMembers($pos, ["PUJI NINGSIH", "SITI NGATEMINAH", "NUR MEGA FUSHSHILAT, S.H."]);

            $uSehat = $makeUnit('SEKSI KESEHATAN', $uImarah, 'section', 4);
            $pos = $makePos($uSehat, 'ANGGOTA', null, 1);
            $addMembers($pos, ["MAHMUD", "dr. LUTFI HABIBAH", "FITRIA DEWI, S.St.Keb", "PURWANTO", "IMAM MUJAIDI"]);

            $uEko = $makeUnit('SEKSI PEREKONOMIAN', $uImarah, 'section', 5);
            $pos = $makePos($uEko, 'ANGGOTA', null, 1);
            $addMembers($pos, [
                "LISTIYONO YUSUP",
                "H.ARIS WILANTORO",
                "WAHYUNI",
                "Hj.ALFIATIN",
                "YUNUS HANGGARA PRIBADI, S.Akun",
            ]);

            // =========================
            // BIDANG RI’AYAH
            // =========================
            $uRiayah = $makeUnit("III. BIDANG RI’AYAH", null, 'field', 6);

            $uPemeliharaan = $makeUnit('SEKSI PEMB. PEMELIHARAAN DAN PERAWATAN', $uRiayah, 'section', 1);

            $uFisik = $makeUnit('FISIK BANGUNAN', $uPemeliharaan, 'other', 1);
            $pos = $makePos($uFisik, 'ANGGOTA', null, 1);
            $addMembers($pos, ["H.DIMYATI SYAM", "H.SUGIRIN", "GUNAWAN"]);

            $uRawat = $makeUnit('PEMELIHARAAN DAN PERAWATAN', $uPemeliharaan, 'other', 2);
            $pos = $makePos($uRawat, 'ANGGOTA', null, 1);
            $addMembers($pos, ["HADI WINARNO", "MUHAMMAD JUPRI"]);

            $uListrik = $makeUnit('INSTALASI LISTRIK', $uPemeliharaan, 'other', 3);
            $pos = $makePos($uListrik, 'ANGGOTA', null, 1);
            $addMembers($pos, ["IRWAN EKO DIANTO", "ARIFIN"]);

            $uSoundTech = $makeUnit('TECHNISI SOUND SYSTEM', $uPemeliharaan, 'other', 4);
            $pos = $makePos($uSoundTech, 'ANGGOTA', null, 1);
            $addMembers($pos, ["SAPERI"]);

            $uSoundOp = $makeUnit('OPERATOR SOUND SYSTEM', $uPemeliharaan, 'other', 5);
            $pos = $makePos($uSoundOp, 'ANGGOTA', null, 1);
            $addMembers($pos, ["MINTO SUWARNO"]);

            $uRT = $makeUnit('KERUMAHTANGGAAN', $uPemeliharaan, 'other', 6);
            $pos = $makePos($uRT, 'ANGGOTA', null, 1);
            $addMembers($pos, ["ENDANG KUSMAWAN", "SAERAN"]);

            $uKeamanan = $makeUnit('SEKSI KEAMANAN KETERTIBAN DAN KEBERSIHAN', $uRiayah, 'section', 2);
            $pos = $makePos($uKeamanan, 'ANGGOTA', null, 1);
            $addMembers($pos, ["ATHOK MUCHLASIN", "SAFARI", "SUPRIYANTO"]);
        });
    }
}
