<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
   public function index()
{
    $acara = Acara::withCount('agenda')
                  ->with('users')
                  ->orderByDesc('tanggal_mulai')
                  ->get();

    return view('absensi.acara', compact('acara'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'nama'             => 'required|string|max:255',
        'deskripsi'        => 'nullable|string',
        'tanggal_mulai'    => 'required|date',
        'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        'lokasi'           => 'nullable|string|max:255',
        'status'           => 'in:draft,aktif,selesai,dibatalkan',
        'agenda'           => 'array',
        'agenda.*.nama'    => 'required|string',
        'agenda.*.jam_mulai'        => 'required|date_format:H:i',
        'agenda.*.jam_selesai'      => 'required|date_format:H:i',
        'agenda.*.batas_absen_masuk' => 'nullable|date_format:H:i',
    ]);

    $acara = Acara::create($data);

    // Simpan agenda sekaligus
    if (!empty($data['agenda'])) {
        $acara->agenda()->createMany($data['agenda']);
    }

    return redirect()->route('acara.index')
                     ->with('success', 'Acara berhasil dibuat.');
}

// Daftarkan mahasiswa ke acara beserta divisinya
public function daftarkanPeserta(Request $request, Acara $acara)
{
    $request->validate([
        'user_id'   => 'required|exists:users,id',
        'divisi_id' => 'required|exists:divisi,id',
    ]);

    $acara->users()->syncWithoutDetaching([
        $request->user_id => ['divisi_id' => $request->divisi_id]
    ]);

    return back()->with('success', 'Peserta berhasil didaftarkan.');
}
}
