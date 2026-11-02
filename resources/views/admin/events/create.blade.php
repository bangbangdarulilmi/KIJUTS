@extends('layouts.portal')

@section('title', 'Buat Event Baru')

@section('portal_content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('admin.events.index') }}" class="hover:underline">&larr; Kembali ke Daftar Event</a>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl space-y-6">
        <div>
            <h1 class="text-lg font-bold text-white">Buat Kegiatan / Event Baru</h1>
            <p class="text-xs text-slate-400 mt-1">Sistem akan secara otomatis membuat token QR Presensi unik untuk kegiatan ini.</p>
        </div>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Kegiatan</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Workshop Fullstack Web Development Modern"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori</label>
                    <select name="category" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="workshop">Workshop</option>
                        <option value="webinar">Webinar</option>
                        <option value="makrab">Makrab</option>
                        <option value="seminar">Seminar</option>
                        <option value="lomba">Lomba / Hackathon</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Status Kegiatan</label>
                    <select name="status" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="upcoming" selected>Upcoming (Mendatang)</option>
                        <option value="ongoing">Ongoing (Sedang Berjalan)</option>
                        <option value="completed">Completed (Selesai)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Batas Kuota Peserta (Opsional)</label>
                    <input type="number" name="quota" value="{{ old('quota') }}" placeholder="Misal: 100" min="1"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Waktu Mulai Pelaksanaan</label>
                    <input type="datetime-local" name="event_date" required value="{{ old('event_date') }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Waktu Berakhir (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Lokasi Kegiatan</label>
                    <input type="text" name="location" required value="{{ old('location') }}" placeholder="Contoh: Auditorium STT Cipasung / Ruang Lab Komputer"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Format</label>
                    <div class="pt-2">
                        <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="is_online" value="1" {{ old('is_online') ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-sky-500 focus:ring-sky-500">
                            <span>Kegiatan Online (Zoom/GMeet)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Banner Gambar Kegiatan</label>
                <input type="file" name="banner" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Uraian / Deskripsi Kegiatan</label>
                <textarea name="description" rows="5" required placeholder="Jelaskan silabus materi, narasumber, ketentuan peserta..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan & Terbitkan Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
