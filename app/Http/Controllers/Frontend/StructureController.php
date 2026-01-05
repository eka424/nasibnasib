<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ManagementTerm;
use App\Models\ManagementUnit;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index(Request $r)
    {
        $term = ManagementTerm::active()->first();

        if (!$term) {
            return view('front.struktur.index', [
                'term' => null,
                'units' => collect(),
            ]);
        }

        $units = ManagementUnit::query()
            ->where('term_id', $term->id)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with([
                'positions' => fn($q) => $q->orderBy('sort_order')->with(['members' => fn($m)=>$m->orderBy('sort_order')]),
                'children' => fn($c) => $c->orderBy('sort_order')->with([
                    'positions' => fn($q) => $q->orderBy('sort_order')->with(['members' => fn($m)=>$m->orderBy('sort_order')]),
                ]),
            ])
            ->get();

        return view('front.struktur.index', compact('term','units'));
    }
}
