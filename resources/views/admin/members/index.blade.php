@extends('layouts.portal')

@section('title', 'Manajemen Anggota & Alumni')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Manajemen Anggota & Alumni HMIF</h1>
            <p class="text-xs text-slate-400 mt-1">Pendataan civitas aktif dan direktori alumni Teknik Informatika STT Cipasung.</p>
        </div>
        <a href="{{ route('admin.members.create') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
            + Tambah Anggota Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.members.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama, NIM, atau jabatan..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="generation" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Angkatan</option>
                    @foreach($generations as $gen)
                        <option value="{{ $gen }}" {{ request('generation') == $gen ? 'selected' : '' }}>{{ $gen }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    @if($members->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada data anggota yang cocok dengan filter.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">NIM</th>
                            <th class="px-6 py-4">Angkatan</th>
                            <th class="px-6 py-4">Jabatan Struktural</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($members as $m)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center font-bold text-sky-400 text-xs flex-shrink-0">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $m->name }}</span>
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px] text-slate-400">
                                    {{ $m->nim }}
                                </td>
                                <td class="px-6 py-4 font-mono">
                                    {{ $m->generation }}
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    {{ $m->role_in_org ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $m->status === 'active' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : ($m->status === 'alumni' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-400') }}">
                                        {{ $m->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.members.edit', $m->id) }}" class="text-xs text-sky-400 hover:text-sky-300 font-medium">Edit</a>
                                    <form action="{{ route('admin.members.destroy', $m->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data anggota ini?')">
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
                {{ $members->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
