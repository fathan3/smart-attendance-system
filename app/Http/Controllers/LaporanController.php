<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Acara;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $total_mahasiswa = User::where('is_active', true)->count();
        $acara_selesai = Acara::where('status', 'selesai')->count();

        $total_absensi = Absensi::count();
        $total_tidak_hadir = Absensi::where('status', 'tidak-hadir')->count();

        $rata_hadir = $total_absensi > 0 ? round((Absensi::whereIn('status', ['hadir', 'terlambat'])->count() / $total_absensi) * 100) : 0;

        $mahasiswa = User::where('is_active', true)->with(['absensi'])->get()->map(function ($m) {
            $total_agenda = $m->absensi->count();
            $hadir = $m->absensi->where('status', 'hadir')->count();
            $terlambat = $m->absensi->where('status', 'terlambat')->count();
            $tidak_hadir = $m->absensi->where('status', 'tidak-hadir')->count();

            $persentase = $total_agenda > 0 ? round((($hadir + $terlambat) / $total_agenda) * 100) : 0;

            $m->laporan = (object) [
                'total_agenda' => $total_agenda,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tidak_hadir' => $tidak_hadir,
                'persentase' => $persentase,
            ];

            return $m;
        });

        return view('absensi.laporan', compact(
            'total_mahasiswa', 'acara_selesai', 'rata_hadir', 'total_tidak_hadir', 'mahasiswa'
        ));
    }
}
