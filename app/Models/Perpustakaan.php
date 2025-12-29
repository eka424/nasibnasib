<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perpustakaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'kategori',
        'isbn',
        'deskripsi',
        'file_ebook',
        'cover',
        'stok_total',
        'stok_tersedia',
    ];
}
