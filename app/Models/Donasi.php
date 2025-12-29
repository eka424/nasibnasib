<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'target_dana',
        'dana_terkumpul',
        'tanggal_selesai',
    ];

    protected $casts = [
        'target_dana' => 'integer',
        'dana_terkumpul' => 'integer',
        'tanggal_selesai' => 'date',
    ];

    public function transaksiDonasis(): HasMany
    {
        return $this->hasMany(TransaksiDonasi::class);
    }

    public function syncDanaTerkumpul(): void
    {
        $total = $this->transaksiDonasis()
            ->where('status_pembayaran', 'berhasil')
            ->sum('jumlah');

        $this->forceFill(['dana_terkumpul' => $total])->save();
    }
}
