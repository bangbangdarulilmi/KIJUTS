@extends('layouts.app')

@section('title', 'Pendaftaran Mahasiswa - Portal HMIF STT Cipasung')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8 bg-slate-900 p-8 sm:p-10 rounded-2xl border border-slate-800 shadow-2xl shadow-slate-950/50">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-950 p-1.5 border border-slate-700 flex items-center justify-center shadow-xl shadow-sky-500/10">
                <img src="{{ asset('images/logo-hmif.png') }}" alt="Logo HMIF" class="w-full h-full object-contain">
            </div>
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-white">
                Pendaftaran Anggota Mahasiswa
            </h2>
            <p class="mt-2 text-xs text-slate-400">
                Daftarkan akun portal resmi Teknik Informatika STT Cipasung untuk mengakses bank soal, e-sertifikat, dan klinik koding.
            </p>
        </div>

        <!-- Form Register -->
        <form class="mt-8 space-y-4" action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Nama Lengkap
                </label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                    placeholder="Contoh: Muhammad Rizki"
                    class="w-full px-4 py-2 bg-slate-950 border @error('name') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                @error('name')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIM & Angkatan (2 Cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nim" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        NIM (Nomor Induk)
                    </label>
                    <input id="nim" name="nim" type="text" required value="{{ old('nim') }}"
                        placeholder="Contoh: 2101001"
                        class="w-full px-4 py-2 bg-slate-950 border @error('nim') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                    @error('nim')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="generation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Angkatan Masuk
                    </label>
                    <select id="generation" name="generation" required
                        class="w-full px-4 py-2 bg-slate-950 border @error('generation') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                        @for($year = date('Y'); $year >= 2018; $year--)
                            <option value="{{ $year }}" {{ old('generation') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    @error('generation')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email Kampus / Pribadi -->
            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Alamat Email Aktif
                </label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                    placeholder="nama@email.com atau @sttcipasung.ac.id"
                    class="w-full px-4 py-2 bg-slate-950 border @error('email') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                @error('email')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password & Confirmation (2 Cols) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Kata Sandi
                    </label>
                    <input id="password" name="password" type="password" required
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2 bg-slate-950 border @error('password') border-rose-500 @else border-slate-700 @enderror rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Konfirmasi Sandi
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-sm tracking-wide shadow-lg shadow-sky-500/25 transition-all transform active:scale-[0.99] flex items-center justify-center space-x-2">
                    <span>Daftar Akun Portal</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>

        <!-- Footer / Login Link -->
        <div class="pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
            Sudah memiliki akun portal?
            <a href="{{ route('login') }}" class="font-semibold text-sky-400 hover:text-sky-300 ml-1">
                Masuk di sini &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
