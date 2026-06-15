<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Agenda;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    // Proses scan RFID — endpoint ini dipanggil dari scanner
    public function scan(Request $request)
    {
        $request->validate([
            'rfid_uid' => 'required|string',
            'agenda_id' => 'required|exists:agenda,id',
            'tipe' => 'required|in:masuk,pulang',
        ]);

        $user = User::where('rfid_uid', $request->rfid_uid)
            ->where('is_active', true)
            ->firstOrFail();

        $agenda = Agenda::findOrFail($request->agenda_id);
        $now = now();

        $absensi = Absensi::firstOrNew([
            'agenda_id' => $agenda->id,
            'user_id' => $user->id,
        ]);

        if ($request->tipe === 'masuk') {
            if ($absensi->waktu_masuk) {
                return response()->json(['message' => 'Sudah absen masuk.'], 422);
            }

            // Tentukan status berdasarkan batas waktu
            $batas = $agenda->batas_absen_masuk;
            $status = ($batas && $now->format('H:i:s') > $batas)
                        ? 'terlambat' : 'hadir';

            $absensi->fill([
                'rfid_uid' => $request->rfid_uid,
                'waktu_masuk' => $now,
                'status' => $status,
            ])->save();

        } else {
            if (! $absensi->waktu_masuk) {
                return response()->json(['message' => 'Belum absen masuk.'], 422);
            }

            $absensi->update(['waktu_pulang' => $now]);
        }

        return response()->json([
            'message' => 'Absensi berhasil dicatat.',
            'user' => $user->name,
            'status' => $absensi->status,
            'waktu' => $now->format('H:i:s'),
        ]);
    }

    public function index(Request $request)
    {
        $absensi = Absensi::with(['user', 'agenda.acara'])
            ->when($request->agenda_id, fn ($q, $v) => $q->where('agenda_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate(30);

        return view('absensi.index', compact('absensi'));
    }
}
