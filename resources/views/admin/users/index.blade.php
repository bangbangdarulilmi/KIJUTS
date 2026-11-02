@extends('layouts.portal')

@section('title', 'Manajemen Pengguna Sistem')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/10 text-purple-400 text-[10px] font-bold uppercase tracking-wider border border-purple-500/20 mb-1">
                <span>🔐</span>
                <span>Super Admin Privilege</span>
            </div>
            <h1 class="text-xl font-bold text-white">Manajemen Akun & Hak Akses (RBAC)</h1>
            <p class="text-xs text-slate-400 mt-1">Konfigurasi hak akses peranan (Super Admin, Pengurus HMIF, Mahasiswa Aktif) dan status akun.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIM..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="role" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Peranan</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="pengurus" {{ request('role') == 'pengurus' ? 'selected' : '' }}>Pengurus HMIF</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa Aktif</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter User</button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    @if($users->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Tidak ada user yang sesuai kriteria.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama User</th>
                            <th class="px-6 py-4">NIM / Email</th>
                            <th class="px-6 py-4">Peran (Role)</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Terdaftar</th>
                            <th class="px-6 py-4 text-right">Ubah Hak Akses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $u->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-slate-300 text-[11px]">{{ $u->nim ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $u->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $u->role === 'super_admin' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : ($u->role === 'pengurus' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-slate-800 text-slate-400') }}">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $u->status === 'active' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $u->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-[11px]">
                                    {{ $u->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.users.update_role', $u->id) }}" method="POST" class="inline-flex items-center space-x-2">
                                        @csrf
                                        <select name="role" class="bg-slate-900 border border-slate-700 text-slate-200 text-[10px] rounded px-2 py-1">
                                            <option value="mahasiswa" {{ $u->role === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                            <option value="pengurus" {{ $u->role === 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                            <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                        <select name="status" class="bg-slate-900 border border-slate-700 text-slate-200 text-[10px] rounded px-2 py-1">
                                            <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $u->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <button type="submit" class="px-2.5 py-1 rounded bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-[10px]">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
