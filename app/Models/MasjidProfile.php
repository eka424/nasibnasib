<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasjidProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'sejarah',
        'visi',
        'misi',
    ];
}
