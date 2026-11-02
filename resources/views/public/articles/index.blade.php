@extends('layouts.app')

@section('title', 'Berita & Artikel Teknologi - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="text-sky-400 font-bold text-xs uppercase tracking-widest">Warta & Publikasi</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Berita & Artikel HMIF</h1>
        <p class="text-slate-400 text-sm mt-3">Publikasi kegiatan himpunan, pengumuman akademik, dan artikel perkembangan sains teknologi.</p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 shadow-sm">
            <form action="{{ route('public.articles.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul atau topik artikel..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="w-full sm:w-48">
                    <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                    Filter
                </button>
            </form>
        </div>

        @if($articles->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada artikel berita yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($articles as $article)
                    <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="h-48 bg-slate-900 overflow-hidden relative flex items-center justify-center">
                                @if($article->cover_image)
                                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-mono text-slate-700 text-2xl font-bold">&lt;NEWS/&gt;</span>
                                @endif
                                <div class="absolute top-3 left-3 bg-slate-950/80 text-sky-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border border-slate-800">
                                    {{ $article->category->name ?? 'Warta' }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="text-[11px] font-medium text-slate-400 mb-2">
                                    {{ $article->created_at->translatedFormat('d F Y') }} • {{ $article->views }} views
                                </div>
                                <h3 class="text-base font-bold text-slate-900 hover:text-sky-600 transition-colors line-clamp-2">
                                    <a href="{{ route('public.articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('public.articles.show', $article->slug) }}"
                                class="text-xs font-semibold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                                <span>Baca Selengkapnya</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
