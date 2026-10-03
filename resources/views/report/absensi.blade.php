<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Helvetica', sans-serif !important;
        }

        body {
            background: #FFFFFF;
            color: #1E293B;
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .header {
            padding-bottom: 14px;
            border-bottom: 2px solid #E2E8F0;
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .org-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #94A3B8;
            margin: 0 0 2px 0;
        }

        .doc-title {
            font-size: 20px;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .meta {
            font-size: 10.5px;
            color: #475569;
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #F8FAFC;
            border-radius: 10px;
            border: 1px solid #F1F5F9;
        }

        .meta strong {
            color: #0F172A;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead th {
            background: #F1F5F9;
            color: #64748B;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 10px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }

        thead th.center {
            text-align: center;
        }

        tbody td {
            padding: 9px 10px;
            color: #334155;
            border-bottom: 1px solid #F1F5F9;
        }

        tbody tr:nth-child(even) td {
            background: #FAFAFA;
        }

        tbody td.center {
            text-align: center;
        }

        .no {
            color: #94A3B8;
            text-align: center;
            font-weight: 500;
        }

        .name {
            font-weight: 600;
            color: #0F172A;
        }

        .divisi {
            color: #64748B;
        }

        .rfid {
            font-size: 9px;
            color: #3B82F6;
            font-weight: 500;
        }

        .time {
            text-align: center;
            color: #475569;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 3px 12px;
            font-size: 8px;
            font-weight: 600;
            border-radius: 40px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-hadir {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
        }

        .badge-terlambat {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #FDE68A;
        }

        .badge-izin {
            background: #EFF6FF;
            color: #2563EB;
            border: 1px solid #BFDBFE;
        }

        .badge-alpa {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .keterangan {
            font-size: 9px;
            color: #64748B;
        }

        .footer {
            margin-top: 24px;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
            font-size: 9px;
            color: #94A3B8;
            text-align: center;
        }

        .footer span {
            color: #3B82F6;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <table style="width:100%; border-bottom: 2px solid #E2E8F0; margin-bottom: 18px; padding-bottom: 14px;">
        <tr>
            <td style="border:none; padding:0; vertical-align:bottom;">
                <p class="org-label">Himpunan Mahasiswa Teknik Informatika &middot; HIMATIF</p>
                <h1 class="doc-title">Laporan Kehadiran</h1>
            </td>
            <td style="border:none; padding:0; text-align:right; vertical-align:bottom; font-size:10px; color:#64748B;">
                {{ $date }}
            </td>
        </tr>
    </table>

    <div class="meta">
        <table style="width:100%; border:none; padding:0;">
            <tr>
                <td style="border:none; padding:0; vertical-align:middle;">
                    <strong>{{ $acara }}</strong> &mdash; {{ $agenda }}
                </td>
                <td style="border:none; padding:0; text-align:right; vertical-align:middle; font-size:10px;">
                    Total: <strong>{{ $stats['total'] }}</strong>
                    &nbsp;&nbsp; Hadir: <strong>{{ $stats['hadir'] }}</strong>
                    &nbsp;&nbsp; Izin: <strong>{{ $stats['izin'] }}</strong>
                    &nbsp;&nbsp; Alpa: <strong>{{ $stats['tidak_hadir'] }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%;" class="center">No</th>
                <th style="width:22%;">Nama</th>
                <th style="width:16%;">RFID</th>
                <th style="width:16%;">Divisi</th>
                <th style="width:10%;" class="center">Masuk</th>
                <th style="width:10%;" class="center">Pulang</th>
                <th style="width:10%;" class="center">Status</th>
                <th style="width:12%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $idx => $abs)
            @php
            $status      = $abs->status ?? 'tidak_hadir';
            $badgeClass  = 'badge-alpa';
            $statusLabel = 'Alpa';

            if ($status === 'hadir') {
                $badgeClass  = 'badge-hadir';
                $statusLabel = 'Hadir';
            } elseif ($status === 'terlambat') {
                $badgeClass  = 'badge-terlambat';
                $statusLabel = 'Terlambat';
            } elseif ($status === 'izin') {
                $badgeClass  = 'badge-izin';
                $statusLabel = 'Izin';
            }
            @endphp
            <tr>
                <td class="no">{{ $idx + 1 }}</td>
                <td class="name">{{ $abs->name }}</td>
                <td class="rfid">{{ $abs->rfid_uid ?? '-' }}</td>
                <td class="divisi">{{ $abs->nama_divisi }}</td>
                <td class="time">{{ $abs->waktu_masuk ? \Carbon\Carbon::parse($abs->waktu_masuk)->format('H:i') : '-' }}</td>
                <td class="time">{{ $abs->waktu_pulang ? \Carbon\Carbon::parse($abs->waktu_pulang)->format('H:i') : '-' }}</td>
                <td class="center">
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="keterangan">{{ $abs->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis dari <span><i> Smart Attendance System</i></span> | HIMATIF 2026 X Sandikala
    </div>

</body>
</html>
