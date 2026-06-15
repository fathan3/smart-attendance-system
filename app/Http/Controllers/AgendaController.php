<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Acara;
use App\Models\Agenda;
use App\Models\Divisi;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
date_default_timezone_set('Asia/Jakarta');
class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index($acara_id)
    {
        $agenda = DB::table('agenda')->where('acara_id','=', $acara_id)->get();
        $namaacara = Acara::find($acara_id, ['nama','id']);
        $divisi = Divisi::all()->where('acara_id','=', $acara_id);
        return view('absensi.agenda', compact('agenda', 'namaacara', 'divisi'));

    }
    public function checkin($agenda_id){
        $agenda = Agenda::find($agenda_id);
        $absensi = DB::table('absensi')
        ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')->where('agenda_id', '=', $agenda_id)->get();
        return view('absensi.checkin', compact('agenda', 'absensi'));
    }
    public function checkout($agenda_id){
        $agenda = Agenda::find($agenda_id);
        return view('absensi.checkout', compact('agenda'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'nama'             => 'required|string|max:255',
        'checkin'        => 'required',
        'batas_checkin'    => 'required',
        'checkout'    => 'required',
        'batas_checkout'    => 'required',
        'acara_id'    => 'required',

        ]);

        $completed_payload = array_merge($data, [
            'checkin'        => \Carbon\Carbon::parse($request->input('checkin')),
            'batas_checkin'  => \Carbon\Carbon::parse($request->input('batas_checkin')),
            'checkout'       => \Carbon\Carbon::parse($request->input('checkout')),
            'batas_checkout' => \Carbon\Carbon::parse($request->input('batas_checkout')),

        ]);

        // echo $completed_payload['checkin'];
        Agenda::create($completed_payload);
        return redirect()->route('acara.agenda', $request->input('acara_id'));

    }

    public function checkin_panitia(Request $request, $id_agenda) {
    $nowImmutable = new \DateTimeImmutable();
    $jam = $nowImmutable->format('Y-m-d H:i:s');
    
    // 1. Simpan data baru
    Absensi::create([
        'agenda_id' => $id_agenda,
        'rfid_uid' => $request->input('rfid'),
        'waktu_masuk' => $jam,
        'status' => 'hadir',
        'keterangan' => '-',
    ]);

    if ($request->wantsJson() || $request->ajax()) {
    try {
        // 1. UBAH DI SINI (hilangkan huruf 's')
        $absensi = DB::table('absensi')
        ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')->where('agenda_id', '=', $id_agenda)->orderBy('waktu_masuk', 'desc')->get() ;
        $nama = DB::table('users')->where('rfid_uid',$request->input('rfid'))->first();

        // 2. UBAH JUGA DI SINI (di dalam compact)
        $htmlTabel = view('partials.tabel_absensi', compact('absensi'))->render();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil scan!',
            'html' => $htmlTabel,
            'nama' => $nama->name
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error PHP: ' . $e->getMessage() . ' di baris ' . $e->getLine()
        ], 200);
    }
}

    return redirect()->route('checkin', $id_agenda);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
        'nama'             => 'required|string|max:255',
        'checkin'        => 'required',
        'batas_checkin'    => 'required',
        'checkout'    => 'required',
        'batas_checkout'    => 'required',
    ]);

    $acara = Agenda::findOrFail($id);
    $acara->update($data);
    
    return redirect()->route('acara.agenda', $request->input('acara_id'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
