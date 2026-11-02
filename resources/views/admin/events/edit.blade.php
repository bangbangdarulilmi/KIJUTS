@extends('layouts.portal')

@section('title', 'Edit Event - ' . $event->title)

@section('portal_content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('admin.events.show', $event->id) }}" class="hover:underline">&larr; Kembali ke Detail Event</a>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl space-y-6">
        <div>
            <h1 class="text-lg font-bold text-white">Edit Informasi Event</h1>
            <p class="text-xs text-slate-400 mt-1">Perbarui jadwal, kuota, atau deskripsi kegiatan.</p>
        </div>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Judul Kegiatan</label>
                <input type="text" name="title" required value="{{ old('title', $event->title) }}"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori</label>
                    <select name="category" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="workshop" {{ $event->category === 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="webinar" {{ $event->category === 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="makrab" {{ $event->category === 'makrab' ? 'selected' : '' }}>Makrab</option>
                        <option value="seminar" {{ $event->category === 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="lomba" {{ $event->category === 'lomba' ? 'selected' : '' }}>Lomba / Hackathon</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Status Kegiatan</label>
                    <select name="status" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="upcoming" {{ $event->status === 'upcoming' ? 'selected' : '' }}>Upcoming (Mendatang)</option>
                        <option value="ongoing" {{ $event->status === 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berjalan)</option>
                        <option value="completed" {{ $event->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Batas Kuota Peserta</label>
                    <input type="number" name="quota" value="{{ old('quota', $event->quota) }}" min="1"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Waktu Mulai</label>
                    <input type="datetime-local" name="event_date" required value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Waktu Berakhir</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Lokasi</label>
                    <input type="text" name="location" required value="{{ old('location', $event->location) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Format</label>
                    <div class="pt-2">
                        <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                            <input type="checkbox" name="is_online" value="1" {{ $event->is_online ? 'checked' : '' }}
                                class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-sky-500 focus:ring-sky-500">
                            <span>Format Online</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Ganti Banner (Opsional)</label>
                <input type="file" name="banner" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Deskripsi Kegiatan</label>
                <textarea name="description" rows="5" required
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <a href="{{ route('admin.events.show', $event->id) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
