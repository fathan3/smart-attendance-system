<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Acara;
use App\Models\Agenda;
use Illuminate\Support\Facades\DB;

date_default_timezone_set('Asia/Jakarta');

class PDFReportingController extends Controller
{
    public function generatePDF($agenda_id)
    {   
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::findOrFail($agenda_id);
        $acara = Acara::findOrFail($agenda->acara_id);
        $absensi = DB::table('acara_user')
                    ->join('users', 'users.id', '=', 'acara_user.user_id')
                    ->leftJoin('absensi', function ($join) use ($agenda_id) {
                        $join->on('users.rfid_uid', '=', 'absensi.rfid_uid')
                             ->where('absensi.agenda_id', '=', $agenda_id);
                    })
                    ->join('divisi', 'acara_user.divisi_id', '=', 'divisi.id')
                    ->where('acara_user.acara_id', '=', $agenda->acara_id)
                    ->select(
                        'users.id as user_id',
                        'users.name as name',
                        'users.rfid_uid as rfid_uid',
                        'users.email as email',
                        'divisi.nama as nama_divisi',
                        'absensi.status',
                        'absensi.waktu_masuk',
                        'absensi.waktu_pulang',
                        'absensi.keterangan'
                    )
                    ->orderBy('acara_user.divisi_id', 'asc')
                    ->get();

        $stats = [
            'total' => $absensi->count(),
            'hadir' => $absensi->whereIn('status', ['hadir', 'terlambat'])->count(),
            'tidak_hadir' => $absensi->filter(fn($x) => is_null($x->status) || $x->status === 'tidak_hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
        ];

        $data = [
            'title' => 'Laporan Absensi | ' . $acara->nama.' - '. $agenda->nama,
            'agenda' => $agenda->nama, 
            'acara' => $acara->nama, 
            'absensi' => $absensi,
            'stats' => $stats,
            'date' => date('d-m-Y H:i:s'),
        ];
        
        $fileName = $acara->nama.' - '.$agenda->nama.'.pdf';
        // Load the blade view and pass data
        $pdf = Pdf::loadView('report.absensi', $data);
        
        // Optional: Set paper size and orientation (e.g., portrait or landscape)
        $pdf->setPaper('A4', 'landscape');

        // Stream the PDF directly in the browser
        // return $pdf->stream('document.pdf');
        
        // OR: Force a file download instead
        return $pdf->download($fileName);
    }
}
