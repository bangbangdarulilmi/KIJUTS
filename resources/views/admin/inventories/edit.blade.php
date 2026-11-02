@extends('layouts.portal')

@section('title', 'Edit Aset - ' . $inventory->name)

@section('portal_content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('admin.inventories.index') }}" class="hover:underline">&larr; Kembali ke Daftar Inventaris</a>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl space-y-6">
        <div>
            <h1 class="text-lg font-bold text-white">Edit Aset Inventaris</h1>
            <p class="text-xs text-slate-400 mt-1">Perbarui kondisi fisik, jumlah unit, atau lokasi penyimpanan aset.</p>
        </div>

        <form action="{{ route('admin.inventories.update', $inventory->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kode Inventaris</label>
                    <input type="text" name="item_code" required value="{{ old('item_code', $inventory->item_code) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs font-mono placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Barang</label>
                    <input type="text" name="name" required value="{{ old('name', $inventory->name) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori Barang</label>
                    <input type="text" name="category" required value="{{ old('category', $inventory->category) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jumlah Unit</label>
                    <input type="number" name="quantity" required min="1" value="{{ old('quantity', $inventory->quantity) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kondisi Barang</label>
                    <select name="condition" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="good" {{ $inventory->condition === 'good' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                        <option value="needs_repair" {{ $inventory->condition === 'needs_repair' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="damaged" {{ $inventory->condition === 'damaged' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Lokasi Penyimpanan</label>
                <input type="text" name="location" required value="{{ old('location', $inventory->location) }}"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Ganti Foto Barang (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Catatan Tambahan</label>
                <textarea name="notes" rows="2"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('notes', $inventory->notes) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <a href="{{ route('admin.inventories.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Perbarui Aset</button>
            </div>
        </form>
    </div>
</div>
@endsection
