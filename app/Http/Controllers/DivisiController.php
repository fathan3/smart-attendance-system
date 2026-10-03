<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\AcaraUser;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'deskripsi' => 'nullable|string',
            'acara_id' => 'required',
        ]);
        Divisi::create($data);

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($request->input('acara_id'))])
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function divisiAgenda($divisi_id)
    {
        $divisi_id = decrypt($divisi_id);
        $divisi = Divisi::findOrFail($divisi_id);
        $acara = Acara::findOrFail($divisi->acara_id);
        $panitia_available = DB::table('users')->whereNotIn('id', function ($query) use ($acara) {
            $query->select('user_id')->from('acara_user')->where('acara_id', '=', $acara->id);
        })
        ->where('users.is_active', '=', '1')
        ->get();
        $panitia = DB::table('acara_user')
            ->join('users', 'acara_user.user_id', '=', 'users.id')
            ->where('acara_user.divisi_id', '=', $divisi_id)
            ->select('acara_user.id as pivot_id', 'users.*')
            ->get();

        return view('absensi.panitia', compact('panitia', 'divisi', 'acara', 'panitia_available'));
    }

    // Menambahkan Panitia Baru
    public function store_panitia(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'acara_id' => 'required|exists:acara,id',
            'divisi_id' => 'required|exists:divisi,id',
        ]);

        $userIds = is_array($request->input('user_id')) ? $request->input('user_id') : [$request->input('user_id')];
        $count = 0;

        foreach ($userIds as $uid) {
            $exists = DB::table('acara_user')
                ->where('acara_id', $request->acara_id)
                ->where('user_id', $uid)
                ->exists();

            if (!$exists) {
                AcaraUser::create([
                    'user_id' => $uid,
                    'acara_id' => $request->input('acara_id'),
                    'divisi_id' => $request->input('divisi_id'),
                ]);
                $count++;
            }
        }

        if ($count === 0) {
            return back()->with('error', 'Mahasiswa yang dipilih sudah terdaftar di acara ini!');
        }

        return back()->with('success', $count > 1 ? "$count panitia berhasil ditambahkan." : 'Panitia berhasil ditambahkan.');
    }

    // Menghapus Divisi
    public function delete_divisi($id)
    {
        $id = decrypt($id);
        $divisi = Divisi::findOrFail($id);
        $acara_id = $divisi->acara_id;

        // Hapus penugasan panitia di divisi ini terlebih dahulu
        DB::table('acara_user')->where('divisi_id', $id)->delete();
        $divisi->delete();

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($acara_id)])
            ->with('success', 'Divisi berhasil dihapus.');
    }

    // Menghapus Panitia dari Divisi
    public function delete_panitia($id)
    {
        $id = decrypt($id);
        $au = DB::table('acara_user')->where('id', $id)->first();
        if ($au) {
            DB::table('acara_user')->where('id', $id)->delete();
            return back()->with('success', 'Panitia berhasil dihapus.');
        }
        return back()->with('error', 'Data panitia tidak ditemukan.');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
