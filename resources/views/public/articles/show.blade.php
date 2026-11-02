@extends('layouts.app')

@section('title', $article->title . ' - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase mb-3">
            <a href="{{ route('public.articles.index') }}" class="hover:underline">Berita</a>
            <span>/</span>
            <span>{{ $article->category->name ?? 'Kegiatan' }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
            {{ $article->title }}
        </h1>
        <div class="flex items-center space-x-4 mt-4 text-xs text-slate-400">
            <span>Ditulis oleh: <strong class="text-white">{{ $article->author->name ?? 'Admin HMIF' }}</strong></span>
            <span>•</span>
            <span>{{ $article->published_at?->translatedFormat('d F Y, H:i') ?? $article->created_at->translatedFormat('d F Y') }} WIB</span>
            <span>•</span>
            <span>{{ $article->views }} Kali Dilihat</span>
        </div>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <article class="bg-white p-8 sm:p-12 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            
            @if($article->cover_image)
                <div class="rounded-xl overflow-hidden">
                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[450px] object-cover">
                </div>
            @endif

            <div class="prose max-w-none text-slate-800 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                {{ $article->content }}
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <a href="{{ route('public.articles.index') }}" class="inline-flex items-center space-x-1 font-semibold text-sky-600 hover:text-sky-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Indeks Berita</span>
                </a>
            </div>
        </article>

    </div>
</div>
@endsection
