@extends('layouts.app')

@section('title', 'Masuk Portal HMIF STT Cipasung')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-slate-900 p-8 sm:p-10 rounded-2xl border border-slate-800 shadow-2xl shadow-slate-950/50">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-950 p-1.5 border border-slate-700 flex items-center justify-center shadow-xl shadow-sky-500/10">
                <img src="{{ asset('images/logo-hmif.png') }}" alt="Logo HMIF" class="w-full h-full object-contain">
            </div>
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-white">
                Masuk Portal HMIF
            </h2>
            <p class="mt-2 text-xs text-slate-400">
                Gunakan <span class="text-sky-400 font-medium">NIM Mahasiswa</span> atau <span class="text-sky-400 font-medium">Email Terdaftar</span> untuk mengakses layanan himpunan.
            </p>
        </div>

        <!-- Form Login -->
        <form class="mt-8 space-y-5" action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- NIM / Email Field -->
            <div>
                <label for="login_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    NIM atau Alamat Email
                </label>
                <div class="relative">
                    <input id="login_id" name="login_id" type="text" autocomplete="username" required
                        value="{{ old('login_id') }}"
                        placeholder="Contoh: 2101001 atau nama@sttcipasung.ac.id"
                        class="w-full px-4 py-2.5 bg-slate-950 border @error('login_id') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent text-sm">
                </div>
                @error('login_id')
                    <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Kata Sandi
                    </label>
                </div>
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 bg-slate-950 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent text-sm">
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-400 hover:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-sky-500 focus:ring-sky-500 focus:ring-offset-slate-900">
                    <span class="ml-2">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-sm tracking-wide shadow-lg shadow-sky-500/25 transition-all transform active:scale-[0.99] flex items-center justify-center space-x-2">
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>

        <!-- Footer / Register Link -->
        <div class="pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
            Belum memiliki akun mahasiswa?
            <a href="{{ route('register') }}" class="font-semibold text-sky-400 hover:text-sky-300 ml-1">
                Daftar sekarang &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
