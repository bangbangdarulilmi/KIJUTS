@extends('layouts.portal')

@section('title', 'Peminjaman Inventaris & Aset')

@section('portal_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Kelola Peminjaman Aset HMIF</h1>
            <p class="text-xs text-slate-400 mt-1">Pencatatan peminjaman, verifikasi pengembalian aset, dan tracking peminjam.</p>
        </div>
        <button onclick="document.getElementById('loan-modal').classList.toggle('hidden')"
            class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl transition-colors shadow-md shadow-sky-500/20">
            + Catat Peminjaman Baru
        </button>
    </div>

    <!-- Loan Modal -->
    <div id="loan-modal" class="hidden bg-slate-950 p-6 rounded-2xl border border-sky-500/40 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-sm font-bold text-white">Formulir Peminjaman Barang / Aset</h3>
            <button onclick="document.getElementById('loan-modal').classList.add('hidden')" class="text-slate-400 hover:text-white text-xs">✕ Tutup</button>
        </div>

        <form action="{{ route('admin.loans.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Pilih Barang</label>
                    <select name="inventory_id" required class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @foreach($inventories as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->name }} (Tersedia: {{ $inv->quantity }} unit)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Jumlah Unit Dipinjam</label>
                    <input type="number" name="quantity" required min="1" value="1"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nama Peminjam / Organisasi</label>
                    <input type="text" name="borrower_name" required placeholder="Nama Mahasiswa / Panitia Kegiatan"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Kontak WhatsApp Peminjam</label>
                    <input type="text" name="borrower_contact" required placeholder="08xxxxxxxxxx"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Tanggal Peminjaman</label>
                    <input type="date" name="loan_date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Target Pengembalian</label>
                    <input type="date" name="expected_return_date" required value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                        class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Keperluan / Keterangan</label>
                <textarea name="notes" rows="2" placeholder="Keperluan acara webinar, rapat komisi, seminar..."
                    class="w-full px-3.5 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="document.getElementById('loan-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-400 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-xl">Simpan Peminjaman</button>
            </div>
        </form>
    </div>

    <!-- Loans Table -->
    @if($loans->isEmpty())
        <div class="bg-slate-950 p-12 rounded-2xl border border-slate-800 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat peminjaman aset tercatat.</p>
        </div>
    @else
        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-900 text-slate-400 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Barang Dipinjam</th>
                            <th class="px-6 py-4">Peminjam & Kontak</th>
                            <th class="px-6 py-4">Tgl Pinjam & Tenggat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Verifikator</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($loans as $loan)
                            <tr class="hover:bg-slate-900/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-white">
                                    {{ $loan->inventory->name }}
                                    <span class="text-sky-400 font-mono text-[10px] block">({{ $loan->quantity }} unit)</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-white">{{ $loan->borrower_name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $loan->borrower_contact }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-400">
                                    <div>Pinjam: {{ $loan->loan_date->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-500">Tenggat: {{ $loan->expected_return_date->translatedFormat('d M Y') }}</div>
                                    @if($loan->actual_return_date)
                                        <div class="text-[10px] text-emerald-400">Kembali: {{ $loan->actual_return_date->translatedFormat('d M Y') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono {{ $loan->status === 'returned' ? 'bg-slate-800 text-slate-400' : ($loan->status === 'approved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($loan->status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400')) }}">
                                        {{ $loan->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-[11px]">
                                    {{ $loan->approver->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-1">
                                    @if($loan->status === 'approved')
                                        <form action="{{ route('admin.loans.update_status', $loan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="returned">
                                            <button type="submit" class="px-2 py-1 rounded bg-slate-800 hover:bg-emerald-600 text-slate-300 hover:text-white font-semibold text-[10px]">
                                                Konfirmasi Kembali
                                            </button>
                                        </form>
                                    @elseif($loan->status === 'pending')
                                        <form action="{{ route('admin.loans.update_status', $loan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2 py-1 rounded bg-emerald-600 text-white font-semibold text-[10px]">
                                                Setujui
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-800">
                {{ $loans->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
