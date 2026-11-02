<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryLoan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Inventory::withCount(['loans as active_loans_count' => function ($q) {
            $q->where('status', 'approved');
        }]);

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }

        $inventories = $query->latest()->paginate(10);
        $categories = Inventory::select('category')->distinct()->pluck('category');

        return view('admin.inventories.index', compact('inventories', 'categories'));
    }

    public function create(): View
    {
        return view('admin.inventories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50', 'unique:inventories,item_code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:good,needs_repair,damaged'],
            'location' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventories', 'public');
        }

        Inventory::create([
            'item_code' => $validated['item_code'],
            'name' => $validated['name'],
            'category' => $validated['category'],
            'quantity' => $validated['quantity'],
            'condition' => $validated['condition'],
            'location' => $validated['location'],
            'notes' => $validated['notes'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.inventories.index')->with('success', 'Barang inventaris berhasil didaftarkan.');
    }

    public function edit(Inventory $inventory): View
    {
        return view('admin.inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:50', 'unique:inventories,item_code,' . $inventory->id],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:good,needs_repair,damaged'],
            'location' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventories', 'public');
            $inventory->image = $imagePath;
        }

        $inventory->item_code = $validated['item_code'];
        $inventory->name = $validated['name'];
        $inventory->category = $validated['category'];
        $inventory->quantity = $validated['quantity'];
        $inventory->condition = $validated['condition'];
        $inventory->location = $validated['location'];
        $inventory->notes = $validated['notes'] ?? null;
        $inventory->save();

        return redirect()->route('admin.inventories.index')->with('success', 'Data inventaris berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory): RedirectResponse
    {
        $inventory->delete();
        return redirect()->route('admin.inventories.index')->with('success', 'Barang inventaris berhasil dihapus.');
    }

    // Loans Management
    public function loans(Request $request): View
    {
        $query = InventoryLoan::with(['inventory', 'user', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest('loan_date')->paginate(15);
        $inventories = Inventory::where('condition', 'good')->get();

        return view('admin.inventories.loans', compact('loans', 'inventories'));
    }

    public function storeLoan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inventory_id' => ['required', 'exists:inventories,id'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'borrower_contact' => ['required', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:1'],
            'loan_date' => ['required', 'date'],
            'expected_return_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'notes' => ['nullable', 'string'],
        ]);

        InventoryLoan::create([
            'inventory_id' => $validated['inventory_id'],
            'user_id' => auth()->id(),
            'borrower_name' => $validated['borrower_name'],
            'borrower_contact' => $validated['borrower_contact'],
            'quantity' => $validated['quantity'],
            'loan_date' => $validated['loan_date'],
            'expected_return_date' => $validated['expected_return_date'],
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Pencatatan peminjaman berhasil.');
    }

    public function updateLoanStatus(Request $request, InventoryLoan $loan): RedirectResponse
    {
        $status = $request->input('status');

        if (! in_array($status, ['approved', 'returned', 'rejected'], true)) {
            return back()->with('error', 'Status tidak valid.');
        }

        $updateData = [
            'status' => $status,
            'approved_by' => auth()->id(),
        ];

        if ($status === 'returned') {
            $updateData['actual_return_date'] = now()->toDateString();
        }

        $loan->update($updateData);

        return back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}
