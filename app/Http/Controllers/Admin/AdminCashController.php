<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCashController extends Controller
{
    public function index(Request $request): View
    {
        $query = CashTransaction::with('recorder');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', date('m', strtotime($request->month)))
                  ->whereYear('transaction_date', date('Y', strtotime($request->month)));
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15);

        $totalIncome = CashTransaction::where('type', 'income')->sum('amount');
        $totalExpense = CashTransaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Group by category for chart/distribution
        $incomeCategories = CashTransaction::where('type', 'income')
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->get();

        $expenseCategories = CashTransaction::where('type', 'expense')
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->get();

        return view('admin.cash.index', compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'balance',
            'incomeCategories',
            'expenseCategories'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'proof_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('cash_proofs', 'public');
        }

        CashTransaction::create([
            'recorded_by' => auth()->id(),
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
            'description' => $validated['description'],
            'proof_image' => $proofPath,
        ]);

        return back()->with('success', 'Transaksi kas berhasil dicatat.');
    }

    public function destroy(CashTransaction $transaction): RedirectResponse
    {
        $transaction->delete();
        return back()->with('success', 'Transaksi kas berhasil dihapus.');
    }
}
