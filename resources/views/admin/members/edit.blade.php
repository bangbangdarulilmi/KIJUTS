@extends('layouts.portal')

@section('title', 'Edit Anggota - ' . $member->name)

@section('portal_content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-2 text-xs text-sky-400 font-semibold uppercase">
        <a href="{{ route('admin.members.index') }}" class="hover:underline">&larr; Kembali ke Data Anggota</a>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl space-y-6">
        <div>
            <h1 class="text-lg font-bold text-white">Edit Data Anggota / Alumni</h1>
            <p class="text-xs text-slate-400 mt-1">Perbarui informasi data pokok keanggotaan HMIF.</p>
        </div>

        <form action="{{ route('admin.members.update', $member->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name', $member->name) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">NIM Mahasiswa</label>
                    <input type="text" name="nim" required value="{{ old('nim', $member->nim) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Angkatan</label>
                    <input type="text" name="generation" required value="{{ old('generation', $member->generation) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Status Keanggotaan</label>
                    <select name="status" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jabatan di Himpunan</label>
                    <input type="text" name="role_in_org" value="{{ old('role_in_org', $member->role_in_org) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Keahlian Khusus / Skills</label>
                <input type="text" name="skills" value="{{ old('skills', $member->skills) }}"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Ganti Foto Profil (Opsional)</label>
                <input type="file" name="avatar" accept="image/*"
                    class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">GitHub URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $member->github_url) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Bio Singkat</label>
                <textarea name="bio" rows="2" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('bio', $member->bio) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-3 border-t border-slate-800">
                <a href="{{ route('admin.members.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Perbarui Data Anggota</button>
            </div>
        </form>
    </div>
</div>
@endsection
