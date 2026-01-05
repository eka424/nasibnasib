<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ManagementTerm extends Model
{
    // ❌ JANGAN PAKAI connection admin
    // protected $connection = 'admin';

    protected $fillable = [
        'title',
        'decision_title',
        'decision_number',
        'period_label',
        'valid_from',
        'valid_to',
        'location',
        'decision_date_hijri',
        'decision_date_masehi',
        'status',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'decision_date_masehi' => 'date', // PENTING
        'is_active' => 'boolean',
    ];

    public function units()
    {
        return $this->hasMany(ManagementUnit::class, 'term_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where('status', 'published');
    }
}
