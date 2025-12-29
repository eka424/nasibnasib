<?php

namespace App\Http\Controllers;

use App\Models\MosqueStructureNode;

class MosqueStructureController extends Controller
{
    public function index()
    {
        $root = MosqueStructureNode::with('children.children.children.children.children.children')
            ->whereNull('parent_id')
            ->firstOrFail();

        return view('mosque.struktur', compact('root'));
    }
}
