<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PertanyaanUstadz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ustadz_id',
        'kategori',
        'pertanyaan',
        'jawaban',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function penanya(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ustadz_id');
    }
}
