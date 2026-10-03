<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Acara;
use App\Models\Agenda;
use App\Models\Divisi;
use Carbon\Carbon;
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
        $acara_id = decrypt($acara_id);
        $agenda = DB::table('agenda')->where('acara_id', '=', $acara_id)->get();
        $namaacara = Acara::findOrFail($acara_id, ['nama', 'id']);
        $divisi = Divisi::where('acara_id', '=', $acara_id)->get();
        // Tampilkan semua mahasiswa aktif (baik yang sudah memiliki divisi maupun yang belum)
        $panitia = DB::table('users')
                ->leftJoin('acara_user', function ($join) use ($acara_id) {
                    $join->on('acara_user.user_id', '=', 'users.id')
                         ->where('acara_user.acara_id', '=', $acara_id);
                })
                ->leftJoin('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                ->where('users.is_active', '=', '1')
                ->select(
                    'acara_user.id as pivot_id',
                    'acara_user.divisi_id',
                    'users.id as user_id',
                    'users.name',
                    'users.email',
                    'users.rfid_uid',
                    'users.is_active',
                    'divisi.nama as nama_divisi'
                )
                ->orderByRaw('CASE WHEN acara_user.id IS NOT NULL THEN 0 ELSE 1 END ASC')
                ->orderBy('divisi.id', 'asc')
                ->orderBy('users.name', 'asc')
                ->get();

        $panitia_available = DB::table('users')->whereNotIn('id', function ($query) use ($acara_id) {
            $query->select('user_id')->from('acara_user')->where('acara_id', '=', $acara_id);
        })
        ->where('users.is_active', '=', '1')
        ->orderBy('name', 'asc')
        ->get();

        return view('absensi.agenda', compact('agenda', 'namaacara', 'divisi', 'panitia', 'panitia_available'));

    }

    public function checkin($agenda_id)
    {
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::findOrFail($agenda_id);
        $absensi = DB::table('absensi')
            ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
            ->join('acara_user', function ($join) use ($agenda) {
                $join->on('acara_user.user_id', '=', 'users.id')
                     ->where('acara_user.acara_id', '=', $agenda->acara_id);
            })
            ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
            ->where('absensi.agenda_id', '=', $agenda_id)
            ->select('absensi.*', 'absensi.id as absensi_id', 'users.name', 'divisi.nama as nama_divisi')
            ->orderBy('absensi.waktu_masuk', 'asc')
            ->get();

        return view('absensi.checkin', compact('agenda', 'absensi'));
    }

    public function checkout($agenda_id)
    {
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::findOrFail($agenda_id);
        $absensi = DB::table('absensi')
            ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
            ->join('acara_user', function ($join) use ($agenda) {
                $join->on('acara_user.user_id', '=', 'users.id')
                     ->where('acara_user.acara_id', '=', $agenda->acara_id);
            })
            ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
            ->where('absensi.agenda_id', '=', $agenda_id)
            ->select('absensi.*', 'users.name', 'divisi.nama as nama_divisi')
            ->orderByRaw('CASE WHEN absensi.waktu_pulang IS NOT NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('absensi.waktu_pulang', 'asc')
            ->orderBy('absensi.waktu_masuk', 'asc')
            ->get();

        return view('absensi.checkout', compact('agenda', 'absensi'));
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
            'nama' => 'required|string|max:255',
            'checkin' => 'required',
            'batas_checkin' => 'required',
            'checkout' => 'required',
            'batas_checkout' => 'required',
            'acara_id' => 'required',
        ]);

        $completed_payload = array_merge($data, [
            'checkin' => Carbon::parse($request->input('checkin')),
            'batas_checkin' => Carbon::parse($request->input('batas_checkin')),
            'checkout' => Carbon::parse($request->input('checkout')),
            'batas_checkout' => Carbon::parse($request->input('batas_checkout')),
        ]);

        Agenda::create($completed_payload);

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($request->input('acara_id'))]);
    }

    public function checkin_panitia(Request $request, $id_agenda)
    {
        $nowImmutable = new DateTimeImmutable;
        $jam = $nowImmutable->format('Y-m-d H:i:s');
        $rfid = trim($request->input('rfid'));
        $agenda = Agenda::findOrFail($id_agenda);
        $user = DB::table('users')->where('rfid_uid', $rfid)->first();
        
        // Cek apakah user terdaftar
        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu RFID tidak terdaftar dalam sistem!',
                ]);
            }
            return redirect()->route('checkin', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Kartu RFID tidak terdaftar!');
        }

        // Cek apakah panitia terdaftar di acara ini
        $cek_panitia = DB::table('acara_user')
            ->join('divisi', 'acara_user.divisi_id', '=', 'divisi.id')
            ->where('acara_user.user_id', '=', $user->id)
            ->where('acara_user.acara_id', '=', $agenda->acara_id)
            ->select('acara_user.*', 'divisi.nama as nama_divisi')
            ->first();

        if (!$cek_panitia) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia tidak terdaftar di acara ini!',
                ]);
            }
            return redirect()->route('checkin', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Panitia tidak terdaftar di acara ini!');
        }

        $cek_absensi = Absensi::where('rfid_uid', '=', $rfid)
                        ->where('agenda_id', '=', $id_agenda)
                        ->first();

        if ($cek_absensi && $cek_absensi->waktu_masuk) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia sudah melakukan check-in!',
                ]);
            }
            return redirect()->route('checkin', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Panitia sudah melakukan check-in!');
        }

        // Status kehadiran otomatis berdasarkan batas_checkin
        $status = 'hadir';
        if ($agenda->batas_checkin && Carbon::parse($jam)->greaterThan(Carbon::parse($agenda->batas_checkin))) {
            $status = 'terlambat';
        }

        Absensi::updateOrCreate(
            [
                'agenda_id' => $id_agenda,
                'rfid_uid' => $rfid,
            ],
            [
                'waktu_masuk' => $jam,
                'status' => $status,
                'keterangan' => ($cek_absensi && $cek_absensi->keterangan && $cek_absensi->keterangan !== '-') ? $cek_absensi->keterangan : '-',
            ]
        );

        if ($request->wantsJson() || $request->ajax()) {
            try {
                $absensi = DB::table('absensi')
                    ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
                    ->join('acara_user', function ($join) use ($agenda) {
                        $join->on('acara_user.user_id', '=', 'users.id')
                             ->where('acara_user.acara_id', '=', $agenda->acara_id);
                    })
                    ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                    ->where('absensi.agenda_id', '=', $id_agenda)
                    ->select('absensi.*', 'absensi.id as absensi_id', 'users.name', 'divisi.nama as nama_divisi')
                    ->orderBy('absensi.waktu_masuk', 'asc')
                    ->get();

                $htmlTabel = view('partials.tabel_absensi', compact('absensi', 'agenda'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil scan check-in!',
                    'html' => $htmlTabel,
                    'nama' => $user->name,
                    'divisi' => $cek_panitia->nama_divisi,
                    'status' => $status,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error PHP: '.$e->getMessage().' di baris '.$e->getLine(),
                ], 200);
            }
        }

        return redirect()->route('checkin', ['id_agenda' => encrypt($id_agenda)]);
    }

    public function checkout_panitia(Request $request, $id_agenda)
    {
        $nowImmutable = new DateTimeImmutable;
        $jam = $nowImmutable->format('Y-m-d H:i:s');
        $rfid = trim($request->input('rfid'));
        $agenda = Agenda::findOrFail($id_agenda);
        $user = DB::table('users')->where('rfid_uid', $rfid)->first();
        
        // Cek apakah user terdaftar
        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu RFID tidak terdaftar dalam sistem!',
                ]);
            }
            return redirect()->route('checkout', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Kartu RFID tidak terdaftar!');
        }

        // Cek apakah panitia terdaftar di acara ini
        $cek_panitia = DB::table('acara_user')
            ->join('divisi', 'acara_user.divisi_id', '=', 'divisi.id')
            ->where('acara_user.user_id', '=', $user->id)
            ->where('acara_user.acara_id', '=', $agenda->acara_id)
            ->select('acara_user.*', 'divisi.nama as nama_divisi')
            ->first();

        if (!$cek_panitia) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia tidak terdaftar di acara ini!',
                ]);
            }
            return redirect()->route('checkout', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Panitia tidak terdaftar di acara ini!');
        }

        // Pastikan panitia sudah melakukan check-in untuk agenda ini
        $cek_absensi = Absensi::where('rfid_uid', '=', $rfid)
                        ->where('agenda_id', '=', $id_agenda)
                        ->first();

        if (!$cek_absensi || !$cek_absensi->waktu_masuk) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia belum melakukan check-in pada agenda ini!',
                ]);
            }
            return redirect()->route('checkout', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Panitia belum melakukan check-in!');
        }

        if ($cek_absensi->waktu_pulang != null) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia sudah melakukan check-out!',
                ]);
            }
            return redirect()->route('checkout', ['id_agenda' => encrypt($id_agenda)])->with('error', 'Panitia sudah melakukan check-out!');
        }

        // Update waktu pulang khusus untuk record agenda ini
        $cek_absensi->update(['waktu_pulang' => $jam]);

        if ($request->wantsJson() || $request->ajax()) {
            try {
                $absensi = DB::table('absensi')
                    ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
                    ->join('acara_user', function ($join) use ($agenda) {
                        $join->on('acara_user.user_id', '=', 'users.id')
                             ->where('acara_user.acara_id', '=', $agenda->acara_id);
                    })
                    ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                    ->where('absensi.agenda_id', '=', $id_agenda)
                    ->select('absensi.*', 'users.name', 'divisi.nama as nama_divisi')
                    ->orderByRaw('CASE WHEN absensi.waktu_pulang IS NOT NULL THEN 0 ELSE 1 END ASC')
                    ->orderBy('absensi.waktu_pulang', 'asc')
                    ->orderBy('absensi.waktu_masuk', 'asc')
                    ->get();

                $htmlTabel = view('partials.tabel_checkout', compact('absensi', 'agenda'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil scan check-out!',
                    'html' => $htmlTabel,
                    'nama' => $user->name,
                    'divisi' => $cek_panitia->nama_divisi,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error PHP: '.$e->getMessage().' di baris '.$e->getLine(),
                ], 200);
            }
        }

        return redirect()->route('checkout', ['id_agenda' => encrypt($id_agenda)]);
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
            'nama' => 'required|string|max:255',
            'checkin' => 'required',
            'batas_checkin' => 'required',
            'checkout' => 'required',
            'batas_checkout' => 'required',
        ]);

        $agenda = Agenda::findOrFail($id);
        $agenda->update([
            'nama' => $data['nama'],
            'checkin' => Carbon::parse($data['checkin']),
            'batas_checkin' => Carbon::parse($data['batas_checkin']),
            'checkout' => Carbon::parse($data['checkout']),
            'batas_checkout' => Carbon::parse($data['batas_checkout']),
        ]);

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($agenda->acara_id)])->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $id = decrypt($id);
        $agenda = Agenda::findOrFail($id);
        $acara_id = $agenda->acara_id;
        $agenda->delete();

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($acara_id)])->with('success', 'Agenda berhasil dihapus.');
    }

    public function destroy(string $id)
    {
        //
    }

    public function daftarAbsensi($agenda_id){
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
            'title' => 'Laporan Absensi - ' . $agenda->nama,
            'agenda' => $agenda->nama, 
            'acara' => $acara->nama, 
            'absensi' => $absensi,
            'stats' => $stats,
            'date' => date('d-m-Y H:i:s'),
        ];
        
        return view('absensi.absensi', compact('absensi', 'acara', 'agenda', 'stats'));
    }

    public function updateAbsensi(Request $request)
    {
        $request->validate([
            'agenda_id' => 'required|exists:agenda,id',
            'rfid_uid' => 'required|string',
            'status' => 'required|in:hadir,terlambat,izin,tidak_hadir',
            'waktu_masuk' => 'nullable|string',
            'waktu_pulang' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $agenda = Agenda::findOrFail($request->agenda_id);
        $dateStr = $agenda->checkin ? $agenda->checkin->format('Y-m-d') : now()->format('Y-m-d');

        $waktu_masuk = null;
        if ($request->status === 'hadir' || $request->status === 'terlambat') {
            if ($request->waktu_masuk) {
                $waktu_masuk = $dateStr . ' ' . $request->waktu_masuk . ':00';
            } else {
                $waktu_masuk = now()->format('Y-m-d H:i:s');
            }
        }

        $waktu_pulang = null;
        if ($request->status === 'hadir' || $request->status === 'terlambat') {
            if ($request->waktu_pulang) {
                $waktu_pulang = $dateStr . ' ' . $request->waktu_pulang . ':00';
            }
        }

        Absensi::updateOrCreate(
            [
                'agenda_id' => $request->agenda_id,
                'rfid_uid' => $request->rfid_uid,
            ],
            [
                'waktu_masuk' => $waktu_masuk,
                'waktu_pulang' => $waktu_pulang,
                'status' => $request->status,
                'keterangan' => $request->keterangan ?? '-',
            ]
        );

        return redirect()->back()->with('success', 'Kehadiran berhasil diperbarui!');
    }

    public function updateKeterangan(Request $request)
    {
        $request->validate([
            'absensi_id' => 'nullable',
            'agenda_id' => 'nullable',
            'rfid_uid' => 'nullable|string',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $keterangan = $request->input('keterangan');
        if ($keterangan === null || trim($keterangan) === '') {
            $keterangan = '-';
        } else {
            $keterangan = trim($keterangan);
        }

        $absensi = null;
        if ($request->filled('absensi_id')) {
            $absensi = Absensi::find($request->input('absensi_id'));
        }

        if (!$absensi && $request->filled('agenda_id') && $request->filled('rfid_uid')) {
            $absensi = Absensi::where('agenda_id', $request->input('agenda_id'))
                ->where('rfid_uid', $request->input('rfid_uid'))
                ->first();
        }

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi tidak ditemukan!',
            ], 404);
        }

        $absensi->keterangan = $keterangan;
        $absensi->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keterangan berhasil disimpan!',
                'keterangan' => $keterangan,
            ]);
        }

        return redirect()->back()->with('success', 'Keterangan berhasil disimpan!');
    }
}
