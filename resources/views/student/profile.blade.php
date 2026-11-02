@extends('layouts.portal')

@section('title', 'Profil & Direktori Bakat')

@section('portal_content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div>
        <h1 class="text-xl font-bold text-white">Profil & Direktori Bakat Saya</h1>
        <p class="text-xs text-slate-400 mt-1">Lengkapi informasi keahlian, link GitHub, dan LinkedIn agar profil Anda mudah ditemukan rekan tim perlombaan atau tawaran proyek.</p>
    </div>

    <div class="bg-slate-950 p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-xl">
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Avatar & Overview -->
            <div class="flex items-center space-x-5 border-b border-slate-800 pb-6">
                <div class="w-16 h-16 rounded-2xl bg-slate-900 border border-slate-700 overflow-hidden flex items-center justify-center text-xl font-bold text-sky-400 flex-shrink-0">
                    @if($member->avatar)
                        <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Perbarui Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-sky-400 hover:file:bg-slate-800 cursor-pointer">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">NIM Mahasiswa</label>
                    <input type="text" disabled value="{{ $user->nim ?? '-' }}"
                        class="w-full px-3.5 py-2 bg-slate-900/50 border border-slate-800 rounded-xl text-slate-400 text-xs font-mono cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nomor WhatsApp / Kontak</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Angkatan Masuk</label>
                    <input type="text" name="generation" required value="{{ old('generation', $member->generation) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Keahlian & Spesialisasi (Pisahkan Koma)</label>
                <input type="text" name="skills" value="{{ old('skills', $member->skills) }}" placeholder="Contoh: Web Developer, UI/UX Designer, Laravel, Python, Machine Learning"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Bio Singkat</label>
                <textarea name="bio" rows="2" placeholder="Tuliskan perkenalan singkat mengenai minat teknologi atau riset Anda..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('bio', $member->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">URL LinkedIn</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" placeholder="https://linkedin.com/in/..."
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">URL GitHub</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $member->github_url) }}" placeholder="https://github.com/..."
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">URL Website / Portofolio</label>
                    <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $member->portfolio_url) }}" placeholder="https://mywebsite.com"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs transition-colors shadow-md shadow-sky-500/20">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
