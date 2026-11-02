@extends('layouts.portal')

@section('title', 'Klinik Koding - Dev-Helpdesk HMIF')

@section('portal_content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Klinik Koding (Dev-Helpdesk)</h1>
            <p class="text-xs text-slate-400 mt-1">Konsultasikan kendala pemrograman, error sintaks, dan tugas akhir dengan peer-tutor pengurus & divisi riset HMIF.</p>
        </div>
        <button onclick="document.getElementById('ask-modal').classList.toggle('hidden')"
            class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-400 hover:to-cyan-400 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-md shadow-sky-500/20 flex items-center space-x-2">
            <span>+ Buat Tiket Pertanyaan Baru</span>
        </button>
    </div>

    <!-- Ask Modal -->
    <div id="ask-modal" class="hidden bg-slate-950 p-6 rounded-2xl border border-sky-500/40 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white">Ajukan Kendala Pemrograman Baru</h3>
            <button onclick="document.getElementById('ask-modal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xs">✕ Tutup</button>
        </div>

        <form action="{{ route('student.clinics.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Kendala / Error</label>
                <input type="text" name="title" required placeholder="Contoh: Error SQLSTATE[HY000] saat migration relasi foreign key"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori Kendala</label>
                    <select name="category" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="bug_error">Bug / Pesan Error</option>
                        <option value="tugas_kuliah">Tugas Praktikum Kuliah</option>
                        <option value="proyek_mandiri">Proyek Mandiri / Lomba</option>
                        <option value="tugas_akhir">Skripsi / Tugas Akhir</option>
                        <option value="general">Diskusi Arsitektur & Logika</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Bahasa / Framework</label>
                    <input type="text" name="programming_language" placeholder="Contoh: PHP / Laravel, Python, React, Java..."
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jelaskan Kendala Secara Rinci</label>
                <textarea name="description" rows="3" required placeholder="Apa yang ingin dicapai, langkah yang sudah dicoba, dan pesan error yang muncul..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Potongan Kode / Code Snippet (Opsional)</label>
                <textarea name="code_snippet" rows="4" placeholder="// Tempelkan baris kode yang bermasalah di sini..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sky-400 font-mono text-xs placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tangkapan Layar Error (Opsional)</label>
                <input type="file" name="screenshot" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('ask-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Kirimkan Tiket</button>
            </div>
        </form>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 flex flex-wrap gap-3">
        <a href="{{ route('student.clinics') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('status') ? 'bg-sky-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
            Semua Status
        </a>
        <a href="{{ route('student.clinics', ['status' => 'open']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'open' ? 'bg-sky-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
            🟡 Terbuka / Menunggu Jawaban
        </a>
        <a href="{{ route('student.clinics', ['status' => 'in_progress']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'in_progress' ? 'bg-sky-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
            🔵 Sedang Dimentori
        </a>
        <a href="{{ route('student.clinics', ['status' => 'resolved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'resolved' ? 'bg-sky-500 text-slate-950' : 'bg-slate-900 text-slate-400 hover:text-white' }}">
            🟢 Berhasil Terselesaikan
        </a>
    </div>

    <!-- Clinics List -->
    @if($clinics->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada tiket klinik koding pada status ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($clinics as $c)
                <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 hover:border-slate-700 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $c->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($c->status === 'in_progress' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                {{ $c->status === 'resolved' ? '✓ Terselesaikan' : ($c->status === 'in_progress' ? 'Sedang Dimentori' : 'Terbuka') }}
                            </span>
                            @if($c->programming_language)
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-900 text-slate-300 border border-slate-800">
                                    {{ $c->programming_language }}
                                </span>
                            @endif
                            <span class="text-[11px] text-slate-500">
                                {{ $c->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-white hover:text-sky-400 transition-colors">
                            <a href="{{ route('student.clinics.show', $c->id) }}">{{ $c->title }}</a>
                        </h3>

                        <div class="text-xs text-slate-400 flex items-center space-x-3">
                            <span>Ditanyakan oleh: <strong class="text-slate-300">{{ $c->user->name }}</strong></span>
                            <span>•</span>
                            <span>💬 {{ $c->replies->count() }} Jawaban Diskusi</span>
                            @if($c->mentor)
                                <span>•</span>
                                <span class="text-sky-400">Mentor: {{ $c->mentor->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('student.clinics.show', $c->id) }}"
                            class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-sky-400 font-semibold text-xs border border-slate-700 transition-colors block text-center whitespace-nowrap">
                            Buka Diskusi &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $clinics->links() }}
        </div>
    @endif

</div>
@endsection
