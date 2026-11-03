<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Web Portal HMIF STT Cipasung') - Himpunan Mahasiswa Informatika</title>
    <meta name="description" content="Pusat informasi, administrasi digital, dan kolaborasi mahasiswa Teknik Informatika STT Cipasung.">

    <link rel="icon" type="image/png" href="{{ asset('images/logo-hmif.png') }}">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 p-0.5 border border-sky-500/30 flex items-center justify-center shadow-lg shadow-sky-500/20 group-hover:scale-105 transition-transform overflow-hidden">
                            <img src="{{ asset('images/logo-hmif.png') }}" alt="Logo HMIF STT Cipasung" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="text-lg font-bold tracking-tight text-white group-hover:text-sky-400 transition-colors">HMIF</span>
                            <span class="text-xs block text-slate-400 -mt-1 font-medium">STT Cipasung</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 font-medium text-sm">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-sky-400 bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">Beranda</a>
                    <a href="{{ route('public.events.index') }}" class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('public.events.*') ? 'text-sky-400 bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">Agenda & Event</a>
                    <a href="{{ route('public.articles.index') }}" class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('public.articles.*') ? 'text-sky-400 bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">Berita & Artikel</a>
                    <a href="{{ route('public.projects') }}" class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('public.projects') ? 'text-sky-400 bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">Karya Mahasiswa</a>
                    <a href="{{ route('public.directory') }}" class="px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('public.directory') ? 'text-sky-400 bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">Direktori Bakat</a>
                </nav>

                <!-- Auth Buttons / User Avatar -->
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->isPengurus())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs border border-sky-500/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Panel Pengurus</span>
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs border border-sky-500/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span>Portal Mahasiswa</span>
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-slate-400 hover:text-rose-400 font-medium px-2 py-1 transition-colors">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-950 font-semibold text-sm transition-all shadow-md shadow-sky-500/25">
                            Daftar Portal
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border-b border-emerald-500/20 text-emerald-800 px-4 py-3 text-sm text-center font-medium">
            <span class="inline-block mr-1">✓</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-500/10 border-b border-rose-500/20 text-rose-800 px-4 py-3 text-sm text-center font-medium">
            <span class="inline-block mr-1">✕</span> {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-sky-500/10 border-b border-sky-500/20 text-sky-800 px-4 py-3 text-sm text-center font-medium">
            <span class="inline-block mr-1">ℹ</span> {{ session('info') }}
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-900 mt-20 pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-900">
                
                <!-- Col 1: About HMIF -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-900 p-0.5 border border-slate-700 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/logo-hmif.png') }}" alt="Logo HMIF STT Cipasung" class="w-full h-full object-contain">
                        </div>
                        <span class="text-white font-bold text-lg tracking-tight">HMIF STTC</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Himpunan Mahasiswa Informatika Sekolah Tinggi Teknologi Cipasung. Wadah akselerasi potensi, inovasi teknologi, dan kolaborasi civitas akademika.
                    </p>
                    <div class="text-xs text-slate-500">
                        Jl. Pesantren KH. Ruhiat, Cipakat, Kec. Singaparna, Kabupaten Tasikmalaya, Jawa Barat 46417
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h4 class="text-sm font-semibold text-white tracking-wide uppercase mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-sky-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('public.events.index') }}" class="hover:text-sky-400 transition-colors">Agenda & Kegiatan</a></li>
                        <li><a href="{{ route('public.articles.index') }}" class="hover:text-sky-400 transition-colors">Berita & Artikel</a></li>
                        <li><a href="{{ route('public.projects') }}" class="hover:text-sky-400 transition-colors">Galeri Karya Mahasiswa</a></li>
                        <li><a href="{{ route('public.directory') }}" class="hover:text-sky-400 transition-colors">Direktori Bakat & Alumni</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan Mahasiswa -->
                <div>
                    <h4 class="text-sm font-semibold text-white tracking-wide uppercase mb-4">Layanan Mahasiswa</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('student.repository') }}" class="hover:text-sky-400 transition-colors">Bank Soal & Modul Praktikum</a></li>
                        <li><a href="{{ route('student.clinics') }}" class="hover:text-sky-400 transition-colors">Klinik Koding (Dev-Helpdesk)</a></li>
                        <li><a href="{{ route('student.events') }}" class="hover:text-sky-400 transition-colors">E-Sertifikat Terverifikasi</a></li>
                        <li><a href="{{ route('student.profile') }}" class="hover:text-sky-400 transition-colors">Portofolio Koding</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Medsos -->
                <div>
                    <h4 class="text-sm font-semibold text-white tracking-wide uppercase mb-4">Sosial & Komunikasi</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li>Email: <a href="mailto:hmif@sttcipasung.ac.id" class="text-sky-400 hover:underline">hmif@sttcipasung.ac.id</a></li>
                        <li>Instagram: <a href="https://instagram.com" target="_blank" class="text-sky-400 hover:underline">@hmif_sttcipasung</a></li>
                        <li>GitHub: <a href="https://github.com" target="_blank" class="text-sky-400 hover:underline">github.com/hmif-sttcipasung</a></li>
                    </ul>
                    <div class="mt-4 pt-3 border-t border-slate-900">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-slate-900 text-sky-400 text-[10px] font-mono">
                            v1.1 Modern Tech Minimalist
                        </span>
                    </div>
                </div>

            </div>

            <div class="pt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} HMIF STT Cipasung. Hak Cipta Dilindungi Undang-Undang. Dibangun dengan Laravel & Tailwind CSS.
            </div>
        </div>
    </footer>

</body>
</html>
