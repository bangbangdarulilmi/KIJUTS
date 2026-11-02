<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Portal HMIF STT Cipasung</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 antialiased min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex-shrink-0 flex flex-col justify-between hidden md:flex sticky top-0 h-screen">
        <div>
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-sky-500 to-cyan-400 flex items-center justify-center font-extrabold text-slate-950 text-sm">
                        &lt;/&gt;
                    </div>
                    <div>
                        <span class="text-sm font-bold tracking-tight text-white block">HMIF Portal</span>
                        <span class="text-[10px] text-sky-400 font-mono tracking-wider uppercase">{{ auth()->user()->role }}</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="p-4 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)]">
                
                @if(auth()->user()->isPengurus())
                    <!-- Admin / Pengurus Navigation -->
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-3 mb-2">Panel Pengurus</div>
                        <nav class="space-y-1 text-sm font-medium">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>📊</span>
                                <span>Dashboard Utama</span>
                            </a>
                            <a href="{{ route('admin.members.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.members.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>👥</span>
                                <span>Anggota & Alumni</span>
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.events.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>📅</span>
                                <span>Event & Presensi QR</span>
                            </a>
                            <a href="{{ route('admin.articles.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>📰</span>
                                <span>CMS Artikel & Berita</span>
                            </a>
                            <a href="{{ route('admin.inventories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.inventories.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>📦</span>
                                <span>Inventaris & Aset</span>
                            </a>
                            <a href="{{ route('admin.loans.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.loans.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>📋</span>
                                <span>Peminjaman Aset</span>
                            </a>
                            <a href="{{ route('admin.cash.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.cash.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>💰</span>
                                <span>Transparansi Kas</span>
                            </a>
                            <a href="{{ route('admin.aspirations.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.aspirations.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                <span>💬</span>
                                <span>Aspirasi Masuk</span>
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                                    <span>🔐</span>
                                    <span>Manajemen User (Admin)</span>
                                </a>
                            @endif
                        </nav>
                    </div>
                @endif

                <!-- Mahasiswa Navigation (Accessible to everyone logged in) -->
                <div>
                    <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider px-3 mb-2">Portal Mahasiswa</div>
                    <nav class="space-y-1 text-sm font-medium">
                        <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>🏠</span>
                            <span>Dashboard Saya</span>
                        </a>
                        <a href="{{ route('student.events') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.events*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>🎫</span>
                            <span>Event & E-Sertifikat</span>
                        </a>
                        <a href="{{ route('student.repository') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.repository*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>📚</span>
                            <span>Bank Soal & Materi</span>
                        </a>
                        <a href="{{ route('student.clinics') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.clinics*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>💡</span>
                            <span>Klinik Koding Helpdesk</span>
                        </a>
                        <a href="{{ route('student.projects') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.projects*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>🚀</span>
                            <span>Portofolio Karya</span>
                        </a>
                        <a href="{{ route('student.profile') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('student.profile*') ? 'bg-sky-500/10 text-sky-400 font-semibold border-l-2 border-sky-400' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                            <span>👤</span>
                            <span>Profil & Direktori Bakat</span>
                        </a>
                    </nav>
                </div>

                <!-- Back to Landing Page -->
                <div class="pt-2 border-t border-slate-800">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-slate-400 hover:text-sky-400 hover:bg-slate-900 text-sm font-medium transition-colors">
                        <span>🌐</span>
                        <span>Lihat Website Publik</span>
                    </a>
                </div>

            </div>
        </div>

        <!-- Sidebar Footer: User Profile Card -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/80">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-sky-400 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-slate-400 font-mono">{{ auth()->user()->nim ?? auth()->user()->email }}</div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen bg-slate-900 text-slate-200">
        
        <!-- Mobile Header Bar -->
        <header class="md:hidden h-14 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-4 sticky top-0 z-40">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-sky-500 flex items-center justify-center text-slate-950 font-bold text-xs">&lt;/&gt;</div>
                <span class="font-bold text-white text-sm">HMIF Portal</span>
            </a>
            <div class="flex items-center space-x-2 text-xs">
                @if(auth()->user()->isPengurus())
                    <a href="{{ route('admin.dashboard') }}" class="px-2 py-1 bg-slate-800 text-sky-400 rounded">Admin</a>
                @endif
                <a href="{{ route('student.dashboard') }}" class="px-2 py-1 bg-slate-800 text-slate-300 rounded">Student</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-2 py-1 bg-rose-500/20 text-rose-400 rounded">Keluar</button>
                </form>
            </div>
        </header>

        <!-- Flash messages in portal -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border-b border-emerald-500/30 text-emerald-400 px-6 py-3 text-xs font-medium flex items-center justify-between">
                <div><span class="font-bold mr-1">Sukses:</span> {{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border-b border-rose-500/30 text-rose-400 px-6 py-3 text-xs font-medium flex items-center justify-between">
                <div><span class="font-bold mr-1">Perhatian:</span> {{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-sky-500/10 border-b border-sky-500/30 text-sky-400 px-6 py-3 text-xs font-medium flex items-center justify-between">
                <div><span class="font-bold mr-1">Info:</span> {{ session('info') }}</div>
            </div>
        @endif

        <div class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            @yield('portal_content')
        </div>
    </div>

</body>
</html>
