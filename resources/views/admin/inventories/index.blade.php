@extends('layouts.portal')

@section('title', 'Manajemen Inventaris & Aset')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Manajemen Inventaris & Aset HMIF</h1>
            <p class="text-xs text-slate-400 mt-1">Pencatatan aset perangkat keras, kabel, audio visual, dan peminjaman fasilitas.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.loans.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs rounded-xl border border-slate-700">
                📋 Kelola Peminjaman
            </a>
            <a href="{{ route('admin.inventories.create') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
                + Tambah Aset Baru
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.inventories.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau kode inventaris..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="condition" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Kondisi</option>
                    <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                    <option value="needs_repair" {{ request('condition') == 'needs_repair' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="category" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter</button>
            </div>
        </form>
    </div>

    <!-- Inventories Table -->
    @if($inventories->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada barang inventaris yang terdata.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Kode Aset</th>
                            <th class="px-6 py-4">Nama Barang</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4">Kondisi</th>
                            <th class="px-6 py-4">Lokasi Simpan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($inventories as $inv)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-sky-400">
                                    {{ $inv->item_code }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $inv->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $inv->category }}
                                </td>
                                <td class="px-6 py-4 font-mono">
                                    <span class="text-white font-bold">{{ $inv->quantity }} Unit</span>
                                    @if($inv->active_loans_count > 0)
                                        <span class="text-amber-400 block text-[10px]">({{ $inv->active_loans_count }} dipinjam)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $inv->condition === 'good' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($inv->condition === 'needs_repair' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                        {{ str_replace('_', ' ', $inv->condition) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $inv->location }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.inventories.edit', $inv->id) }}" class="text-xs text-sky-400 hover:text-sky-300 font-medium">Edit</a>
                                    <form action="{{ route('admin.inventories.destroy', $inv->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus aset ini?')">
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
                {{ $inventories->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
