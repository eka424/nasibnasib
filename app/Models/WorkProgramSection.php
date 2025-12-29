<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgramSection extends Model
{
    protected $fillable = ['nama', 'urutan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function parts()
    {
        return $this->hasMany(WorkProgramPart::class, 'section_id')->orderBy('urutan')->orderBy('id');
    }
}
