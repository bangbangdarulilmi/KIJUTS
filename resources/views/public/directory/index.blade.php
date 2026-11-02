@extends('layouts.app')

@section('title', 'Direktori Bakat & Alumni - HMIF STT Cipasung')

@section('content')
<div class="py-12 bg-slate-950 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
        <span class="text-sky-400 font-bold text-xs uppercase tracking-widest">Talent Directory & Network</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Direktori Bakat & Alumni HMIF</h1>
        <p class="text-slate-400 text-sm mt-3">Temukan talenta programmer, UI/UX designer, data analyst, dan profesional alumni Teknik Informatika STT Cipasung.</p>
    </div>
</div>

<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 shadow-sm">
            <form action="{{ route('public.directory') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIM, atau spesialisasi (UI/UX, Backend, Mobile)..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div class="w-full sm:w-40">
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Mahasiswa Aktif</option>
                        <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>
                <div class="w-full sm:w-36">
                    <select name="generation" class="w-full px-4 py-2 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Angkatan</option>
                        @foreach($generations as $gen)
                            <option value="{{ $gen }}" {{ request('generation') == $gen ? 'selected' : '' }}>{{ $gen }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-colors">
                    Filter
                </button>
            </form>
        </div>

        @if($members->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada data anggota yang sesuai dengan kriteria filter.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($members as $member)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between text-center">
                        <div>
                            <!-- Avatar -->
                            <div class="w-20 h-20 rounded-2xl mx-auto overflow-hidden bg-slate-900 border-2 border-slate-100 flex items-center justify-center font-bold text-sky-400 text-2xl shadow-inner mb-4">
                                @if($member->avatar)
                                    <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                @endif
                            </div>

                            <div class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-2 {{ $member->status === 'alumni' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800' }}">
                                {{ $member->status === 'alumni' ? 'Alumni HMIF' : 'Mahasiswa Aktif' }} ({{ $member->generation }})
                            </div>

                            <h3 class="font-bold text-slate-900 text-sm leading-snug">
                                {{ $member->name }}
                            </h3>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                NIM: {{ $member->nim }}
                            </div>

                            @if($member->role_in_org)
                                <div class="text-xs font-semibold text-sky-600 mt-1">
                                    {{ $member->role_in_org }}
                                </div>
                            @endif

                            @if($member->bio)
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                    {{ $member->bio }}
                                </p>
                            @endif

                            <!-- Skills Badges -->
                            @if($member->skills)
                                <div class="mt-3 flex flex-wrap justify-center gap-1">
                                    @foreach(explode(',', str_replace(['[', ']', '"'], '', $member->skills)) as $skill)
                                        @if(trim($skill))
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-medium">
                                                {{ trim($skill) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Social & Links -->
                        <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-center space-x-3 text-xs">
                            @if($member->github_url)
                                <a href="{{ $member->github_url }}" target="_blank" title="GitHub" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                    GitHub
                                </a>
                            @endif
                            @if($member->linkedin_url)
                                <a href="{{ $member->linkedin_url }}" target="_blank" title="LinkedIn" class="p-2 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 transition-colors">
                                    LinkedIn
                                </a>
                            @endif
                            @if($member->portfolio_url)
                                <a href="{{ $member->portfolio_url }}" target="_blank" title="Website Portofolio" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-colors">
                                    Portofolio
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $members->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
