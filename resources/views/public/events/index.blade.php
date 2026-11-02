@extends('layouts.app')

@section('title', 'Agenda & Kegiatan - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="text-sky-400 font-bold text-xs uppercase tracking-widest">Agenda & Event</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Kegiatan HMIF STT Cipasung</h1>
        <p class="text-slate-400 text-sm mt-3">Ikuti berbagai workshop koding, seminar teknologi, kompetisi, dan kegiatan keakraban himpunan.</p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 shadow-sm">
            <form action="{{ route('public.events.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul kegiatan..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="w-full sm:w-48">
                    <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Kategori</option>
                        <option value="workshop" {{ request('category') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="webinar" {{ request('category') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="makrab" {{ request('category') == 'makrab' ? 'selected' : '' }}>Makrab</option>
                        <option value="seminar" {{ request('category') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="lomba" {{ request('category') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Event Cards Grid -->
        @if($events->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada kegiatan yang sesuai dengan pencarian Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="h-44 bg-slate-900 relative overflow-hidden flex items-center justify-center">
                                @if($event->banner)
                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-mono text-slate-700 text-2xl font-bold">&lt;HMIF /&gt;</span>
                                @endif
                                <div class="absolute top-3 left-3 bg-slate-950/80 text-sky-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border border-slate-800">
                                    {{ $event->category }}
                                </div>
                                <div class="absolute top-3 right-3 bg-slate-900/80 text-white text-[10px] font-medium px-2 py-0.5 rounded">
                                    {{ $event->is_online ? '🌐 Online' : '📍 ' . $event->location }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="text-xs font-semibold text-slate-500 mb-1">
                                    📅 {{ $event->event_date->translatedFormat('d F Y • H:i') }} WIB
                                </div>

                                <h3 class="text-base font-bold text-slate-900 hover:text-sky-600 transition-colors line-clamp-2">
                                    <a href="{{ route('public.events.show', $event->slug) }}">{{ $event->title }}</a>
                                </h3>

                                <p class="text-xs text-slate-600 mt-2 line-clamp-3">
                                    {{ strip_tags($event->description) }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('public.events.show', $event->slug) }}"
                                class="w-full block py-2.5 px-4 rounded-xl text-center bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-colors">
                                Lihat Detail & Daftar
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
