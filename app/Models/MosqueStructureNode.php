<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MosqueStructureNode extends Model
{
    protected $fillable = ['parent_id', 'jabatan', 'nama', 'urutan'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('urutan')->orderBy('id');
    }
}
