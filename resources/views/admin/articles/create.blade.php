@extends('layouts.portal')

@section('title', 'Tulis Artikel Baru')

@section('portal_content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('admin.articles.index') }}" class="hover:underline">&larr; Kembali ke CMS Artikel</a>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl space-y-6">
        <div>
            <h1 class="text-lg font-bold text-white">Tulis Berita / Artikel Baru</h1>
            <p class="text-xs text-slate-400 mt-1">Publikasi warta kegiatan himpunan atau edukasi teknologi.</p>
        </div>

        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Artikel</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Judul artikel yang menarik..."
                    class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori</label>
                    <select name="category_id" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">- Tanpa Kategori -</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Status Publikasi</label>
                    <select name="status" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="published" selected>Published (Langsung Terbit)</option>
                        <option value="draft">Draft (Konsep Sementara)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Gambar Sampul / Cover</label>
                <input type="file" name="cover_image" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Ringkasan / Excerpt</label>
                <textarea name="excerpt" rows="2" placeholder="Ringkasan 1-2 kalimat untuk kartu berita..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Konten Lengkap Artikel</label>
                <textarea name="content" rows="10" required placeholder="Tuliskan isi berita atau panduan teknologi selengkapnya..."
                    class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('content') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection
