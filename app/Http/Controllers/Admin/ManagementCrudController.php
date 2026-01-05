<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManagementUnit;
use App\Models\ManagementPosition;
use App\Models\ManagementMember;
use Illuminate\Http\Request;

class ManagementCrudController extends Controller
{
    public function storeUnit(Request $r)
    {
        $data = $r->validate([
            'term_id' => 'required|integer',
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'type' => 'required|in:group,field,section,other',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        ManagementUnit::create($data);

        return back()->with('ok', 'Unit berhasil ditambahkan.');
    }

    public function storePosition(Request $r)
    {
        $data = $r->validate([
            'unit_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        ManagementPosition::create($data);

        return back()->with('ok', 'Jabatan berhasil ditambahkan.');
    }

    public function storeMember(Request $r)
    {
        $data = $r->validate([
            'position_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        ManagementMember::create($data);

        return back()->with('ok', 'Anggota berhasil ditambahkan.');
    }
}
