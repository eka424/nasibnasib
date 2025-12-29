<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDonasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donasi_id',
        'jumlah',
        'status_pembayaran',
        'gateway',
        'xendit_invoice_id',
        'xendit_external_id',
        'payment_url',
        'dibayar_pada',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'status_pembayaran' => 'string',
        'dibayar_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class);
    }
}
