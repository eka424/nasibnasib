<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SedekahTransaction extends Model
{
  protected $fillable = [
    'user_id','sedekah_campaign_id','order_id','jumlah',
    'nama','email','whatsapp','pesan',
    'status','payment_type','transaction_id','fraud_status',
    'snap_token','raw_notif'
  ];

  protected $casts = [
    'raw_notif' => 'array',
  ];

  public function campaign()
  {
    return $this->belongsTo(SedekahCampaign::class, 'sedekah_campaign_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
