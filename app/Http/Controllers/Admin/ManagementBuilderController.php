<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManagementTerm;
use App\Models\ManagementUnit;
use App\Models\ManagementPosition;
use App\Models\ManagementMember;

class ManagementBuilderController extends Controller
{
    public function builder(ManagementTerm $term)
    {
        $units = ManagementUnit::where('term_id', $term->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $positions = ManagementPosition::whereIn('unit_id', $units->pluck('id'))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $members = ManagementMember::whereIn('position_id', $positions->pluck('id'))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $childrenByParent = $units->groupBy(fn ($u) => $u->parent_id ?? 0);
        $positionsByUnit = $positions->groupBy('unit_id');
        $membersByPosition = $members->groupBy('position_id');

        return view('admin.struktur.builder', compact(
            'term',
            'units',
            'childrenByParent',
            'positionsByUnit',
            'membersByPosition'
        ));
    }
}
