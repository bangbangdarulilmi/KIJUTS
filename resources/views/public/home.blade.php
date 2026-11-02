@extends('layouts.app')

@section('title', 'Beranda - Himpunan Mahasiswa Informatika STT Cipasung')

@section('content')

<!-- ========================================== -->
<!-- 1. Hero Section -->
<!-- ========================================== -->
<section class="relative bg-slate-950 text-white overflow-hidden py-20 lg:py-28 border-b border-slate-800">
    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b15_1px,transparent_1px),linear-gradient(to_bottom,#1e293b15_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto space-y-6">
            
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-sky-400 text-xs font-semibold shadow-inner">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                </span>
                <span>Portal Resmi HMIF STT Cipasung • Versi 1.1</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                Membangun Generasi <br>
                <span class="bg-gradient-to-r from-sky-400 via-cyan-300 to-blue-500 bg-clip-text text-transparent">
                    Inovatif & Berdaya Saing Global
                </span>
            </h1>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal">
                Pusat terpadu informasi kegiatan, pengelolaan administrasi digital, bank soal akademik, dan etalase karya mahasiswa Teknik Informatika STT Cipasung.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <a href="{{ route('public.events.index') }}"
                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-sm tracking-wide shadow-lg shadow-sky-500/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                    <span>Jelajahi Kegiatan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                
                @auth
                    <a href="{{ auth()->user()->isPengurus() ? route('admin.dashboard') : route('student.dashboard') }}"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm border border-slate-700 transition-all flex items-center justify-center space-x-2">
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm border border-slate-700 transition-all flex items-center justify-center space-x-2">
                        <span>Masuk Portal</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </a>
                @endauth
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-12 border-t border-slate-800/80 text-left">
                <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800">
                    <div class="text-2xl font-black text-white font-mono">{{ $stats['members_count'] }}</div>
                    <div class="text-xs text-slate-400">Anggota Terdata</div>
                </div>
                <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800">
                    <div class="text-2xl font-black text-sky-400 font-mono">{{ $stats['events_count'] }}</div>
                    <div class="text-xs text-slate-400">Kegiatan Selesai</div>
                </div>
                <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800">
                    <div class="text-2xl font-black text-cyan-400 font-mono">{{ $stats['repo_count'] }}</div>
                    <div class="text-xs text-slate-400">Arsip & Bank Soal</div>
                </div>
                <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800">
                    <div class="text-2xl font-black text-emerald-400 font-mono">{{ $stats['projects_count'] }}</div>
                    <div class="text-xs text-slate-400">Karya Terbit</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- 2. Profil Himpunan: Visi Misi & Struktur -->
<!-- ========================================== -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-sky-600 font-bold text-xs uppercase tracking-widest">Tentang HMIF STT Cipasung</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Visi, Misi & Dedikasi Himpunan</h2>
            <p class="text-slate-600 text-sm mt-3">Mewujudkan iklim akademik yang kolaboratif, profesional, dan relevan dengan perkembangan industri rekayasa perangkat lunak.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Visi Card -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg mb-4">
                    🎯
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Visi Himpunan</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Menjadi organisasi kemahasiswaan berbasis teknologi informasi yang unggul, berintegritas tinggi, adaptif terhadap kemajuan sains global, serta berkontribusi nyata bagi kemaslahatan masyarakat dan institusi STT Cipasung.
                </p>
            </div>

            <!-- Misi Card -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold text-lg mb-4">
                    🚀
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Misi Utama</h3>
                <ul class="text-slate-600 text-sm space-y-2 leading-relaxed list-disc list-inside">
                    <li>Menyelenggarakan pelatihan teknologi intensif, workshop koding, dan riset aplikatif.</li>
                    <li>Membangun iklim akademik yang suportif melalui klinik koding dan bank soal terpadu.</li>
                    <li>Menjalin jejaring strategis dengan alumni, komunitas teknologi, dan dunia industri.</li>
                    <li>Menjaga tata kelola organisasi yang transparan, akuntabel, dan berbasis teknologi.</li>
                </ul>
            </div>
        </div>

        <!-- Struktur Organisasi Interaktif -->
        <div class="bg-slate-900 text-white rounded-2xl p-8 border border-slate-800 shadow-xl">
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold text-white">Struktur Pengurus Inti HMIF</h3>
                <p class="text-xs text-slate-400 mt-1">Kabinet Kolaborasi Teknologi Informatika STT Cipasung</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-800/60 p-5 rounded-xl border border-slate-700/60 text-center">
                    <div class="w-14 h-14 rounded-full bg-sky-500/20 text-sky-400 font-bold text-lg flex items-center justify-center mx-auto mb-3">
                        KA
                    </div>
                    <div class="font-bold text-sm text-white">Ketua Himpunan</div>
                    <div class="text-xs text-slate-400 mt-1">Koordinator Eksekutif HMIF</div>
                </div>

                <div class="bg-slate-800/60 p-5 rounded-xl border border-slate-700/60 text-center">
                    <div class="w-14 h-14 rounded-full bg-cyan-500/20 text-cyan-400 font-bold text-lg flex items-center justify-center mx-auto mb-3">
                        SE
                    </div>
                    <div class="font-bold text-sm text-white">Sekretaris Umum</div>
                    <div class="text-xs text-slate-400 mt-1">Administrasi & Tata Dokumen</div>
                </div>

                <div class="bg-slate-800/60 p-5 rounded-xl border border-slate-700/60 text-center">
                    <div class="w-14 h-14 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-lg flex items-center justify-center mx-auto mb-3">
                        BE
                    </div>
                    <div class="font-bold text-sm text-white">Bendahara Umum</div>
                    <div class="text-xs text-slate-400 mt-1">Manajemen Kas & Keuangan</div>
                </div>

                <div class="bg-slate-800/60 p-5 rounded-xl border border-slate-700/60 text-center">
                    <div class="w-14 h-14 rounded-full bg-purple-500/20 text-purple-400 font-bold text-lg flex items-center justify-center mx-auto mb-3">
                        MD
                    </div>
                    <div class="font-bold text-sm text-white">Divisi Medinfo & Riset</div>
                    <div class="text-xs text-slate-400 mt-1">Publikasi, Riset & Portal Web</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ========================================== -->
<!-- 3. Berita & Agenda Kegiatan (Countdown) -->
<!-- ========================================== -->
<section class="py-20 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-sky-600 font-bold text-xs uppercase tracking-widest">Agenda Mendatang</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Kegiatan & Workshop Terdekat</h2>
            </div>
            <a href="{{ route('public.events.index') }}" class="mt-4 md:mt-0 text-sm font-semibold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                <span>Lihat Semua Kegiatan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($upcomingEvents->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada agenda kegiatan mendatang yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <!-- Banner placeholder/image -->
                            <div class="h-44 bg-slate-900 relative overflow-hidden flex items-center justify-center">
                                @if($event->banner)
                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-slate-600 text-3xl font-bold font-mono">&lt;EVENT /&gt;</div>
                                @endif
                                <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-sm text-sky-400 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md border border-slate-800">
                                    {{ $event->category }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="text-xs font-semibold text-slate-500 mb-1 flex items-center space-x-1">
                                    <span>📅</span>
                                    <span>{{ $event->event_date->translatedFormat('d F Y • H:i') }} WIB</span>
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 leading-snug hover:text-sky-600 transition-colors">
                                    <a href="{{ route('public.events.show', $event->slug) }}">{{ $event->title }}</a>
                                </h3>

                                <p class="text-xs text-slate-600 mt-2 line-clamp-2">
                                    {{ Str::limit(strip_tags($event->description), 100) }}
                                </p>

                                <!-- Countdown Timer Display -->
                                <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                    <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Hitung Mundur Acara</div>
                                    <div class="font-mono text-xs font-bold text-sky-600 countdown-timer" data-time="{{ $event->event_date->toIso8601String() }}">
                                        Menghitung...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('public.events.show', $event->slug) }}"
                                class="w-full block py-2.5 px-4 rounded-xl text-center bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-colors">
                                Detail & Pendaftaran
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

