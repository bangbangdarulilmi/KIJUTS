@extends('layouts.portal')

@section('title', 'Karya & Portofolio Koding Saya')

@section('portal_content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Karya & Portofolio Saya</h1>
            <p class="text-xs text-slate-400 mt-1">Publikasikan karya aplikasi dan repositori open-source Anda agar tampil di galeri publik mahasiswa HMIF.</p>
        </div>
        <button onclick="document.getElementById('project-modal').classList.toggle('hidden')"
            class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-md shadow-sky-500/20 flex items-center space-x-2">
            <span>+ Tambah Karya Baru</span>
        </button>
    </div>

    <!-- Modal Tambah Karya -->
    <div id="project-modal" class="hidden bg-slate-950 p-6 rounded-2xl border border-sky-500/40 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white">Tambah Karya Baru ke Showcase</h3>
            <button onclick="document.getElementById('project-modal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xs">✕ Tutup</button>
        </div>

        <form action="{{ route('student.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Karya / Aplikasi</label>
                    <input type="text" name="title" required placeholder="Contoh: Sistem Presensi QR Mobile"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tech Stack (Pisahkan dengan Koma)</label>
                    <input type="text" name="tech_stack" required placeholder="Contoh: Flutter, Firebase, Python"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Deskripsi Singkat (Satu Kalimat)</label>
                <input type="text" name="short_description" required placeholder="Contoh: Aplikasi mobile pelacakan kehadiran berbasis geofencing dan QR Code dinamis."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tautan GitHub / Repositori</label>
                    <input type="url" name="repo_url" placeholder="https://github.com/username/project"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tautan Live Demo / APK</label>
                    <input type="url" name="demo_url" placeholder="https://myproject.com atau link drive"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Gambar Cuplikan Layar / Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Deskripsi Lengkap & Fitur</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan arsitektur, latar belakang proyek, dan teknologi yang digunakan..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('project-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan & Publikasikan</button>
            </div>
        </form>
    </div>

    <!-- Projects List -->
    @if($projects->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Anda belum mendaftarkan karya proyek koding.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $p)
                <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="h-36 bg-slate-900 overflow-hidden flex items-center justify-center">
                            @if($p->thumbnail)
                                <img src="{{ asset('storage/' . $p->thumbnail) }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-mono text-slate-700 font-bold text-xl">&lt;PROJECT/&gt;</span>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="font-bold text-white text-sm line-clamp-1">{{ $p->title }}</h3>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $p->short_description }}</p>
                            
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach(explode(',', $p->tech_stack) as $t)
                                    <span class="px-2 py-0.5 rounded bg-slate-900 text-slate-400 text-[10px] font-mono border border-slate-800">
                                        {{ trim($t) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="p-5 pt-0 flex gap-2 text-xs">
                        @if($p->repo_url)
                            <a href="{{ $p->repo_url }}" target="_blank" class="flex-1 py-1.5 text-center bg-slate-900 hover:bg-slate-800 text-slate-300 rounded-lg border border-slate-800">
                                GitHub
                            </a>
                        @endif
                        @if($p->demo_url)
                            <a href="{{ $p->demo_url }}" target="_blank" class="flex-1 py-1.5 text-center bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 rounded-lg border border-sky-500/30">
                                Demo
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
@endsection
