<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementMember extends Model
{
    // ❌ HAPUS connection admin
    // protected $connection = 'admin';

    protected $fillable = [
        'position_id',
        'name',
        'sort_order',
    ];

    public function position()
    {
        return $this->belongsTo(ManagementPosition::class, 'position_id');
    }
}
