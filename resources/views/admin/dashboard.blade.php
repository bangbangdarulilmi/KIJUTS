@extends('layouts.portal')

@section('title', 'Dashboard Panel Pengurus & Admin')

@section('portal_content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-xl">
        <div>
            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-sky-500/10 text-sky-400 text-[10px] font-bold uppercase tracking-wider border border-sky-500/20 mb-1">
                <span>🛡️</span>
                <span>{{ auth()->user()->role === 'super_admin' ? 'Super Admin Mode' : 'Pengurus Himpunan' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Dashboard Pengurus HMIF</h1>
            <p class="text-xs text-slate-400 mt-1">Ringkasan aktivitas operasional himpunan, status kegiatan, dan tata kelola administrasi terpadu.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.events.create') }}" class="px-3.5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
                + Buat Event
            </a>
            <a href="{{ route('admin.cash.index') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs border border-slate-700 rounded-xl transition-colors">
                + Catat Kas
            </a>
            <a href="{{ route('admin.members.create') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs border border-slate-700 rounded-xl transition-colors">
                + Tambah Anggota
            </a>
        </div>
    </div>

    <!-- 4 Key Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Anggota Aktif</div>
            <div class="text-2xl font-extrabold text-white font-mono mt-2">{{ $totalMembers }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Alumni terdata: {{ $totalAlumni }}</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Saldo Kas Himpunan</div>
            <div class="text-2xl font-extrabold font-mono mt-2 {{ $cashBalance >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($cashBalance, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Masuk: Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Event Mendatang</div>
            <div class="text-2xl font-extrabold text-sky-400 font-mono mt-2">{{ $upcomingEventsCount }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Agenda workshop & kegiatan</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Perlu Tindakan</div>
            <div class="flex items-center space-x-3 mt-2">
                <span class="text-xs font-mono px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20" title="Peminjaman Pending">
                    📦 {{ $pendingLoansCount }}
                </span>
                <span class="text-xs font-mono px-2 py-0.5 rounded bg-rose-500/10 text-rose-400 border border-rose-500/20" title="Aspirasi Belum Dibaca">
                    💬 {{ $unreadAspirationsCount }}
                </span>
                <span class="text-xs font-mono px-2 py-0.5 rounded bg-sky-500/10 text-sky-400 border border-sky-500/20" title="Klinik Koding Open">
                    💡 {{ $pendingClinicsCount }}
                </span>
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Aspirasi & peminjaman aset</div>
        </div>

    </div>

    <!-- Quick Approval: Pending Loans -->
    @if($pendingLoans->isNotEmpty())
        <div class="bg-amber-950/20 border border-amber-500/40 p-5 rounded-2xl space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                    <span>⚠️</span>
                    <span>Permohonan Peminjaman Aset Inventaris Menunggu Persetujuan</span>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="text-xs font-semibold text-amber-400 hover:underline">
                    Kelola Semua Peminjaman &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($pendingLoans as $loan)
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-white">{{ $loan->inventory->name }} ({{ $loan->quantity }} unit)</div>
                            <div class="text-[11px] text-slate-400 mt-1">Peminjam: <strong class="text-slate-300">{{ $loan->borrower_name }}</strong></div>
                            <div class="text-[10px] text-slate-500 font-mono mt-0.5">Tgl Pinjam: {{ $loan->loan_date->translatedFormat('d M Y') }}</div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <form action="{{ route('admin.loans.update_status', $loan->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="w-full py-1 text-center bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 text-[11px] font-semibold rounded-lg border border-emerald-500/30">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.loans.update_status', $loan->id) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="w-full py-1 text-center bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 text-[11px] font-semibold rounded-lg border border-rose-500/30">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2 Columns: Registrations & Cash Flow -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Pendaftaran Kegiatan Terbaru -->
        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Partisipasi & Pendaftaran Terbaru</h3>
                <a href="{{ route('admin.events.index') }}" class="text-xs text-sky-400 hover:underline">Kelola Event &rarr;</a>
            </div>

            @if($recentRegistrations->isEmpty())
                <div class="py-8 text-center text-xs text-slate-500">
                    Belum ada pendaftaran peserta kegiatan baru.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentRegistrations as $reg)
                        <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between">
                            <div class="min-w-0 pr-3">
                                <div class="text-xs font-bold text-white truncate">{{ $reg->user->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate">
                                    {{ $reg->event->title }} • <span class="font-mono">{{ $reg->user->nim ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($reg->attendance_status === 'present')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        ✓ Hadir
                                    </span>
                                @else
                                    <form action="{{ route('admin.events.mark_attendance', $reg->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="present">
                                        <button type="submit" class="px-2 py-1 rounded bg-slate-800 hover:bg-emerald-600 text-slate-300 hover:text-white font-semibold text-[10px] transition-colors">
                                            Validasi Presensi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Mutasi Kas Terbaru -->
        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Mutasi Kas Terakhir</h3>
                <a href="{{ route('admin.cash.index') }}" class="text-xs text-sky-400 hover:underline">Buku Kas Lengkap &rarr;</a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="py-8 text-center text-xs text-slate-500">
                    Belum ada transaksi kas yang dicatat.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentTransactions as $tx)
                        <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-white">{{ $tx->category }}</div>
                                <div class="text-[11px] text-slate-400">{{ $tx->description }} • {{ $tx->transaction_date->translatedFormat('d M Y') }}</div>
                            </div>
                            <div class="text-right font-mono font-bold text-xs {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
