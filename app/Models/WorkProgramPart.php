<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgramPart extends Model
{
    protected $fillable = ['section_id', 'judul', 'urutan'];

    public function section()
    {
        return $this->belongsTo(WorkProgramSection::class, 'section_id');
    }

    public function items()
    {
        return $this->hasMany(WorkProgramItem::class, 'part_id')->orderBy('urutan')->orderBy('id');
    }
}
