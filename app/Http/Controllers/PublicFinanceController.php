<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PublicFinanceController extends Controller
{
    public function index(Request $request)
{
    $mode = $request->get('mode', 'month'); // day|week|month|year
    $date = $request->get('date', now()->toDateString());

    $base = \Illuminate\Support\Carbon::parse($date);

    if ($mode === 'day') {
        $start = $base->copy()->startOfDay();
        $end = $base->copy()->endOfDay();
    } elseif ($mode === 'week') {
        $start = $base->copy()->startOfWeek();
        $end = $base->copy()->endOfWeek();
    } elseif ($mode === 'year') {
        $start = $base->copy()->startOfYear();
        $end = $base->copy()->endOfYear();
    } else {
        $start = $base->copy()->startOfMonth();
        $end = $base->copy()->endOfMonth();
    }

    $year = (int) $start->format('Y');

    // saldo awal tahun (input manual)
    $opening = \App\Models\YearBalance::where('year', $year)->value('opening_balance') ?? 0;

    // transaksi publik di range terpilih (untuk tabel)
    $txRange = \App\Models\Transaction::query()
        ->public()
        ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->get();

    $incomeRange = $txRange->where('type', 'income')->sum('amount');
    $expenseRange = $txRange->where('type', 'expense')->sum('amount');

    // total setahun (untuk saldo)
    $incomeYear = \App\Models\Transaction::public()
        ->where('type', 'income')
        ->whereYear('date', $year)
        ->sum('amount');

    $expenseYear = \App\Models\Transaction::public()
        ->where('type', 'expense')
        ->whereYear('date', $year)
        ->sum('amount');

    $balanceYear = $opening + $incomeYear - $expenseYear;

    // kas hari ini (khusus info harian)
    $todayIncome = \App\Models\Transaction::public()
        ->where('type', 'income')->whereDate('date', now()->toDateString())->sum('amount');

    $todayExpense = \App\Models\Transaction::public()
        ->where('type', 'expense')->whereDate('date', now()->toDateString())->sum('amount');

    $todayNet = $todayIncome - $todayExpense;

    // pengeluaran per bidang (range) termasuk "lainnya"
    $byDivision = $txRange->where('type', 'expense')
        ->groupBy(fn($t) => $t->division ?: 'lainnya')
        ->map(fn($g) => $g->sum('amount'))
        ->toArray();

    // chart time-series: per hari untuk range (day/week/month), kalau year -> per bulan
    $series = [];

    if ($mode === 'year') {
        for ($m = 1; $m <= 12; $m++) {
            $series[] = [
                'label' => \Illuminate\Support\Carbon::create($year, $m, 1)->format('M'),
                'income' => \App\Models\Transaction::public()->where('type','income')->whereYear('date',$year)->whereMonth('date',$m)->sum('amount'),
                'expense' => \App\Models\Transaction::public()->where('type','expense')->whereYear('date',$year)->whereMonth('date',$m)->sum('amount'),
            ];
        }
    } else {
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $d = $cursor->toDateString();
            $series[] = [
                'label' => $d,
                'income' => $txRange->where('type','income')->where('date', $d)->sum('amount'),
                'expense' => $txRange->where('type','expense')->where('date', $d)->sum('amount'),
            ];
            $cursor->addDay();
        }
    }

    // REKAP TAHUNAN (tabel per bulan) -> selalu disiapkan
    $yearRecap = [];
    $running = $opening;

    for ($m = 1; $m <= 12; $m++) {
        $inc = \App\Models\Transaction::public()->where('type','income')->whereYear('date',$year)->whereMonth('date',$m)->sum('amount');
        $exp = \App\Models\Transaction::public()->where('type','expense')->whereYear('date',$year)->whereMonth('date',$m)->sum('amount');
        $running = $running + $inc - $exp;

        $yearRecap[] = [
            'month' => \Illuminate\Support\Carbon::create($year, $m, 1)->format('F'),
            'income' => $inc,
            'expense' => $exp,
            'balance' => $running,
        ];
    }

    return view('public.finance.index', [
        'mode' => $mode,
        'date' => $base->toDateString(),
        'start' => $start,
        'end' => $end,

        'opening' => $opening,
        'balanceYear' => $balanceYear,

        'todayIncome' => $todayIncome,
        'todayExpense' => $todayExpense,
        'todayNet' => $todayNet,

        'transactions' => $txRange,

        'incomeRange' => $incomeRange,
        'expenseRange' => $expenseRange,

        'byDivision' => $byDivision,
        'series' => $series,
        'year' => $year,
        'yearRecap' => $yearRecap,
    ]);
}
public function yearly(Request $request)
{
    $year = (int) ($request->get('year', now()->year));

    $opening = \App\Models\YearBalance::where('year', $year)->value('opening_balance') ?? 0;

    // rekap per bulan (income/expense + saldo berjalan)
    $yearRecap = [];
    $running = $opening;

    for ($m = 1; $m <= 12; $m++) {
        $income = \App\Models\Transaction::public()
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->sum('amount');

        $expense = \App\Models\Transaction::public()
            ->where('type', 'expense')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->sum('amount');

        $running = $running + $income - $expense;

        $yearRecap[] = [
            'month' => \Illuminate\Support\Carbon::create($year, $m, 1)->format('F'),
            'income' => $income,
            'expense' => $expense,
            'balance' => $running,
        ];
    }

    // total setahun
    $incomeYear = array_sum(array_column($yearRecap, 'income'));
    $expenseYear = array_sum(array_column($yearRecap, 'expense'));
    $balanceYear = $opening + $incomeYear - $expenseYear;

    // pengeluaran per bidang setahun (termasuk lainnya)
    $byDivisionYear = \App\Models\Transaction::public()
        ->where('type', 'expense')
        ->whereYear('date', $year)
        ->get()
        ->groupBy(fn($t) => $t->division ?: 'lainnya')
        ->map(fn($g) => $g->sum('amount'))
        ->toArray();

    // chart series per bulan
    $series = [];
    for ($m = 1; $m <= 12; $m++) {
        $series[] = [
            'label' => \Illuminate\Support\Carbon::create($year, $m, 1)->format('M'),
            'income' => $yearRecap[$m - 1]['income'],
            'expense' => $yearRecap[$m - 1]['expense'],
        ];
    }

    return view('public.finance.yearly', [
        'year' => $year,
        'opening' => $opening,
        'incomeYear' => $incomeYear,
        'expenseYear' => $expenseYear,
        'balanceYear' => $balanceYear,
        'yearRecap' => $yearRecap,
        'byDivisionYear' => $byDivisionYear,
        'series' => $series,
    ]);
}

    public function receipt(Transaction $transaction)
    {
        abort_unless($transaction->is_public, 404);
        abort_unless($transaction->receipt_path, 404);

        $path = $transaction->receipt_path;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // tampilkan bukti langsung
        return response()->file(
            Storage::disk('public')->path($path),
            ['Content-Type' => $transaction->receipt_mime ?? 'application/octet-stream']

            
        );
    }
}
