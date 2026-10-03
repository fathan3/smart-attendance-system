<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcaraController extends Controller
{
    public function index()
    {
        $acara = Acara::all();

        return view('absensi.acara', compact('acara'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:255',
        ]);

        Acara::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            
            try {
                $acara = Acara::all();
                $htmlTabel = view('partials.acara_partial', compact('acara'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil Menambahkan',
                    'table' => $htmlTabel
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error PHP: '.$e->getMessage().' di baris '.$e->getLine(),
                ], 200);
            }
        }

        return redirect()->route('acara');
    }

    // Daftarkan mahasiswa ke acara beserta divisinya
    public function daftarkanPeserta(Request $request, Acara $acara)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'divisi_id' => 'required|exists:divisi,id',
        ]);

        $acara->users()->syncWithoutDetaching([
            $request->user_id => ['divisi_id' => $request->divisi_id],
        ]);

        return back()->with('success', 'Peserta berhasil didaftarkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $acara = Acara::findOrFail($id);
        $acara->update($data);

        return redirect()->route('acara');
    }

    public function delete($id)
    {
        $id = decrypt($id);
        $acara = Acara::findOrFail($id);
        // Hapus relasi panitia acara terlebih dahulu agar cascade agenda/divisi berjalan lancar
        DB::table('acara_user')->where('acara_id', $id)->delete();
        $acara->delete();

        return redirect()->route('acara')->with('success', 'Acara berhasil dihapus.');
    }
}
