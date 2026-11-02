@extends('layouts.app')

@section('title', 'Verifikasi Keaslian E-Sertifikat - HMIF STT Cipasung')

@section('content')
<div class="min-h-[80vh] py-16 px-4 flex items-center justify-center">
    <div class="max-w-xl w-full bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-xl text-center">
        
        @if($registration)
            <!-- Verified Header -->
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-3xl mb-4">
                ✓
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs uppercase tracking-wider mb-2">
                Sertifikat Asli & Terverifikasi
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">
                Verifikasi E-Sertifikat HMIF
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Dokumen ini resmi diterbitkan oleh Himpunan Mahasiswa Informatika STT Cipasung.
            </p>

            <div class="mt-8 bg-slate-50 p-6 rounded-xl border border-slate-200 text-left space-y-4">
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor Sertifikat</div>
                    <div class="font-mono font-bold text-sm text-sky-600">{{ $registration->certificate_code }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-200">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Peserta</div>
                        <div class="font-bold text-slate-900 text-sm">{{ $registration->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIM Mahasiswa</div>
                        <div class="font-mono text-slate-700 text-sm">{{ $registration->user->nim ?? '-' }}</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama Kegiatan</div>
                    <div class="font-bold text-slate-900 text-sm">{{ $registration->event->title }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Tanggal: {{ $registration->event->event_date->translatedFormat('d F Y') }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-200">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Kehadiran</div>
                        <div class="font-bold text-emerald-600 text-xs uppercase">Hadir (Terbukti Valid)</div>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Diterbitkan Pada</div>
                        <div class="text-slate-700 text-xs">{{ $registration->certificate_generated_at?->translatedFormat('d F Y • H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-xs text-slate-400">
                Sistem Validasi Kredensial Digital HMIF STT Cipasung
            </div>

        @else
            <!-- Invalid / Not Found -->
            <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-3xl mb-4">
                ✕
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-rose-100 text-rose-800 font-bold text-xs uppercase tracking-wider mb-2">
                Tidak Ditemukan
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900">
                Sertifikat Tidak Valid
            </h1>
            <p class="text-xs text-slate-500 mt-2">
                Kode sertifikat <span class="font-mono font-bold text-slate-700">{{ $code }}</span> tidak terdaftar dalam pangkalan data verifikasi resmi HMIF STT Cipasung.
            </p>
        @endif

        <div class="mt-8 pt-6 border-t border-slate-200">
            <a href="{{ route('home') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">
                &larr; Kembali ke Beranda Portal
            </a>
        </div>

    </div>
</div>
@endsection
