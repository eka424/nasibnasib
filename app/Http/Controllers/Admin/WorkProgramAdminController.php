<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkProgramSection;
use App\Models\WorkProgramPart;
use App\Models\WorkProgramItem;
use Illuminate\Http\Request;

class WorkProgramAdminController extends Controller
{
    public function index()
    {
        $sections = WorkProgramSection::with('parts.items')
            ->orderBy('urutan')->orderBy('id')
            ->get();

        return view('admin.work_program.all_in_one', compact('sections'));
    }

    // ===== SECTION =====
    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        WorkProgramSection::create([
            'nama' => $data['nama'],
            'urutan' => $data['urutan'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Seksi berhasil ditambahkan.');
    }

    public function updateSection(Request $request, WorkProgramSection $section)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $section->update([
            'nama' => $data['nama'],
            'urutan' => $data['urutan'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Seksi berhasil diupdate.');
    }

    public function destroySection(WorkProgramSection $section)
    {
        $section->delete(); // cascade delete parts/items
        return back()->with('success', 'Seksi berhasil dihapus.');
    }

    // ===== PART =====
    public function storePart(Request $request)
    {
        $data = $request->validate([
            'section_id' => 'required|exists:work_program_sections,id',
            'judul' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);

        WorkProgramPart::create([
            'section_id' => $data['section_id'],
            'judul' => $data['judul'],
            'urutan' => $data['urutan'] ?? 0,
        ]);

        return back()->with('success', 'Bagian (a,b,c) berhasil ditambahkan.');
    }

    public function updatePart(Request $request, WorkProgramPart $part)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $part->update([
            'judul' => $data['judul'],
            'urutan' => $data['urutan'] ?? 0,
        ]);

        return back()->with('success', 'Bagian berhasil diupdate.');
    }

    public function destroyPart(WorkProgramPart $part)
    {
        $part->delete(); // cascade delete items
        return back()->with('success', 'Bagian berhasil dihapus.');
    }

    // ===== ITEM =====
    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:work_program_parts,id',
            'teks' => 'required|string|max:500',
            'urutan' => 'nullable|integer|min:0',
        ]);

        WorkProgramItem::create([
            'part_id' => $data['part_id'],
            'teks' => $data['teks'],
            'urutan' => $data['urutan'] ?? 0,
        ]);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function updateItem(Request $request, WorkProgramItem $item)
    {
        $data = $request->validate([
            'teks' => 'required|string|max:500',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $item->update([
            'teks' => $data['teks'],
            'urutan' => $data['urutan'] ?? 0,
        ]);

        return back()->with('success', 'Item berhasil diupdate.');
    }

    public function destroyItem(WorkProgramItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}
