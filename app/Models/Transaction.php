<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'date','time','type','division','subcategory','title','description',
        'amount','payment_method','account_id','receipt_path','receipt_mime',
        'created_by','is_public',
    ];

    protected $casts = [
        'date' => 'date',
        'is_public' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublic($q)
    {
        return $q->where('is_public', true);
    }
}
