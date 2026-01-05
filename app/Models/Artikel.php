<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * RELATION
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ============================
     * AUTO FIX THUMBNAIL URL
     * ============================
     * Bisa handle:
     * - thumbnails/xxx.jpg
     * - storage/thumbnails/xxx.jpg
     * - URL full (http / https)
     * - NULL → fallback Unsplash
     */
    public function getThumbnailUrlAttribute(): string
    {
        // Tidak ada thumbnail → fallback
        if (empty($this->thumbnail)) {
            return 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
        }

        // Sudah URL penuh
        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        // Bersihkan path
        $path = ltrim($this->thumbnail, '/');

        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        // Kalau file benar-benar ada → pakai storage
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        // Fallback terakhir
        return 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=1600&q=80';
    }
}
