<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KidsContent extends Model
{
    protected $table = 'kids_contents';

protected $fillable = [
    'type',
    'title',
    'slug',
    'description',
    'quote_text',
    'quote_source',
    'youtube_id',
    'youtube_url',
    'pdf_path',
    'thumbnail',
    'is_published',
    'sort_order',
    'published_at',
];


    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}
