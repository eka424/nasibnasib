<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearBalance;
use Illuminate\Http\Request;

class YearBalanceController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) ($request->get('year', now()->year));

        $row = YearBalance::firstOrCreate(
            ['year' => $year],
            ['opening_balance' => 0]
        );

        return view('admin.finance.year-balance', [
            'year' => $year,
            'row' => $row,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'opening_balance' => ['required', 'integer', 'min:0'],
        ]);

        YearBalance::updateOrCreate(
            ['year' => (int) $data['year']],
            ['opening_balance' => (int) $data['opening_balance']]
        );

        return redirect()
            ->route('admin.finance.year-balance.index', ['year' => $data['year']])
            ->with('success', 'Saldo awal berhasil disimpan.');
    }
}
