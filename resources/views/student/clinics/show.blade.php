@extends('layouts.portal')

@section('title', $clinic->title . ' - Klinik Koding')

@section('portal_content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('student.clinics') }}" class="hover:underline">&larr; Kembali ke Daftar Klinik Koding</a>
    </div>

    <!-- Main Question Box -->
    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 space-y-5">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 pb-4">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $clinic->status === 'resolved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($clinic->status === 'in_progress' ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                    {{ $clinic->status === 'resolved' ? '✓ Masalah Terselesaikan' : ($clinic->status === 'in_progress' ? 'Sedang Dimentori' : 'Terbuka') }}
                </span>
                @if($clinic->programming_language)
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-900 text-slate-300 border border-slate-800">
                        {{ $clinic->programming_language }}
                    </span>
                @endif
                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-900 text-slate-400">
                    {{ str_replace('_', ' ', $clinic->category) }}
                </span>
            </div>
            <div class="text-xs text-slate-500">
                Diajukan {{ $clinic->created_at->translatedFormat('d F Y • H:i') }}
            </div>
        </div>

        <h1 class="text-xl sm:text-2xl font-bold text-white leading-snug">
            {{ $clinic->title }}
        </h1>

        <div class="flex items-center space-x-3 text-xs text-slate-400">
            <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center font-bold text-sky-400 text-xs">
                {{ strtoupper(substr($clinic->user->name, 0, 1)) }}
            </div>
            <div>
                <span class="font-semibold text-white">{{ $clinic->user->name }}</span>
                <span class="text-slate-500">({{ $clinic->user->nim ?? 'Mahasiswa' }})</span>
            </div>
            @if($clinic->mentor)
                <span>•</span>
                <span class="text-sky-400 font-semibold">Mentor Ditugaskan: {{ $clinic->mentor->name }}</span>
            @endif
        </div>

        <!-- Description -->
        <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line pt-2">
            {{ $clinic->description }}
        </div>

        <!-- Code Snippet if present -->
        @if($clinic->code_snippet)
            <div class="space-y-1 pt-2">
                <div class="text-[11px] font-mono text-slate-400">Potongan Kode Terkait:</div>
                <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 overflow-x-auto text-xs font-mono text-sky-300">
                    <pre><code>{{ $clinic->code_snippet }}</code></pre>
                </div>
            </div>
        @endif

        <!-- Screenshot if present -->
        @if($clinic->screenshot)
            <div class="space-y-1 pt-2">
                <div class="text-[11px] font-mono text-slate-400">Lampiran Tangkapan Layar:</div>
                <div class="rounded-xl overflow-hidden border border-slate-800 max-w-xl">
                    <img src="{{ asset('storage/' . $clinic->screenshot) }}" alt="Tangkapan layar error" class="w-full h-auto">
                </div>
            </div>
        @endif
    </div>

    <!-- Replies Header -->
    <div class="flex items-center justify-between pt-2">
        <h2 class="text-base font-bold text-white flex items-center space-x-2">
            <span>💬 Jawaban & Peer-Tutoring</span>
            <span class="px-2 py-0.5 rounded-full bg-slate-800 text-sky-400 text-xs">{{ $clinic->replies->count() }}</span>
        </h2>
    </div>

    <!-- Replies List -->
    @if($clinic->replies->isEmpty())
        <div class="bg-slate-950 p-8 rounded-2xl border border-slate-800 text-center text-xs text-slate-400">
            Belum ada tanggapan atau solusi untuk kendala ini. Jadilah yang pertama memberikan panduan!
        </div>
    @else
        <div class="space-y-4">
            @foreach($clinic->replies as $reply)
                <div class="p-6 rounded-2xl border transition-colors {{ $reply->is_solution ? 'bg-emerald-950/20 border-emerald-500/50' : 'bg-slate-950 border-slate-800' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center font-bold text-xs {{ $reply->user->isPengurus() ? 'text-sky-400 border border-sky-500/30' : 'text-slate-300' }}">
                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white">{{ $reply->user->name }}</span>
                                @if($reply->user->isPengurus())
                                    <span class="ml-1.5 px-2 py-0.2 rounded text-[10px] bg-sky-500/10 text-sky-400 font-semibold border border-sky-500/20 font-mono">
                                        Pengurus / Tutor
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <!-- Solution Badge / Mark Solution Button -->
                        <div>
                            @if($reply->is_solution)
                                <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 font-bold text-xs border border-emerald-500/40">
                                    <span>✓</span>
                                    <span>Solusi Terverifikasi</span>
                                </span>
                            @elseif(auth()->id() === $clinic->user_id || auth()->user()->isPengurus())
                                <form action="{{ route('student.clinics.solution', ['clinic' => $clinic->id, 'reply' => $reply->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-slate-400 hover:text-emerald-400 font-medium px-2 py-1 rounded bg-slate-900 hover:bg-slate-800 border border-slate-800 transition-colors">
                                        Tandai Solusi Ini
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="text-slate-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line pl-10">
                        {{ $reply->reply }}
                    </div>

                    @if($reply->code_snippet)
                        <div class="mt-3 pl-10">
                            <div class="bg-slate-900 p-3.5 rounded-xl border border-slate-800 overflow-x-auto text-xs font-mono text-emerald-300">
                                <pre><code>{{ $reply->code_snippet }}</code></pre>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Reply Form -->
    <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
        <h3 class="text-sm font-bold text-white">Tulis Jawaban / Masukan Solusi</h3>
        
        <form action="{{ route('student.clinics.reply', $clinic->id) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <textarea name="reply" rows="4" required placeholder="Tuliskan analisis atau panduan langkah perbaikan..."
                    class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Contoh Kode Solusi (Opsional)</label>
                <textarea name="code_snippet" rows="3" placeholder="// Contoh baris kode perbaikan yang benar..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-emerald-400 font-mono text-xs placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs transition-colors shadow-md shadow-sky-500/20">
                    Kirim Tanggapan Diskusi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
