<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MosqueStructureNode;
use Illuminate\Http\Request;

class MosqueStructureAdminController extends Controller
{
    public function index()
    {
        $nodes = MosqueStructureNode::orderBy('parent_id')->orderBy('urutan')->orderBy('id')->get();
        $parents = MosqueStructureNode::orderBy('jabatan')->get();

        return view('admin.mosque_structure.index', compact('nodes', 'parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:mosque_structure_nodes,id',
            'jabatan' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);

        MosqueStructureNode::create($data);
        return back()->with('success', 'Node struktur berhasil ditambahkan.');
    }

    public function update(Request $request, MosqueStructureNode $node)
    {
        $data = $request->validate([
            'parent_id' => 'nullable|exists:mosque_structure_nodes,id',
            'jabatan' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);

        // cegah parent ke dirinya sendiri
        if ($data['parent_id'] ?? null) {
            if ((int)$data['parent_id'] === (int)$node->id) {
                return back()->withErrors(['parent_id' => 'Parent tidak boleh dirinya sendiri.']);
            }
        }

        $node->update($data);
        return back()->with('success', 'Node struktur berhasil diupdate.');
    }

    public function destroy(MosqueStructureNode $node)
    {
        $node->delete();
        return back()->with('success', 'Node struktur berhasil dihapus.');
    }
}
