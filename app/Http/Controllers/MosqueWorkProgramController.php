<?php

namespace App\Http\Controllers;

use App\Models\WorkProgramSection;

class MosqueWorkProgramController extends Controller
{
    public function index()
    {
        $sections = WorkProgramSection::with('parts.items')
            ->where('is_active', true)
            ->orderBy('urutan')->orderBy('id')
            ->get();

        return view('mosque.program_kerja', compact('sections'));
    }
}
