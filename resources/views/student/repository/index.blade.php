@extends('layouts.portal')

@section('title', 'Bank Soal & Repositori Akademik')

@section('portal_content')
<div class="space-y-6">
    
    <!-- Header with Upload Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Bank Soal & Repositori Akademik</h1>
            <p class="text-xs text-slate-400 mt-1">Arsip modul praktikum, contoh kodingan, dan soal UTS/UAS Teknik Informatika STT Cipasung.</p>
        </div>
        <button onclick="document.getElementById('upload-modal').classList.toggle('hidden')"
            class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-md shadow-sky-500/20 flex items-center space-x-2">
            <span>+ Unggah Berkas Baru</span>
        </button>
    </div>

    <!-- Upload Modal / Drawer -->
    <div id="upload-modal" class="hidden bg-slate-950 p-6 rounded-2xl border border-sky-500/40 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white">Unggah Berkas ke Repositori Himpunan</h3>
            <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xs">✕ Tutup</button>
        </div>

        <form action="{{ route('student.repository.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Dokumen / Soal</label>
                    <input type="text" name="title" required placeholder="Contoh: Soal UTS Basis Data 2024"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Mata Kuliah</label>
                    <input type="text" name="course_name" required placeholder="Contoh: Algoritma & Pemrograman"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tipe Berkas</label>
                    <select name="type" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="bank_soal">Bank Soal (UTS / UAS)</option>
                        <option value="modul_praktikum">Modul Praktikum Lab</option>
                        <option value="source_code">Source Code / Kodingan</option>
                        <option value="catatan_kuliah">Catatan & Rangkuman Kuliah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Semester</label>
                    <select name="semester" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}">Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori Ujian (Jika Ada)</label>
                    <select name="exam_type" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">- Tidak Ada -</option>
                        <option value="uts">UTS (Ujian Tengah Semester)</option>
                        <option value="uas">UAS (Ujian Akhir Semester)</option>
                        <option value="kuis">Kuis</option>
                        <option value="tugas">Tugas Besar</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Pilih Berkas (PDF, ZIP, DOCX, Maks 20MB)</label>
                <input type="file" name="file" required
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Keterangan / Catatan Singkat</label>
                <textarea name="description" rows="2" placeholder="Informasi dosen pengampu, materi yang diujikan, dll..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Mulai Unggah</button>
            </div>
        </form>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('student.repository') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama mata kuliah atau judul..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <select name="semester" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Semester</option>
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex gap-2">
                <select name="type" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Tipe</option>
                    <option value="bank_soal" {{ request('type') == 'bank_soal' ? 'selected' : '' }}>Bank Soal</option>
                    <option value="modul_praktikum" {{ request('type') == 'modul_praktikum' ? 'selected' : '' }}>Modul Praktikum</option>
                    <option value="source_code" {{ request('type') == 'source_code' ? 'selected' : '' }}>Source Code</option>
                    <option value="catatan_kuliah" {{ request('type') == 'catatan_kuliah' ? 'selected' : '' }}>Catatan Kuliah</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter</button>
            </div>
        </form>
    </div>

    <!-- Repositories Grid -->
    @if($repositories->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada materi atau berkas di repositori untuk kriteria ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($repositories as $item)
                <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 flex flex-col justify-between hover:border-slate-700 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                Sem {{ $item->semester }} • {{ str_replace('_', ' ', $item->type) }}
                            </span>
                            @if($item->exam_type)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    {{ strtoupper($item->exam_type) }}
                                </span>
                            @endif
                        </div>

                        <h3 class="font-bold text-white text-sm line-clamp-2">
                            {{ $item->title }}
                        </h3>
                        <div class="text-xs text-slate-400 mt-1 font-medium">
                            {{ $item->course_name }}
                        </div>

                        @if($item->description)
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                {{ $item->description }}
                            </p>
                        @endif

                        <div class="mt-4 pt-3 border-t border-slate-900 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Ukuran: {{ $item->file_size ?? '-' }}</span>
                            <span>{{ $item->download_count }}x diunduh</span>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                        <div class="text-[10px] text-slate-500">
                            Oleh: {{ $item->uploader->name ?? 'HMIF' }}
                        </div>
                        <a href="{{ route('student.repository.download', $item->id) }}"
                            class="px-3.5 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs transition-colors flex items-center space-x-1 shadow-sm">
                            <span>Unduh Berkas</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $repositories->links() }}
        </div>
    @endif

</div>
@endsection
