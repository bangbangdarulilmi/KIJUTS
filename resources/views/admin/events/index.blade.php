@extends('layouts.portal')

@section('title', 'Manajemen Event & Presensi')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Manajemen Event & Presensi QR</h1>
            <p class="text-xs text-slate-400 mt-1">Pembuatan kegiatan, monitor pendaftaran, buka/tutup presensi QR, dan rekap sertifikat.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
            + Buat Event Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.events.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul event..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Mendatang</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="category" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Kategori</option>
                    <option value="workshop">Workshop</option>
                    <option value="webinar">Webinar</option>
                    <option value="makrab">Makrab</option>
                    <option value="seminar">Seminar</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter</button>
            </div>
        </form>
    </div>

    <!-- Events Table -->
    @if($events->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada kegiatan yang cocok dengan kriteria filter.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Judul Kegiatan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Pelaksanaan</th>
                            <th class="px-6 py-4">Pendaftar / Kuota</th>
                            <th class="px-6 py-4">Presensi QR</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($events as $event)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    <a href="{{ route('admin.events.show', $event->id) }}" class="hover:text-sky-400 transition-colors">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 uppercase font-mono text-[10px] text-sky-400">
                                    {{ $event->category }}
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $event->event_date->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 font-mono">
                                    <span class="text-emerald-400 font-bold">{{ $event->present_count }} Hadir</span> / 
                                    <span class="text-white">{{ $event->registrations_count }} Terdaftar</span>
                                    @if($event->quota)
                                        <span class="text-slate-500">({{ $event->quota }} maks)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.events.toggle_presensi', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition-colors {{ $event->presensi_open ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-slate-800 text-slate-500' }}">
                                            {{ $event->presensi_open ? '🟢 Dibuka' : '⚪ Ditutup' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase {{ $event->status === 'upcoming' ? 'bg-sky-500/10 text-sky-400' : ($event->status === 'completed' ? 'bg-slate-800 text-slate-400' : 'bg-amber-500/10 text-amber-400') }}">
                                        {{ $event->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.events.show', $event->id) }}" class="text-xs text-sky-400 hover:text-sky-300 font-medium">Buka</a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="text-xs text-slate-400 hover:text-slate-300 font-medium">Edit</a>
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $events->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
