@extends('layouts.portal')

@section('title', 'Transparansi Kas Himpunan')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Transparansi Kas Himpunan (Financial Tracker)</h1>
            <p class="text-xs text-slate-400 mt-1">Pencatatan arus kas masuk & keluar terverifikasi guna akuntabilitas tata kelola keuangan HMIF.</p>
        </div>
        <button onclick="document.getElementById('cash-modal').classList.toggle('hidden')"
            class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-bold text-xs rounded-xl transition-all shadow-md shadow-emerald-500/20">
            + Catat Transaksi Kas Baru
        </button>
    </div>

    <!-- 3 Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Total Pemasukan Kas</div>
            <div class="text-2xl font-extrabold text-emerald-400 font-mono mt-2">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Iuran anggota, sponsor, donasi</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Total Pengeluaran Kas</div>
            <div class="text-2xl font-extrabold text-rose-400 font-mono mt-2">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Kegiatan, operasional & aset</div>
        </div>

        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800">
            <div class="text-xs text-slate-400">Saldo Akhir Tersedia</div>
            <div class="text-2xl font-extrabold font-mono mt-2 {{ $balance >= 0 ? 'text-sky-400' : 'text-rose-500' }}">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Akumulasi kas riil himpunan</div>
        </div>
    </div>

    <!-- Category Breakdown Visual Bars -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pemasukan Breakdown -->
        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
            <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center justify-between">
                <span>Distribusi Kategori Pemasukan</span>
                <span>Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
            </h3>
            @if($incomeCategories->isEmpty())
                <p class="text-xs text-slate-500 py-4">Belum ada pos pemasukan.</p>
            @else
                <div class="space-y-2 pt-1">
                    @foreach($incomeCategories as $ic)
                        @php
                            $perc = $totalIncome > 0 ? round(($ic->total / $totalIncome) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs text-slate-300 mb-1">
                                <span>{{ $ic->category }}</span>
                                <span class="font-mono text-emerald-400 font-semibold">Rp {{ number_format($ic->total, 0, ',', '.') }} ({{ $perc }}%)</span>
                            </div>
                            <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $perc }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pengeluaran Breakdown -->
        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
            <h3 class="text-xs font-bold text-rose-400 uppercase tracking-wider flex items-center justify-between">
                <span>Distribusi Kategori Pengeluaran</span>
                <span>Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
            </h3>
            @if($expenseCategories->isEmpty())
                <p class="text-xs text-slate-500 py-4">Belum ada pos pengeluaran.</p>
            @else
                <div class="space-y-2 pt-1">
                    @foreach($expenseCategories as $ec)
                        @php
                            $perc = $totalExpense > 0 ? round(($ec->total / $totalExpense) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs text-slate-300 mb-1">
                                <span>{{ $ec->category }}</span>
                                <span class="font-mono text-rose-400 font-semibold">Rp {{ number_format($ec->total, 0, ',', '.') }} ({{ $perc }}%)</span>
                            </div>
                            <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $perc }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Form Catat Kas -->
    <div id="cash-modal" class="hidden bg-slate-950 p-6 rounded-2xl border border-sky-500/40 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white">Form Pencatatan Transaksi Kas</h3>
            <button onclick="document.getElementById('cash-modal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xs">✕ Tutup</button>
        </div>

        <form action="{{ route('admin.cash.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jenis Transaksi</label>
                    <select name="type" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500 font-bold">
                        <option value="income" class="text-emerald-400">+ Pemasukan Kas</option>
                        <option value="expense" class="text-rose-400">- Pengeluaran Kas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kategori Pos</label>
                    <input type="text" name="category" required placeholder="Iuran Kas, Konsumsi, Transport, Aset..."
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nominal (Rupiah)</label>
                    <input type="number" name="amount" required min="1000" placeholder="100000"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Bukti Nota / Kuitansi (Opsional)</label>
                    <input type="file" name="proof_image" accept="image/*"
                        class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-sky-400 hover:file:bg-slate-700 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Uraian / Deskripsi Lengkap</label>
                <textarea name="description" rows="2" required placeholder="Rincian peruntukan dana transaksi kas..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('cash-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-400 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl">Catat Transaksi</button>
            </div>
        </form>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
        <form action="{{ route('admin.cash.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <select name="type" class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <option value="">Semua Jenis Arus Kas</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan Saja</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran Saja</option>
                </select>
            </div>
            <div>
                <input type="text" name="category" value="{{ request('category') }}" placeholder="Kategori pos..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <input type="month" name="month" value="{{ request('month') }}"
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs rounded-xl">Filter Laporan</button>
            </div>
        </form>
    </div>

    <!-- Ledger Table -->
    @if($transactions->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat transaksi kas yang sesuai.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Jenis</th>
                            <th class="px-6 py-4">Kategori Pos</th>
                            <th class="px-6 py-4">Uraian / Deskripsi</th>
                            <th class="px-6 py-4">Nominal</th>
                            <th class="px-6 py-4">Bukti / Nota</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($transactions as $t)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-slate-400">
                                    {{ $t->transaction_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-bold uppercase text-[10px] font-mono {{ $t->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $t->type === 'income' ? '+ Masuk' : '- Keluar' }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $t->category }}
                                </td>
                                <td class="px-6 py-4 text-slate-300 max-w-xs">
                                    {{ $t->description }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-xs {{ $t->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                    Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($t->proof_image)
                                        <a href="{{ asset('storage/' . $t->proof_image) }}" target="_blank" class="text-sky-400 hover:underline text-[11px] font-mono">
                                            Lihat Bukti ↗
                                        </a>
                                    @else
                                        <span class="text-slate-600 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.cash.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $transactions->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
