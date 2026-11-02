@extends('layouts.portal')

@section('title', 'Event & E-Sertifikat Saya')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Event & E-Sertifikat Saya</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar kegiatan yang Anda ikuti beserta riwayat kehadiran dan sertifikat digital.</p>
        </div>
        <a href="{{ route('public.events.index') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
            Jelajahi Agenda Baru &rarr;
        </a>
    </div>

    @if($registrations->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Anda belum mendaftar kegiatan apapun.</p>
            <a href="{{ route('public.events.index') }}" class="mt-2 text-xs font-semibold text-sky-400 inline-block hover:underline">
                Daftar kegiatan sekarang &rarr;
            </a>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama Kegiatan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Tanggal Pelaksanaan</th>
                            <th class="px-6 py-4">Kode Registrasi</th>
                            <th class="px-6 py-4">Kehadiran</th>
                            <th class="px-6 py-4 text-right">E-Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($registrations as $reg)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    <a href="{{ route('public.events.show', $reg->event->slug) }}" class="hover:text-sky-400 transition-colors">
                                        {{ $reg->event->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 uppercase text-[10px] font-mono text-sky-400">
                                    {{ $reg->event->category }}
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $reg->event->event_date->translatedFormat('d M Y, H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-400">
                                    {{ $reg->registration_code }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->attendance_status === 'present')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            ✓ Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400">
                                            Terdaftar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($reg->attendance_status === 'present' && $reg->certificate_code)
                                        <a href="{{ route('student.certificate.view', $reg->id) }}"
                                            class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-[11px] transition-colors shadow-sm">
                                            <span>Unduh Sertifikat</span>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-600">Presensi Belum Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $registrations->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
