<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Acara;
use App\Models\Agenda;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    private function getLaporanData(Request $request)
    {
        $acara_list = Acara::orderBy('nama', 'asc')->get();

        // 1. Filter Agendas based on Acara & Periode
        $agendaQuery = Agenda::query();

        if ($request->filled('acara_id')) {
            $agendaQuery->where('acara_id', $request->acara_id);
        }

        $periode = $request->input('periode', 'semua');
        $periodeText = 'Semua Periode';

        if ($periode === 'hari_ini') {
            $agendaQuery->whereDate('checkin', today());
            $periodeText = 'Hari Ini (' . date('d F Y') . ')';
        } elseif ($periode === 'minggu_ini') {
            $start = now()->startOfWeek()->format('d M');
            $end = now()->endOfWeek()->format('d M Y');
            $agendaQuery->whereBetween('checkin', [now()->startOfWeek(), now()->endOfWeek()]);
            $periodeText = 'Minggu Ini (' . $start . ' - ' . $end . ')';
        } elseif ($periode === 'bulan_ini') {
            $agendaQuery->whereMonth('checkin', now()->month)->whereYear('checkin', now()->year);
            $periodeText = 'Bulan Ini (' . now()->translatedFormat('F Y') . ')';
        } elseif ($periode === 'custom' && $request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $agendaQuery->whereBetween(DB::raw('DATE(checkin)'), [$request->tanggal_mulai, $request->tanggal_selesai]);
            $periodeText = date('d/m/Y', strtotime($request->tanggal_mulai)) . ' s/d ' . date('d/m/Y', strtotime($request->tanggal_selesai));
        }

        $filteredAgendas = $agendaQuery->with('acara')->get();
        $filteredAgendaIds = $filteredAgendas->pluck('id')->toArray();

        // 2. Filter Students Query
        if ($request->filled('acara_id')) {
            $userQuery = User::where('is_active', true)
                ->whereHas('acara', function ($q) use ($request) {
                    $q->where('acara.id', $request->acara_id);
                });
        } else {
            $userQuery = User::where('is_active', true);
        }

        $allUsers = $userQuery->with(['acara', 'absensi' => function ($q) use ($filteredAgendaIds) {
            $q->whereIn('agenda_id', $filteredAgendaIds);
        }])->orderBy('name', 'asc')->get();

        // 3. Map Attendance Details per Student
        $mahasiswa = $allUsers->map(function ($m) use ($filteredAgendas, $filteredAgendaIds, $request) {
            if ($request->filled('acara_id')) {
                $studentAgendaCount = count($filteredAgendaIds);
            } else {
                $mAcaraIds = $m->acara->pluck('id')->toArray();
                $studentAgendaCount = $filteredAgendas->whereIn('acara_id', $mAcaraIds)->count();
            }

            $hadir = $m->absensi->where('status', 'hadir')->count();
            $terlambat = $m->absensi->where('status', 'terlambat')->count();
            $recordedTidakHadir = $m->absensi->where('status', 'tidak_hadir')->count();
            $unrecordedTidakHadir = max(0, $studentAgendaCount - ($hadir + $terlambat + $recordedTidakHadir));
            $tidak_hadir = $recordedTidakHadir + $unrecordedTidakHadir;

            $persentase = $studentAgendaCount > 0
                ? round((($hadir + $terlambat) / $studentAgendaCount) * 100)
                : 0;

            $m->laporan = (object) [
                'total_agenda' => $studentAgendaCount,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tidak_hadir' => $tidak_hadir,
                'persentase' => $persentase,
            ];

            return $m;
        });

        // 4. Filter by Status (Hadir, Terlambat, Tidak Hadir)
        $statusFilter = $request->input('status');
        $statusText = 'Semua Status';
        if ($statusFilter === 'hadir') {
            $mahasiswa = $mahasiswa->filter(fn($m) => $m->laporan->hadir > 0);
            $statusText = 'Hadir';
        } elseif ($statusFilter === 'terlambat') {
            $mahasiswa = $mahasiswa->filter(fn($m) => $m->laporan->terlambat > 0);
            $statusText = 'Terlambat';
        } elseif ($statusFilter === 'tidak_hadir') {
            $mahasiswa = $mahasiswa->filter(fn($m) => $m->laporan->tidak_hadir > 0);
            $statusText = 'Tidak Hadir';
        }

        // 5. Calculate Metrics for Cards
        $total_mahasiswa = $mahasiswa->count();

        $studentsWithAgendas = $mahasiswa->filter(fn($m) => $m->laporan->total_agenda > 0);
        $totalAgendaSlots = $studentsWithAgendas->sum(fn($m) => $m->laporan->total_agenda);
        $totalHadir = $studentsWithAgendas->sum(fn($m) => $m->laporan->hadir + $m->laporan->terlambat);

        $rata_hadir = $totalAgendaSlots > 0 ? round(($totalHadir / $totalAgendaSlots) * 100) : 0;
        $total_tidak_hadir = $mahasiswa->sum(fn($m) => $m->laporan->tidak_hadir);

        $acaraSelesaiQuery = Acara::whereDate('tanggal_selesai', '<', today());
        if ($request->filled('acara_id')) {
            $acaraSelesaiQuery->where('id', $request->acara_id);
        }
        $acara_selesai = $acaraSelesaiQuery->count();

        $selectedAcara = $request->filled('acara_id') ? Acara::find($request->acara_id) : null;
        $acaraText = $selectedAcara ? $selectedAcara->nama : 'Semua Acara';

        $filter_info = [
            'periode' => $periodeText,
            'acara' => $acaraText,
            'status' => $statusText,
        ];

        return compact(
            'total_mahasiswa', 'acara_selesai', 'rata_hadir', 'total_tidak_hadir',
            'mahasiswa', 'acara_list', 'filter_info'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('absensi.laporan', $data);
    }

    public function exportPDF(Request $request)
    {
        $data = $this->getLaporanData($request);
        $data['title'] = 'Laporan Rekapitulasi Kehadiran - HIMATIF 2026';
        $data['date'] = date('d F Y, H:i') . ' WIB';

        $pdf = Pdf::loadView('report.rekap_kehadiran', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Laporan_Rekap_Kehadiran_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
