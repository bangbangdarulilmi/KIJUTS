@extends('layouts.portal')

@section('title', 'Aspirasi & Masukan Masuk')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Aspirasi & Masukan Masuk</h1>
            <p class="text-xs text-slate-400 mt-1">Daftar aspirasi, kritik, dan saran dari mahasiswa dan publik kepada himpunan.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.aspirations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Status</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Sudah Ditanggapi</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Kategori</option>
                    <option value="fasilitas">Fasilitas Lab / Kampus</option>
                    <option value="akademik">Akademik</option>
                    <option value="kegiatan_himpunan">Usulan Kegiatan</option>
                    <option value="kritik_saran">Kritik & Saran</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter Aspirasi</button>
            </div>
        </form>
    </div>

    @if($aspirations->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada aspirasi yang cocok dengan filter.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($aspirations as $a)
                <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-800 pb-3">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $a->status === 'unread' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}">
                                {{ $a->status === 'unread' ? 'Belum Ditanggapi' : 'Sudah Ditanggapi' }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-900 text-slate-300">
                                {{ $a->category }}
                            </span>
                            <span class="text-xs text-slate-500">
                                {{ $a->created_at->translatedFormat('d M Y • H:i') }}
                            </span>
                        </div>

                        <form action="{{ route('admin.aspirations.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus aspirasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-medium">Hapus</button>
                        </form>
                    </div>

                    <div>
                        <h3 class="text-base font-bold text-white">{{ $a->subject }}</h3>
                        <div class="text-xs text-slate-400 mt-0.5">
                            Pengirim: <strong class="text-slate-300">{{ $a->sender_name }}</strong> ({{ $a->sender_email }})
                        </div>
                    </div>

                    <div class="p-4 bg-slate-900 rounded-xl text-slate-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                        {{ $a->message }}
                    </div>

                    @if($a->response)
                        <div class="p-4 bg-emerald-950/20 border border-emerald-500/30 rounded-xl space-y-1">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                                Tanggapan Pengurus (Oleh: {{ $a->responder->name ?? 'Admin' }}):
                            </div>
                            <div class="text-xs text-emerald-300 whitespace-pre-line">
                                {{ $a->response }}
                            </div>
                        </div>
                    @endif

                    <!-- Reply Form Drawer -->
                    <details class="text-xs">
                        <summary class="cursor-pointer text-sky-400 hover:text-sky-300 font-semibold py-1">
                            {{ $a->response ? 'Perbarui Tanggapan ▾' : 'Tuliskan Tanggapan / Resolusi ▾' }}
                        </summary>
                        <form action="{{ route('admin.aspirations.reply', $a->id) }}" method="POST" class="mt-3 space-y-3 pt-3 border-t border-slate-800">
                            @csrf
                            <div>
                                <textarea name="response" rows="3" required placeholder="Tuliskan jawaban resmi himpunan..."
                                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('response', $a->response) }}</textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center space-x-2 text-slate-300 cursor-pointer">
                                    <input type="checkbox" name="is_public" value="1" {{ $a->is_public ? 'checked' : '' }}
                                        class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-sky-500">
                                    <span>Tampilkan aspirasi & jawaban ini di halaman publik</span>
                                </label>
                                <button type="submit" class="px-4 py-1.5 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">
                                    Simpan Tanggapan
                                </button>
                            </div>
                        </form>
                    </details>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $aspirations->links() }}
        </div>
    @endif
</div>
@endsection
