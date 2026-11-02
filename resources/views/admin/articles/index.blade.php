@extends('layouts.portal')

@section('title', 'CMS Artikel & Berita')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">CMS Artikel & Warta Berita HMIF</h1>
            <p class="text-xs text-slate-400 mt-1">Publikasi warta kegiatan, pengumuman akademik, dan tulisan teknologi informatika.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="document.getElementById('cat-modal').classList.toggle('hidden')" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs rounded-xl border border-slate-700">
                + Tambah Kategori
            </button>
            <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
                + Tulis Artikel Baru
            </a>
        </div>
    </div>

    <!-- Quick Add Category Drawer -->
    <div id="cat-modal" class="hidden bg-slate-950 p-5 rounded-2xl border border-sky-500/40 shadow-xl space-y-3">
        <h3 class="text-xs font-bold text-white uppercase tracking-wider">Tambah Kategori Artikel Baru</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="name" required placeholder="Nama Kategori (misal: Riset & AI)"
                class="flex-1 px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            <input type="text" name="description" placeholder="Deskripsi singkat..."
                class="flex-1 px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            <button type="submit" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan Kategori</button>
            <button type="button" onclick="document.getElementById('cat-modal').classList.add('hidden')" class="px-3 py-2 bg-slate-800 text-slate-400 text-xs rounded-xl">Batal</button>
        </form>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Konsep)</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="category_id" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter</button>
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    @if($articles->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada artikel berita yang dibuat.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Judul Artikel</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Penulis</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Views</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($articles as $a)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    <div class="line-clamp-1">{{ $a->title }}</div>
                                    <div class="text-[10px] text-slate-500 font-normal mt-0.5">{{ $a->created_at->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-[10px] text-sky-400">
                                    {{ $a->category->name ?? 'Uncategorized' }}
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    {{ $a->author->name ?? 'Admin' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $a->status === 'published' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-400">
                                    {{ $a->views }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('public.articles.show', $a->slug) }}" target="_blank" class="text-xs text-sky-400 hover:text-sky-300 font-medium">Lihat ↗</a>
                                    <a href="{{ route('admin.articles.edit', $a->id) }}" class="text-xs text-slate-400 hover:text-slate-300 font-medium">Edit</a>
                                    <form action="{{ route('admin.articles.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
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
                {{ $articles->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