<!-- ========================================== -->
<!-- 4. Galeri Karya & Project Mahasiswa -->
<!-- ========================================== -->
<section class="py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-sky-600 font-bold text-xs uppercase tracking-widest">Talenta & Inovasi</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Galeri Karya Mahasiswa Informatika</h2>
            </div>
            <a href="{{ route('public.projects') }}" class="mt-4 md:mt-0 text-sm font-semibold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                <span>Lihat Semua Proyek</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if($featuredProjects->isEmpty())
            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-sm text-slate-500">Belum ada karya mahasiswa yang terdaftar di etalase publik.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProjects as $project)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="h-36 bg-slate-900 flex items-center justify-center relative overflow-hidden">
                                @if($project->thumbnail)
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="font-mono text-slate-700 text-2xl font-bold">&lt;CODE /&gt;</div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-slate-900 text-sm hover:text-sky-600 transition-colors line-clamp-1">
                                    {{ $project->title }}
                                </h4>
                                <div class="text-[11px] text-slate-500 font-medium mt-1">
                                    Oleh: {{ $project->user->name }}
                                </div>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-2">
                                    {{ $project->short_description }}
                                </p>
                                <div class="mt-3 flex flex-wrap gap-1">
                                    @foreach(explode(',', $project->tech_stack) as $tech)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-mono">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="p-5 pt-0 flex items-center space-x-2 text-xs">
                            @if($project->repo_url)
                                <a href="{{ $project->repo_url }}" target="_blank" class="flex-1 text-center py-1.5 px-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition-colors">
                                    GitHub
                                </a>
                            @endif
                            @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" class="flex-1 text-center py-1.5 px-2 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-700 font-medium transition-colors">
                                    Demo
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

