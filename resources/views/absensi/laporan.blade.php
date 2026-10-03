@extends('layouts.app')
@section('page_title', 'Laporan Kehadiran')
@section('page_subtitle', 'Rekap dan ringkasan kehadiran mahasiswa')
@section('main-content')

    <style>
        @media print {
            /* Sembunyikan elemen web yang tidak perlu dicetak */
            #sidebar,
            #topbar,
            #form-filter,
            .no-print,
            button,
            footer,
            .web-only {
                display: none !important;
            }

            /* Format halaman cetak formal */
            body, #page-laporan {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-family: 'Helvetica', 'Arial', sans-serif !important;
                font-size: 10pt !important;
                color: #000000 !important;
            }

            .print-only {
                display: block !important;
            }

            .web-card {
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
            }

            #table-laporan {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-top: 12px !important;
            }

            #table-laporan th, #table-laporan td {
                border: 1px solid #94a3b8 !important;
                padding: 6px 8px !important;
                font-size: 8.5pt !important;
                color: #000000 !important;
            }

            #table-laporan th {
                background-color: #f1f5f9 !important;
                font-weight: 700 !important;
                text-align: center !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .progress-bar-container {
                display: none !important;
            }
            .progress-text {
                display: inline !important;
            }
        }

        @media screen {
            .print-only {
                display: none;
            }
        }
    </style>

    <div id="page-laporan" class="page active">

        <!-- HEADER KHUSUS CETAK FORMAL (Hanya muncul saat di-print / cetak) -->
        <div class="print-only mb-6">
            <div style="border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase; color: #2563eb; letter-spacing: 1px;">Himpunan Mahasiswa Teknik Informatika (HIMATIF)</div>
                    <h1 style="font-size: 16pt; font-weight: 800; margin: 3px 0 0 0; text-transform: uppercase; color: #0f172a;">Laporan Rekapitulasi Kehadiran Mahasiswa</h1>
                </div>
                <div style="text-align: right; font-size: 8.5pt; color: #64748b;">
                    <strong>Tanggal Cetak:</strong><br>{{ date('d F Y, H:i') }} WIB
                </div>
            </div>

            <!-- Info Filter Cetak -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; font-size: 9pt;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="width: 12%; border: none; padding: 2px 0;"><strong>Periode:</strong></td>
                        <td style="width: 38%; border: none; padding: 2px 0;">{{ $filter_info['periode'] }}</td>
                        <td style="width: 15%; border: none; padding: 2px 0;"><strong>Filter Status:</strong></td>
                        <td style="width: 35%; border: none; padding: 2px 0;">{{ $filter_info['status'] }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px 0;"><strong>Acara:</strong></td>
                        <td style="border: none; padding: 2px 0;">{{ $filter_info['acara'] }}</td>
                        <td style="border: none; padding: 2px 0;"><strong>Total Data:</strong></td>
                        <td style="border: none; padding: 2px 0;">{{ $total_mahasiswa }} Mahasiswa (Rata-rata Hadir: {{ $rata_hadir }}%)</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Bagian Atas: Filter (Kiri) & Ringkasan (Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <!-- Filter Laporan (Disembunyikan Otomatis Saat Print) -->
            <form method="GET" action="{{ route('laporan.index') }}" id="form-filter" class="no-print">
                <div class="bg-white border border-slate-200/60 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Filter Laporan</div>
                        @if(request()->hasAny(['periode', 'acara_id', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                            <a href="{{ route('laporan.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                ✕ Reset Filter
                            </a>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-slate-700">Periode</label>
                            <select name="periode" id="filter-periode" class="inp" onchange="toggleCustomDate(this.value)">
                                <option value="semua" {{ request('periode', 'semua') == 'semua' ? 'selected' : '' }}>Semua Periode</option>
                                <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                            </select>
                        </div>

                        <div id="custom-date-container" class="{{ request('periode') == 'custom' ? '' : 'hidden' }} space-y-2 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                            <div>
                                <label class="text-xs text-slate-600">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="inp text-xs py-1.5">
                            </div>
                            <div>
                                <label class="text-xs text-slate-600">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="inp text-xs py-1.5">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-slate-700">Acara</label>
                            <select name="acara_id" class="inp">
                                <option value="">Semua Acara</option>
                                @foreach($acara_list as $ac)
                                    <option value="{{ $ac->id }}" {{ request('acara_id') == $ac->id ? 'selected' : '' }}>
                                        {{ $ac->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-slate-700">Status</label>
                            <select name="status" class="inp">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center mt-2">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Ringkasan Laporan -->
            <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-lg p-5 web-card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-slate-900">Ringkasan Laporan</h3>
                        <p class="text-slate-500 text-xs mt-0.5">
                            @if(request()->filled('status'))
                                Filter status: <span class="capitalize font-semibold text-blue-600">{{ request('status') }}</span>
                            @else
                                Ringkasan statistik kehadiran
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-2 no-print">
                        <!-- Tombol Download PDF Resmi (DomPDF) -->
                        <a href="{{ route('laporan.pdf', request()->query()) }}" target="_blank"
                            class="btn-primary text-xs py-1.5 px-3 inline-flex items-center gap-1.5"
                            title="Unduh file dokumen PDF resmi lengkap dengan tanda tangan">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Cetak PDF Resmi
                        </a>

                        <!-- Tombol Cetak Langsung Browser -->
                        <button class="btn-secondary text-xs py-1.5 px-3 inline-flex items-center gap-1.5" onclick="window.print()"
                            title="Cetak langsung menggunakan printer / print preview browser">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>

                        <!-- Tombol Export Excel -->
                        <button class="btn-green text-xs py-1.5 px-3 inline-flex items-center gap-1.5" onclick="exportToExcel()">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <div id="laporan-content" class="grid grid-cols-2 gap-3 mb-2">
                    <div class="bg-slate-50 rounded-lg p-4 text-center border border-slate-100">
                        <div class="text-2xl font-bold tracking-tight text-slate-900">{{ $total_mahasiswa }}</div>
                        <div class="text-slate-500 text-xs mt-1">Total Mahasiswa</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center border border-slate-100">
                        <div class="text-2xl font-bold tracking-tight text-emerald-600">{{ $rata_hadir }}%</div>
                        <div class="text-slate-500 text-xs mt-1">Rata-rata Hadir</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center border border-slate-100">
                        <div class="text-2xl font-bold tracking-tight text-rose-600">{{ $total_tidak_hadir }}</div>
                        <div class="text-slate-500 text-xs mt-1">Total Tidak Hadir</div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 text-center border border-slate-100">
                        <div class="text-2xl font-bold tracking-tight text-sky-600">{{ $acara_selesai }}</div>
                        <div class="text-slate-500 text-xs mt-1">Acara Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Detail Kehadiran -->
        <div class="bg-white border border-slate-200/60 rounded-lg overflow-hidden web-card">
            <div class="flex items-center justify-between p-5 border-b border-slate-200/60 no-print">
                <div>
                    <h3 class="font-semibold text-slate-900">Detail Kehadiran Per Mahasiswa</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Menampilkan {{ $mahasiswa->count() }} data mahasiswa</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('laporan.pdf', request()->query()) }}" target="_blank" class="btn-secondary text-xs py-1.5 px-3">
                        Download PDF
                    </a>
                    <button class="btn-secondary text-xs py-1.5 px-3" onclick="window.print()">
                        Cetak Semua
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table w-full" id="table-laporan">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Nama Mahasiswa</th>
                            <th class="text-center">Total Agenda</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Tidak Hadir</th>
                            <th class="text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa as $idx => $mhs)
                            <tr>
                                <td class="text-slate-400 text-center">{{ $loop->iteration }}</td>
                                <td class="text-slate-900 font-medium">{{ $mhs->name }}</td>
                                <td class="text-center">{{ $mhs->laporan->total_agenda }}</td>
                                <td class="text-emerald-600 font-semibold text-center">{{ $mhs->laporan->hadir }}</td>
                                <td class="text-amber-600 font-semibold text-center">{{ $mhs->laporan->terlambat }}</td>
                                <td class="text-rose-600 font-semibold text-center">{{ $mhs->laporan->tidak_hadir }}</td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="progress-bar-container flex-1 min-w-[70px] max-w-[120px]">
                                            <div class="progress-bar">
                                                <div class="progress-fill {{ $mhs->laporan->persentase >= 80 ? 'bg-emerald-500' : ($mhs->laporan->persentase >= 50 ? 'bg-blue-600' : 'bg-red-500') }}"
                                                    style="width:{{ $mhs->laporan->persentase }}%"></div>
                                            </div>
                                        </div>
                                        <span class="progress-text text-slate-900 text-xs font-semibold">{{ $mhs->laporan->persentase }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-500 py-6">
                                    Tidak ada data kehadiran yang sesuai dengan filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TANDA TANGAN FORMAL KHUSUS CETAK -->
        <div class="print-only mt-8" style="page-break-inside: avoid;">
            <table style="width: 100%; border: none; margin-top: 35px;">
                <tr>
                    <td style="width: 65%; border: none;"></td>
                    <td style="width: 35%; border: none; text-align: center; font-size: 9.5pt;">
                        <div>Surabaya, {{ date('d F Y') }}</div>
                        <div style="font-weight: 600; margin-top: 3px;">Pengurus HIMATIF 2026</div>
                        <div style="height: 55px;"></div>
                        <div style="font-weight: bold; text-decoration: underline;">Administrator Absensi</div>
                        <div style="font-size: 8.5pt; color: #64748b; margin-top: 2px;">Smart Attendance System</div>
                    </td>
                </tr>
            </table>
            <div style="border-top: 1px solid #cbd5e1; margin-top: 25px; padding-top: 6px; font-size: 8pt; color: #94a3b8; text-align: center;">
                Dokumen ini digenerate secara otomatis oleh <strong>Smart Attendance System</strong> &middot; HIMATIF 2026 X Sandikala
            </div>
        </div>

    </div>

    <script>
        function toggleCustomDate(val) {
            const container = document.getElementById('custom-date-container');
            if (val === 'custom') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        function exportToExcel() {
            const table = document.getElementById('table-laporan');
            if (!table) return;

            const rows = table.querySelectorAll('tr');
            let csv = [];

            rows.forEach(row => {
                const cols = row.querySelectorAll('th, td');
                let rowData = [];
                cols.forEach(col => {
                    let text = col.innerText.replace(/\n/g, ' ').replace(/"/g, '""').trim();
                    rowData.push('"' + text + '"');
                });
                if (rowData.length > 0) {
                    csv.push(rowData.join(','));
                }
            });

            const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csv.join('\n'));
            const link = document.createElement('a');
            link.setAttribute('href', csvContent);
            const dateStr = new Date().toISOString().slice(0, 10);
            link.setAttribute('download', `Laporan_Kehadiran_${dateStr}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection
