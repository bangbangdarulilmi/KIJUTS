@extends('layouts.app')

@section('title', 'Galeri Karya Mahasiswa - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="text-sky-400 font-bold text-xs uppercase tracking-widest">Showcase & Portofolio</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Karya & Proyek Mahasiswa</h1>
        <p class="text-slate-400 text-sm mt-3">Aplikasi web, mobile, sistem cerdas, dan repositori open-source inovasi civitas Teknik Informatika STT Cipasung.</p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 shadow-sm">
            <form action="{{ route('public.projects') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan judul, nama pengembang, atau tech stack (Laravel, Flutter, Python, dsb)..."
                    class="flex-1 px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                    Cari
                </button>
            </form>
        </div>

        @if($projects->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada karya proyek yang sesuai pencarian.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects as $project)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="h-44 bg-slate-900 overflow-hidden flex items-center justify-center relative">
                                @if($project->thumbnail)
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-mono text-slate-700 text-2xl font-bold">&lt;PROJECT/&gt;</span>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1">
                                    {{ $project->title }}
                                </h3>
                                <div class="text-xs text-sky-600 font-medium mt-1">
                                    Oleh: {{ $project->user->name }}
                                </div>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-3">
                                    {{ $project->description }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-1.5">
                                    @foreach(explode(',', $project->tech_stack) as $tech)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-mono font-medium">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0 flex gap-3 text-xs">
                            @if($project->repo_url)
                                <a href="{{ $project->repo_url }}" target="_blank"
                                    class="flex-1 py-2 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold transition-colors">
                                    GitHub Repo
                                </a>
                            @endif
                            @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank"
                                    class="flex-1 py-2 text-center rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold transition-colors">
                                    Live Demo
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
