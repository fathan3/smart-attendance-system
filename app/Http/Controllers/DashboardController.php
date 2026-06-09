<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Acara;
use App\Models\Absensi;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
{
    $stats = [
        'total_mahasiswa' => User::where('is_active', true)->count(),
        'acara_aktif'     => Acara::where('status', 'aktif')->count(),
        'hadir_hari_ini'  => Absensi::whereDate('waktu_masuk', today())
                                     ->where('status', '!=', 'tidak_hadir')
                                     ->count(),
    ];

    $aktivitas_terbaru = Absensi::with(['user', 'agenda.acara'])
                                 ->latest()
                                 ->take(10)
                                 ->get();

    $acara_mendatang = Acara::whereIn('status', ['draft', 'aktif'])
                             ->orderBy('tanggal_mulai')
                             ->take(5)
                             ->get();

    return view('dashboard', compact('stats', 'aktivitas_terbaru', 'acara_mendatang'));
}
}
