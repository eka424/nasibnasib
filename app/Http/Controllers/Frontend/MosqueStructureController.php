<?php

namespace App\Http\Controllers;

use App\Models\ManagementTerm;
use App\Models\ManagementUnit;

class MosqueStructureController extends Controller
{
    public function index()
    {
        $term = ManagementTerm::query()
            ->where('status', 'published')
            ->orderByDesc('is_active')
            ->orderByDesc('decision_date_masehi')
            ->first();

        $units = collect();

        if ($term) {
            $units = ManagementUnit::query()
                ->where('term_id', $term->id)
                ->whereNull('parent_id') // unit utama aja
                ->with([
                    'positions.members',
                    'children.positions.members',
                ])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        return view('frontend.mosque.struktur', compact('term', 'units'));
    }
}
