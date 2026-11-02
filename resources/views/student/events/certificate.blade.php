<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sertifikat - {{ $registration->user->name }} - {{ $registration->certificate_code }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Cinzel:wght@600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-container {
                box-shadow: none !important;
                border: 2px solid #0f172a !important;
                page-break-inside: avoid;
            }
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
    </style>
</head>
<body class="bg-slate-950 min-h-screen py-10 px-4 flex flex-col items-center justify-center font-sans antialiased text-slate-800">

    <!-- Print Action Bar -->
    <div class="no-print max-w-4xl w-full flex items-center justify-between mb-6 bg-slate-900 p-4 rounded-xl border border-slate-800 text-white">
        <a href="{{ route('student.events') }}" class="text-xs font-semibold text-slate-300 hover:text-white flex items-center space-x-1">
            <span>&larr;</span>
            <span>Kembali ke Portal</span>
        </a>
        <div class="flex items-center space-x-3">
            <span class="text-xs text-slate-400 font-mono">{{ $registration->certificate_code }}</span>
            <button onclick="window.print()" class="px-4 py-2 bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-xs rounded-lg transition-colors flex items-center space-x-2 shadow-md shadow-sky-500/20">
                <span>🖨️ Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- The E-Certificate Canvas -->
    <div class="certificate-container bg-white w-full max-w-4xl p-10 sm:p-14 rounded-2xl border-8 border-slate-900 shadow-2xl relative overflow-hidden">
        
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(#0ea5e915_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

        <!-- Corner Badges -->
        <div class="absolute top-0 left-0 w-24 h-24 bg-gradient-to-br from-slate-900 via-slate-900 to-transparent flex items-start justify-start p-3 text-white text-xs font-mono font-bold">
            HMIF
        </div>
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-sky-600 via-sky-600 to-transparent flex items-start justify-end p-3 text-slate-950 text-xs font-bold">
            STTC
        </div>

        <div class="relative z-10 text-center space-y-6">
            
            <!-- Header Institution -->
            <div class="space-y-1">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-900 text-sky-400 font-black text-xl flex items-center justify-center mb-2">
                    &lt;/&gt;
                </div>
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-500">
                    Himpunan Mahasiswa Informatika (HMIF)
                </h3>
                <h4 class="text-sm font-bold uppercase tracking-wider text-slate-800">
                    Sekolah Tinggi Teknologi Cipasung
                </h4>
            </div>

            <!-- Certificate Title -->
            <div class="pt-4">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-950 tracking-wider font-cinzel">
                    SERTIFIKAT PENGHARGAAN
                </h1>
                <div class="text-[11px] font-mono text-slate-500 mt-1">
                    Nomor: {{ $registration->certificate_code }}
                </div>
            </div>

            <!-- Given To -->
            <div class="pt-3">
                <div class="text-xs uppercase text-slate-500 font-medium">Diberikan Dengan Hormat Kepada:</div>
                <div class="text-2xl sm:text-3xl font-extrabold text-sky-700 tracking-wide mt-2 border-b-2 border-slate-300 inline-block px-8 pb-1">
                    {{ $registration->user->name }}
                </div>
                <div class="text-xs text-slate-500 font-mono mt-1">
                    NIM: {{ $registration->user->nim ?? '-' }}
                </div>
            </div>

            <!-- Event Description -->
            <div class="max-w-2xl mx-auto text-xs sm:text-sm text-slate-700 leading-relaxed pt-2">
                Atas partisipasi aktif dan kehadirannya sebagai <strong class="text-slate-950">Peserta</strong> dalam kegiatan:
                <div class="text-base sm:text-lg font-bold text-slate-900 mt-1">
                    "{{ $registration->event->title }}"
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    Diselenggarakan oleh HMIF STT Cipasung pada tanggal {{ $registration->event->event_date->translatedFormat('d F Y') }}.
                </div>
            </div>

            <!-- Signatures & QR Code Validation -->
            <div class="pt-8 grid grid-cols-3 items-end text-center border-t border-slate-200 mt-8">
                
                <!-- Left Signature: Ketua Pelaksana / Himpunan -->
                <div class="space-y-1">
                    <div class="h-16 flex items-center justify-center text-slate-400 italic text-xs font-serif">
                        [ Digital Seal HMIF ]
                    </div>
                    <div class="font-bold text-xs text-slate-900 underline">Ketua HMIF STT Cipasung</div>
                    <div class="text-[10px] text-slate-500">Divisi Akademik & Kegiatan</div>
                </div>

                <!-- Center: Validated QR Code -->
                <div class="flex flex-col items-center justify-center space-y-1">
                    <div class="w-24 h-24 p-1.5 bg-white border border-slate-300 rounded-lg shadow-sm flex items-center justify-center">
                        {!! $qrSvg !!}
                    </div>
                    <div class="text-[9px] font-mono text-slate-400">Pindai untuk Verifikasi</div>
                </div>

                <!-- Right Signature: Sekretaris / Panitia -->
                <div class="space-y-1">
                    <div class="h-16 flex items-center justify-center text-slate-400 italic text-xs font-serif">
                        [ Terbit Otomatis ]
                    </div>
                    <div class="font-bold text-xs text-slate-900 underline">Koordinator Divisi Medinfo</div>
                    <div class="text-[10px] text-slate-500">Sistem Portal Kredensial</div>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
