<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = Transaction::query()->orderBy('date', 'desc')->orderBy('id', 'desc');

        if ($request->filled('type')) $q->where('type', $request->type);
        if ($request->filled('division')) $q->where('division', $request->division);
        if ($request->filled('date')) $q->whereDate('date', $request->date);

        return view('admin.finance.transactions.index', [
            'transactions' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.finance.transactions.create', [
            'accounts' => Account::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['created_by'] = auth()->id();

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $data['receipt_mime'] = $file->getMimeType();
            $data['receipt_path'] = $file->store('receipts', 'public');
        }

        Transaction::create($data);

        return redirect()->route('admin.finance.transaksi.index')->with('status', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaksi)
    {
        return view('admin.finance.transactions.edit', [
            'transaction' => $transaksi,
            'accounts' => Account::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaksi)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('receipt')) {
            if ($transaksi->receipt_path) Storage::disk('public')->delete($transaksi->receipt_path);

            $file = $request->file('receipt');
            $data['receipt_mime'] = $file->getMimeType();
            $data['receipt_path'] = $file->store('receipts', 'public');
        }

        $transaksi->update($data);

        return redirect()->route('admin.finance.transaksi.index')->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaksi)
    {
        if ($transaksi->receipt_path) Storage::disk('public')->delete($transaksi->receipt_path);
        $transaksi->delete();

        return back()->with('status', 'Transaksi dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'date' => ['required','date'],
            'time' => ['nullable'],
            'type' => ['required','in:income,expense'],

            'division' => ['nullable','in:idarah,imarah,riayah'],
            'subcategory' => ['nullable','string','max:120'],

            'title' => ['required','string','max:180'],
            'description' => ['nullable','string'],

            'amount' => ['required','integer','min:0'],
            'payment_method' => ['required','in:cash,transfer,qris,other'],

            'account_id' => ['nullable','exists:accounts,id'],
            'receipt' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],

            'is_public' => ['required','boolean'],
        ]);

        // rule: expense wajib punya division
        if ($data['type'] === 'expense' && empty($data['division'])) {
            abort(422, 'Pengeluaran wajib memilih bidang (Idarah/Imarah/Riayah).');
        }

        // income tidak perlu division
        if ($data['type'] === 'income') {
            $data['division'] = null;
            $data['subcategory'] = $data['subcategory'] ?? null;
        }

        return $data;
    }
}
