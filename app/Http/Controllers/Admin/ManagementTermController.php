<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManagementTerm;
use Illuminate\Http\Request;

class ManagementTermController extends Controller
{
    public function index()
    {
        $terms = ManagementTerm::query()
            ->orderByDesc('is_active')
            ->orderByDesc('decision_date_masehi')
            ->orderByDesc('id')
            ->get();

        return view('admin.struktur.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.struktur.form', [
            'term' => new ManagementTerm(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|string|max:255',
            'decision_title' => 'nullable|string|max:255',
            'decision_number' => 'nullable|string|max:255',
            'period_label' => 'nullable|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'location' => 'nullable|string|max:100',
            'decision_date_hijri' => 'nullable|string|max:100',
            'decision_date_masehi' => 'nullable|date',
        ]);

        $data['status'] = 'draft';
        $data['is_active'] = false;

        $term = ManagementTerm::create($data);

        return redirect()->route('admin.struktur.builder', $term)->with('ok', 'Periode dibuat. Silakan isi struktur.');
    }

    public function edit(ManagementTerm $term)
    {
        return view('admin.struktur.form', [
            'term' => $term,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $r, ManagementTerm $term)
    {
        $data = $r->validate([
            'title' => 'required|string|max:255',
            'decision_title' => 'nullable|string|max:255',
            'decision_number' => 'nullable|string|max:255',
            'period_label' => 'nullable|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'location' => 'nullable|string|max:100',
            'decision_date_hijri' => 'nullable|string|max:100',
            'decision_date_masehi' => 'nullable|date',
        ]);

        $term->update($data);

        return back()->with('ok', 'Periode diperbarui.');
    }

    public function setActive(ManagementTerm $term)
    {
        ManagementTerm::query()->update(['is_active' => false]);
        $term->update(['is_active' => true]);

        return back()->with('ok', 'Periode aktif berhasil diubah.');
    }

    public function publish(ManagementTerm $term)
    {
        $term->update(['status' => 'published']);

        return back()->with('ok', 'Periode berhasil dipublish.');
    }
}
