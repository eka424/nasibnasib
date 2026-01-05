<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasjidProfile extends Model
{
    protected $fillable = [
        'nama','kategori','tipe','no_id_masjid','tahun_berdiri',

        'alamat','kelurahan','kecamatan','kabupaten','provinsi','kode_pos','lat','lng',

        'email','website','url_website','url_peta','url_petunjuk',

        'sejarah','visi','misi',

        'jumlah_pengurus','jumlah_imam','jumlah_khatib','jumlah_muazin','jumlah_remaja',

        'luas_tanah_m2','status_tanah','luas_bangunan_m2','daya_tampung',
    ];

    protected $casts = [
        'misi' => 'array',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'luas_tanah_m2' => 'integer',
        'luas_bangunan_m2' => 'integer',
        'jumlah_pengurus' => 'integer',
        'jumlah_imam' => 'integer',
        'jumlah_khatib' => 'integer',
        'jumlah_muazin' => 'integer',
        'jumlah_remaja' => 'integer',
    ];
}
