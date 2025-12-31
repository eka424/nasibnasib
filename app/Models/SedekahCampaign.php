<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SedekahCampaign extends Model
{
  protected $fillable = [
    'judul','deskripsi','target_dana','dana_terkumpul','poster','tanggal_selesai','is_active'
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'tanggal_selesai' => 'date',
  ];

  public function transaksi()
  {
    return $this->hasMany(SedekahTransaction::class, 'sedekah_campaign_id');
  }
}
