<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementPosition extends Model
{
    // ❌ HAPUS connection admin
    // protected $connection = 'admin';

    protected $fillable = [
        'unit_id',
        'title',
        'notes',
        'sort_order',
    ];

    public function unit()
    {
        return $this->belongsTo(ManagementUnit::class, 'unit_id');
    }

    public function members()
    {
        return $this->hasMany(ManagementMember::class, 'position_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
