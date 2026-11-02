@extends('layouts.app')

@section('title', $event->title . ' - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase mb-3">
            <a href="{{ route('public.events.index') }}" class="hover:underline">Agenda</a>
            <span>/</span>
            <span>{{ $event->category }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight max-w-4xl">
            {{ $event->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-4 mt-4 text-xs text-slate-300">
            <span class="flex items-center space-x-1">
                <span>📅</span>
                <span>{{ $event->event_date->translatedFormat('d F Y, H:i') }} WIB</span>
            </span>
            <span class="flex items-center space-x-1">
                <span>📍</span>
                <span>{{ $event->location }}</span>
            </span>
            <span class="px-2 py-0.5 rounded bg-slate-800 text-sky-400 font-mono">
                Status: {{ ucfirst($event->status) }}
            </span>
        </div>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Left: Event Details -->
            <div class="lg:col-span-2 space-y-8">
                
                @if($event->banner)
                    <div class="rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="w-full h-auto max-h-96 object-cover">
                    </div>
                @endif

                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-3">Deskripsi Kegiatan</h2>
                    <div class="prose max-w-none text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                        {{ $event->description }}
                    </div>
                </div>

            </div>

            <!-- Right: Action Sidebar -->
            <div class="space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                    <h3 class="text-base font-bold text-slate-900">Pendaftaran Kegiatan</h3>

                    @if($event->quota)
                        <div>
                            <div class="flex justify-between text-xs text-slate-500 mb-1">
                                <span>Kuota Terisi</span>
                                <span class="font-bold text-slate-800">{{ $event->registrations()->count() }} / {{ $event->quota }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                @php
                                    $percent = min(100, round(($event->registrations()->count() / $event->quota) * 100));
                                @endphp
                                <div class="bg-sky-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endif

                    @auth
                        @if($userRegistration)
                            <!-- Already Registered -->
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <div class="flex items-center space-x-2 text-emerald-800 font-bold text-xs mb-1">
                                    <span>✓</span>
                                    <span>Anda Telah Terdaftar</span>
                                </div>
                                <div class="text-[11px] text-emerald-700">
                                    Kode Registrasi: <span class="font-mono font-bold">{{ $userRegistration->registration_code }}</span>
                                </div>
                                <div class="text-[11px] text-emerald-700 mt-1">
                                    Status Kehadiran: <span class="font-semibold uppercase">{{ $userRegistration->attendance_status }}</span>
                                </div>

                                @if($userRegistration->attendance_status === 'present' && $userRegistration->certificate_code)
                                    <div class="mt-3 pt-3 border-t border-emerald-200">
                                        <a href="{{ route('student.certificate.view', $userRegistration->id) }}"
                                            class="inline-flex items-center justify-center w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition-colors">
                                            Unduh / Lihat E-Sertifikat
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Presensi Box if open -->
                            @if($event->presensi_open && $userRegistration->attendance_status !== 'present')
                                <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl space-y-3">
                                    <div class="text-xs font-bold text-sky-900">
                                        📲 Presensi QR Kegiatan Sedang Dibuka!
                                    </div>
                                    <p class="text-[11px] text-sky-700 leading-normal">
                                        Masukkan kode token QR presensi yang ditampilkan panitia di layar/proyektor untuk memvalidasi kehadiran Anda.
                                    </p>
                                    <form action="{{ route('student.presensi.submit') }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="text" name="qr_token" required placeholder="Contoh: HMIF-QR-..."
                                            class="w-full px-3 py-2 text-xs border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono">
                                        <button type="submit"
                                            class="w-full py-2 px-3 rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs transition-colors">
                                            Kirim Presensi Kehadiran
                                        </button>
                                    </form>
                                </div>
                            @endif

                        @else
                            <!-- Not yet registered -->
                            @if($event->status === 'completed' || $event->status === 'cancelled')
                                <div class="p-3 bg-slate-100 rounded-xl text-center text-xs text-slate-500">
                                    Pendaftaran kegiatan telah ditutup.
                                </div>
                            @elseif($event->quota && $event->registrations()->count() >= $event->quota)
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-center text-xs text-amber-700">
                                    Kuota pendaftaran telah penuh.
                                </div>
                            @else
                                <form action="{{ route('student.events.register', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-sm tracking-wide shadow-lg shadow-sky-500/20 transition-all">
                                        Daftar Sekarang (Gratis)
                                    </button>
                                </form>
                            @endif
                        @endif
                    @else
                        <!-- Guest Prompt -->
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-center space-y-3">
                            <p class="text-xs text-slate-600">
                                Silakan masuk dengan akun mahasiswa STT Cipasung untuk mendaftar kegiatan ini dan memperoleh e-sertifikat.
                            </p>
                            <a href="{{ route('login') }}"
                                class="block w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-colors">
                                Masuk ke Akun Anda
                            </a>
                        </div>
                    @endauth

                    <div class="pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-2">
                        <div class="flex items-center space-x-2">
                            <span>🎓</span>
                            <span>E-Sertifikat otomatis ber-QR code</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>☕</span>
                            <span>Snack & Networking mahasiswa</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection
