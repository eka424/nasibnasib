<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementUnit extends Model
{
    protected $fillable = [
        'term_id',
        'name',
        'sort_order',
    ];

    public function term()
    {
        return $this->belongsTo(ManagementTerm::class, 'term_id');
    }

    public function positions()
    {
        return $this->hasMany(ManagementPosition::class, 'unit_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
