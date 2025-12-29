<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgramItem extends Model
{
    protected $fillable = ['part_id', 'teks', 'urutan'];

    public function part()
    {
        return $this->belongsTo(WorkProgramPart::class, 'part_id');
    }
}
