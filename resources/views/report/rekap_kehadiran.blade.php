<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Helvetica', 'Arial', sans-serif !important;
        }

        body {
            background: #FFFFFF;
            color: #1E293B;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #0F172A;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .org-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #3B82F6;
            margin: 0 0 3px 0;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
            letter-spacing: -0.3px;
            text-transform: uppercase;
        }

        .meta-box {
            font-size: 9.5px;
            color: #334155;
            margin-bottom: 14px;
            padding: 10px 14px;
            background: #F8FAFC;
            border-radius: 6px;
            border: 1px solid #E2E8F0;
        }

        .meta-table {
            width: 100%;
            border: none;
        }

        .meta-table td {
            border: none;
            padding: 2px 4px;
            font-size: 9.5px;
        }

        .stats-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }

        .stat-card {
            background: #F1F5F9;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 8px 10px;
            text-align: center;
        }

        .stat-val {
            font-size: 15px;
            font-weight: 800;
            color: #0F172A;
        }

        .stat-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            font-weight: 600;
            margin-top: 2px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }

        table.data-table th {
            background: #0F172A;
            color: #FFFFFF;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 8px;
            text-align: left;
            border: 1px solid #0F172A;
        }

        table.data-table th.center {
            text-align: center;
        }

        table.data-table td {
            padding: 7px 8px;
            color: #334155;
            border: 1px solid #E2E8F0;
        }

        table.data-table tr:nth-child(even) td {
            background: #F8FAFC;
        }

        .center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 8px;
            font-weight: 700;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .badge-green {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
        }

        .badge-amber {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #FDE68A;
        }

        .badge-red {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .badge-gray {
            background: #F1F5F9;
            color: #64748B;
            border: 1px solid #CBD5E1;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #E2E8F0;
            padding-top: 8px;
            font-size: 8.5px;
            color: #94A3B8;
            text-align: center;
        }

        .ttd-table {
            width: 100%;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            border: none;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen Formal -->
    <table class="header-table">
        <tr>
            <td style="border:none; padding:0; vertical-align:middle;">
                <p class="org-label">Himpunan Mahasiswa Teknik Informatika &middot; HIMATIF 2026</p>
                <h1 class="doc-title">Laporan Rekapitulasi Kehadiran</h1>
            </td>
            <td style="border:none; padding:0; text-align:right; vertical-align:middle; font-size:9px; color:#64748B;">
                <strong>Dicetak pada:</strong><br>{{ $date }}
            </td>
        </tr>
    </table>

    <!-- Meta Information -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td style="width:12%;"><strong>Periode:</strong></td>
                <td style="width:38%;">{{ $filter_info['periode'] }}</td>
                <td style="width:15%;"><strong>Filter Status:</strong></td>
                <td style="width:35%;">{{ $filter_info['status'] }}</td>
            </tr>
            <tr>
                <td><strong>Acara:</strong></td>
                <td>{{ $filter_info['acara'] }}</td>
                <td><strong>Total Mahasiswa:</strong></td>
                <td>{{ $total_mahasiswa }} Orang</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Statistik -->
    <table class="stats-table">
        <tr>
            <td class="stat-card" style="width:25%;">
                <div class="stat-val">{{ $total_mahasiswa }}</div>
                <div class="stat-label">Total Mahasiswa</div>
            </td>
            <td class="stat-card" style="width:25%;">
                <div class="stat-val" style="color: #059669;">{{ $rata_hadir }}%</div>
                <div class="stat-label">Rata-rata Hadir</div>
            </td>
            <td class="stat-card" style="width:25%;">
                <div class="stat-val" style="color: #DC2626;">{{ $total_tidak_hadir }}</div>
                <div class="stat-label">Total Tidak Hadir</div>
            </td>
            <td class="stat-card" style="width:25%;">
                <div class="stat-val" style="color: #2563EB;">{{ $acara_selesai }}</div>
                <div class="stat-label">Acara Selesai</div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Rekapitulasi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="center">No</th>
                <th style="width: 33%;">Nama Mahasiswa</th>
                <th style="width: 12%;" class="center">Total Agenda</th>
                <th style="width: 10%;" class="center">Hadir</th>
                <th style="width: 10%;" class="center">Terlambat</th>
                <th style="width: 12%;" class="center">Tidak Hadir</th>
                <th style="width: 10%;" class="center">Persentase</th>
                <th style="width: 8%;" class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa as $idx => $mhs)
            @php
                $pct = $mhs->laporan->persentase;
                $badgeClass = 'badge-green';
                $badgeText = 'Baik';
                if ($pct < 50) {
                    $badgeClass = 'badge-red';
                    $badgeText = 'Kurang';
                } elseif ($pct < 80) {
                    $badgeClass = 'badge-amber';
                    $badgeText = 'Cukup';
                }
            @endphp
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td style="font-weight: 600; color: #0F172A;">{{ $mhs->name }}</td>
                <td class="center">{{ $mhs->laporan->total_agenda }}</td>
                <td class="center" style="color: #059669; font-weight: 700;">{{ $mhs->laporan->hadir }}</td>
                <td class="center" style="color: #D97706; font-weight: 700;">{{ $mhs->laporan->terlambat }}</td>
                <td class="center" style="color: #DC2626; font-weight: 700;">{{ $mhs->laporan->tidak_hadir }}</td>
                <td class="center" style="font-weight: 700;">{{ $pct }}%</td>
                <td class="center">
                    <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center" style="padding: 16px; color: #64748B;">
                    Tidak ada data kehadiran yang sesuai dengan filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Formal -->
    <table class="ttd-table">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;">
                <p style="margin: 0; font-size: 9.5px;">Surabaya, {{ date('d F Y') }}</p>
                <p style="margin: 4px 0 0 0; font-weight: 600; font-size: 9.5px;">Pengurus HIMATIF 2026</p>
                <div style="height: 50px;"></div>
                <p style="margin: 0; font-weight: 700; text-decoration: underline; font-size: 10px;">Administrator Absensi</p>
                <p style="margin: 2px 0 0 0; font-size: 8.5px; color: #64748B;">Sistem Absensi Cerdas</p>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh <strong>Smart Attendance System</strong> &middot; HIMATIF 2026 X Sandikala
    </div>

</body>
</html>
