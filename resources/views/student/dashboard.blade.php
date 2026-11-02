@extends('layouts.portal')

@section('title', 'Dashboard Mahasiswa')

@section('portal_content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-xl">
        <div>
            <div class="text-xs text-sky-400 font-mono">Halo, {{ auth()->user()->nim ?? 'Mahasiswa' }} 👋</div>
            <h1 class="text-2xl font-bold text-white mt-1">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-400 mt-1">Portal terpadu akademik, partisipasi kegiatan, dan komunitas Informatika STT Cipasung.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.events') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs transition-colors shadow-md shadow-sky-500/20">
                Lihat Kegiatan
            </a>
            <a href="{{ route('student.repository') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs border border-slate-700 transition-colors">
                Bank Soal
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Kegiatan Diikuti</div>
            <div class="text-2xl font-extrabold text-white font-mono mt-2">{{ $myRegistrations->count() }}</div>
            <div class="text-[11px] text-sky-400 mt-1">Event workshop & webinar</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">E-Sertifikat Dimiliki</div>
            <div class="text-2xl font-extrabold text-emerald-400 font-mono mt-2">{{ $certificatesCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Tervalidasi QR Code</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Tiket Klinik Koding</div>
            <div class="text-2xl font-extrabold text-cyan-400 font-mono mt-2">{{ $myCodingClinics->count() }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Konsultasi kendala program</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Karya Portofolio</div>
            <div class="text-2xl font-extrabold text-purple-400 font-mono mt-2">{{ $myProjects->count() }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Terbit di direktori</div>
        </div>
    </div>

    <!-- Quick Attendance Presensi Token Input Card -->
    <div class="bg-gradient-to-r from-sky-950/40 via-slate-950 to-slate-950 p-6 rounded-2xl border border-sky-500/30 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-sky-500/10 text-sky-400 text-[10px] font-bold uppercase tracking-wider border border-sky-500/20">
                <span>📲</span>
                <span>Presensi Cepat Kegiatan</span>
            </div>
            <h3 class="text-base font-bold text-white">Masukkan Token QR Presensi HMIF</h3>
            <p class="text-xs text-slate-400 max-w-xl">Jika panitia sedang membuka presensi kegiatan yang Anda ikuti, masukkan token kode QR yang tertera di layar untuk otomatis menerbitkan E-Sertifikat Anda.</p>
        </div>
        <form action="{{ route('student.presensi.submit') }}" method="POST" class="flex gap-2 w-full md:w-auto">
            @csrf
            <input type="text" name="qr_token" required placeholder="Token QR Presensi..."
                class="px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono w-48 sm:w-64">
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors whitespace-nowrap">
                Validasi
            </button>
        </form>
    </div>

    <!-- Two Columns: Recent Registrations & Academic Repo -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Kegiatan Saya -->
        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Kegiatan Terdaftar</h3>
                <a href="{{ route('student.events') }}" class="text-xs text-sky-400 hover:underline">Lihat Semua &rarr;</a>
            </div>

            @if($myRegistrations->isEmpty())
                <div class="py-8 text-center text-xs text-slate-500">
                    Anda belum mendaftar kegiatan apapun.
                    <a href="{{ route('public.events.index') }}" class="text-sky-400 block mt-1 hover:underline">Jelajahi agenda kegiatan &rarr;</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($myRegistrations as $reg)
                        <div class="p-3.5 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-white line-clamp-1">{{ $reg->event->title }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $reg->event->event_date->translatedFormat('d M Y, H:i') }} • Status: 
                                    <span class="font-semibold {{ $reg->attendance_status === 'present' ? 'text-emerald-400' : 'text-slate-400' }}">
                                        {{ ucfirst($reg->attendance_status) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                @if($reg->attendance_status === 'present' && $reg->certificate_code)
                                    <a href="{{ route('student.certificate.view', $reg->id) }}" class="px-2.5 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 font-semibold text-[11px] transition-colors">
                                        E-Sertifikat
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $reg->registration_code }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Repositori & Bank Soal Cepat -->
        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Materi & Bank Soal Terbaru</h3>
                <a href="{{ route('student.repository') }}" class="text-xs text-sky-400 hover:underline">Lihat Semua &rarr;</a>
            </div>

            @if($recentRepositories->isEmpty())
                <div class="py-8 text-center text-xs text-slate-500">
                    Belum ada materi atau bank soal diunggah.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentRepositories as $repo)
                        <div class="p-3.5 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between">
                            <div class="min-w-0 pr-3">
                                <div class="text-xs font-bold text-white line-clamp-1">{{ $repo->title }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Semester {{ $repo->semester }} • {{ $repo->course_name }}
                                </div>
                            </div>
                            <a href="{{ route('student.repository.download', $repo->id) }}"
                                class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-[11px] transition-colors whitespace-nowrap">
                                📥 Unduh
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
