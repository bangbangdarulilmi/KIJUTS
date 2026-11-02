@extends('layouts.portal')

@section('title', 'Detail Event - ' . $event->title)

@section('portal_content')
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
            <a href="{{ route('admin.events.index') }}" class="hover:underline">&larr; Kembali ke Daftar Event</a>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.events.edit', $event->id) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs border border-slate-700">
                Edit Event
            </a>
            <a href="{{ route('public.events.show', $event->slug) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs border border-slate-700">
                Lihat di Web Publik ↗
            </a>
        </div>
    </div>

    <!-- Event Banner & Controls Header -->
    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 border-b border-slate-800 pb-6">
            <div class="space-y-2 max-w-2xl">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono bg-sky-500/10 text-sky-400 border border-sky-500/20">
                        {{ $event->category }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono bg-slate-900 text-slate-300 border border-slate-800">
                        {{ $event->status }}
                    </span>
                    <span class="text-xs text-slate-400">
                        {{ $event->event_date->translatedFormat('d F Y • H:i') }} WIB
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-white leading-snug">{{ $event->title }}</h1>
                <div class="text-xs text-slate-400">Lokasi: <span class="text-white">{{ $event->location }}</span> ({{ $event->is_online ? 'Online' : 'Tatap Muka' }})</div>
            </div>

            <!-- Quick Presensi Toggle Box -->
            <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 text-center space-y-3 min-w-[220px]">
                <div class="text-xs text-slate-400 font-medium">Status Presensi Kehadiran</div>
                <div class="text-sm font-bold {{ $event->presensi_open ? 'text-emerald-400' : 'text-slate-500' }}">
                    {{ $event->presensi_open ? '🟢 SEDANG DIBUKA' : '⚪ SEDANG DITUTUP' }}
                </div>
                <form action="{{ route('admin.events.toggle_presensi', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 rounded-lg font-bold text-xs transition-colors {{ $event->presensi_open ? 'bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 border border-rose-500/30' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-md shadow-emerald-500/20' }}">
                        {{ $event->presensi_open ? 'Tutup Presensi' : 'Buka Presensi Sekarang' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- 2 Columns: QR Display for Screen / Projector & Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: QR Code Screen Display Card -->
            <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 text-center space-y-4">
                <div class="text-xs font-bold uppercase tracking-wider text-sky-400">
                    📲 QR Presensi untuk Proyektor / Layar
                </div>
                
                <div class="w-48 h-48 mx-auto bg-white p-3 rounded-2xl shadow-xl flex items-center justify-center">
                    {!! $qrSvg !!}
                </div>

                <div class="space-y-1">
                    <div class="text-[10px] text-slate-400 uppercase font-mono">Token Kode Presensi:</div>
                    <div class="text-sm font-mono font-bold text-sky-300 bg-slate-950 py-1.5 px-3 rounded-lg border border-slate-800 select-all">
                        {{ $event->qr_presensi_token }}
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 leading-normal">
                    Tampilkan kode QR ini atau bagikan token kepada peserta di ruangan untuk presensi mandiri dan otomatisasi e-sertifikat.
                </p>
            </div>

            <!-- Right: Stats & Participants Summary -->
            <div class="lg:col-span-2 space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                        <div class="text-[11px] text-slate-400">Total Terdaftar</div>
                        <div class="text-xl font-bold font-mono text-white mt-1">{{ $event->registrations->count() }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Kuota: {{ $event->quota ?? 'Unlimited' }}</div>
                    </div>
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                        <div class="text-[11px] text-slate-400">Hadir Terbukti</div>
                        <div class="text-xl font-bold font-mono text-emerald-400 mt-1">
                            {{ $event->registrations->where('attendance_status', 'present')->count() }}
                        </div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Sudah presensi</div>
                    </div>
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                        <div class="text-[11px] text-slate-400">Sertifikat Terbit</div>
                        <div class="text-xl font-bold font-mono text-sky-400 mt-1">
                            {{ $event->registrations->whereNotNull('certificate_code')->count() }}
                        </div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Otomatisasi 100%</div>
                    </div>
                </div>

                <!-- Rekap Peserta Table -->
                <div class="bg-slate-900 rounded-xl border border-slate-800 overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-bold text-white uppercase tracking-wider">Daftar Peserta & Presensi</h3>
                        <span class="text-xs text-slate-400 font-mono">{{ $event->registrations->count() }} Peserta</span>
                    </div>

                    @if($event->registrations->isEmpty())
                        <div class="p-8 text-center text-xs text-slate-500">
                            Belum ada peserta yang mendaftar pada event ini.
                        </div>
                    @else
                        <div class="overflow-x-auto max-h-96">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] font-semibold sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3">Nama Peserta</th>
                                        <th class="px-4 py-3">NIM</th>
                                        <th class="px-4 py-3">Status Hadir</th>
                                        <th class="px-4 py-3">No. Sertifikat</th>
                                        <th class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800">
                                    @foreach($event->registrations as $r)
                                        <tr class="hover:bg-slate-950/40">
                                            <td class="px-4 py-3 font-semibold text-white">
                                                {{ $r->user->name }}
                                            </td>
                                            <td class="px-4 py-3 font-mono text-[11px] text-slate-400">
                                                {{ $r->user->nim ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($r->attendance_status === 'present')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                        ✓ Hadir
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400">
                                                        Terdaftar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono text-[10px] text-sky-400">
                                                {{ $r->certificate_code ?? '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('admin.events.mark_attendance', $r->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @if($r->attendance_status === 'present')
                                                        <input type="hidden" name="status" value="registered">
                                                        <button type="submit" class="text-[10px] text-slate-400 hover:text-amber-400 font-semibold">
                                                            Batalkan Hadir
                                                        </button>
                                                    @else
                                                        <input type="hidden" name="status" value="present">
                                                        <button type="submit" class="px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] shadow-sm">
                                                            Validasi Hadir
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