<!-- ========================================== -->
<!-- 5. Berita Terkini & Form Aspirasi / Kontak -->
<!-- ========================================== -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Berita & Artikel Terkini -->
            <div>
                <span class="text-sky-600 font-bold text-xs uppercase tracking-widest">Informasi Terkini</span>
                <h2 class="text-2xl font-extrabold text-slate-900 mt-1 mb-8">Berita & Pengumuman Himpunan</h2>

                @if($latestArticles->isEmpty())
                    <p class="text-xs text-slate-500 py-6">Belum ada artikel berita yang dipublikasikan.</p>
                @else
                    <div class="space-y-6">
                        @foreach($latestArticles as $article)
                            <article class="flex space-x-4 group">
                                <div class="w-24 h-24 rounded-xl bg-slate-900 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    @if($article->cover_image)
                                        <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    @else
                                        <span class="text-slate-700 font-mono text-sm">&lt;NEWS/&gt;</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[11px] font-semibold text-sky-600 uppercase tracking-wider">
                                        {{ $article->category->name ?? 'Kegiatan' }} • {{ $article->created_at->translatedFormat('d M Y') }}
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-sky-600 transition-colors line-clamp-2 mt-1">
                                        <a href="{{ route('public.articles.show', $article->slug) }}">{{ $article->title }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                        {{ $article->excerpt }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Form Aspirasi & Kontak Publik -->
            <div class="bg-slate-900 text-white p-8 sm:p-10 rounded-2xl border border-slate-800 shadow-xl">
                <div>
                    <span class="text-sky-400 font-bold text-xs uppercase tracking-widest">Kritik, Saran & Aspirasi</span>
                    <h2 class="text-2xl font-extrabold text-white mt-1">Sampaikan Aspirasi Anda</h2>
                    <p class="text-xs text-slate-400 mt-2">Kanal resmi penyampaian aspirasi akademik, fasilitas laboratorium, maupun ide kegiatan langsung ke Pengurus HMIF STT Cipasung.</p>
                </div>

                <form action="{{ route('public.aspiration.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Pengirim</label>
                            <input type="text" name="sender_name" required value="{{ old('sender_name', auth()->user()?->name) }}"
                                placeholder="Nama Anda"
                                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Email Aktif</label>
                            <input type="email" name="sender_email" required value="{{ old('sender_email', auth()->user()?->email) }}"
                                placeholder="email@anda.com"
                                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori</label>
                            <select name="category" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs">
                                <option value="fasilitas">Fasilitas Lab / Kampus</option>
                                <option value="akademik">Akademik & Perkuliahan</option>
                                <option value="kegiatan_himpunan">Usulan Kegiatan HMIF</option>
                                <option value="kritik_saran" selected>Kritik & Saran</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Subjek Pesan</label>
                            <input type="text" name="subject" required value="{{ old('subject') }}"
                                placeholder="Judul ringkas aspirasi"
                                class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Uraian Pesan / Aspirasi</label>
                        <textarea name="message" rows="3" required placeholder="Tuliskan aspirasi atau masukan Anda dengan jelas..."
                            class="w-full px-3.5 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors shadow-lg shadow-sky-500/25">
                        Kirimkan Aspirasi
                    </button>
                </form>
            </div>

        </div>

    </div>
</section>

<!-- Countdown Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const timerElements = document.querySelectorAll('.countdown-timer');

        function updateTimers() {
            const now = new Date().getTime();

            timerElements.forEach(el => {
                const targetTime = new Date(el.getAttribute('data-time')).getTime();
                const difference = targetTime - now;

                if (difference <= 0) {
                    el.innerHTML = '<span class="text-emerald-500">Acara Sedang / Telah Berlangsung</span>';
                    return;
                }

                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                el.textContent = `${days}h ${hours}j ${minutes}m ${seconds}d`;
            });
        }

        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>

@endsection
