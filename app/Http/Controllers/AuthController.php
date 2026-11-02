<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login_id'], FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';

        $remember = $request->boolean('remember');

        if (Auth::attempt([$field => $credentials['login_id'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['login_id' => 'Akun Anda sedang dinonaktifkan atau belum disetujui.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withInput($request->only('login_id', 'remember'))->withErrors([
            'login_id' => 'Kombinasi NIM / Email dan kata sandi tidak cocok.',
        ]);
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:30', 'unique:users,nim', 'unique:members,nim'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'generation' => ['required', 'string', 'max:10'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'email' => $validated['email'],
            'role' => 'mahasiswa',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        Member::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'name' => $validated['name'],
            'generation' => $validated['generation'],
            'status' => 'active',
            'skills' => '[]',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Portal HMIF STT Cipasung.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isPengurus()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}
