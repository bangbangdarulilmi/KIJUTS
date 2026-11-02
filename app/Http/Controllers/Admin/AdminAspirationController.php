<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAspirationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Aspiration::with(['user', 'responder']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $aspirations = $query->latest()->paginate(15);

        return view('admin.aspirations.index', compact('aspirations'));
    }

    public function reply(Request $request, Aspiration $aspiration): RedirectResponse
    {
        $validated = $request->validate([
            'response' => ['required', 'string'],
            'is_public' => ['boolean'],
        ]);

        $aspiration->update([
            'response' => $validated['response'],
            'responded_by' => auth()->id(),
            'status' => 'responded',
            'is_public' => $request->boolean('is_public'),
        ]);

        return back()->with('success', 'Tanggapan aspirasi berhasil disimpan.');
    }

    public function destroy(Aspiration $aspiration): RedirectResponse
    {
        $aspiration->delete();
        return back()->with('success', 'Aspirasi berhasil dihapus.');
    }
}
