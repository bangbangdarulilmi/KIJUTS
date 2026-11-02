<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMemberController extends Controller
{
    public function index(Request $request): View
    {
        $query = Member::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('generation')) {
            $query->where('generation', $request->generation);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%')
                  ->orWhere('role_in_org', 'like', '%' . $request->search . '%');
            });
        }

        $members = $query->orderBy('generation', 'desc')->orderBy('name', 'asc')->paginate(15);
        $generations = Member::select('generation')->distinct()->orderBy('generation', 'desc')->pluck('generation');

        return view('admin.members.index', compact('members', 'generations'));
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:30', 'unique:members,nim'],
            'generation' => ['required', 'string', 'max:10'],
            'status' => ['required', 'in:active,alumni,inactive'],
            'role_in_org' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::where('nim', $validated['nim'])->first();

        Member::create([
            'user_id' => $user?->id,
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'generation' => $validated['generation'],
            'status' => $validated['status'],
            'role_in_org' => $validated['role_in_org'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'skills' => $validated['skills'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'avatar' => $avatarPath,
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit(Member $member): View
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:30', 'unique:members,nim,' . $member->id],
            'generation' => ['required', 'string', 'max:10'],
            'status' => ['required', 'in:active,alumni,inactive'],
            'role_in_org' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $member->avatar = $avatarPath;
        }

        $member->name = $validated['name'];
        $member->nim = $validated['nim'];
        $member->generation = $validated['generation'];
        $member->status = $validated['status'];
        $member->role_in_org = $validated['role_in_org'] ?? null;
        $member->bio = $validated['bio'] ?? null;
        $member->skills = $validated['skills'] ?? null;
        $member->linkedin_url = $validated['linkedin_url'] ?? null;
        $member->github_url = $validated['github_url'] ?? null;
        $member->portfolio_url = $validated['portfolio_url'] ?? null;
        $member->save();

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}
