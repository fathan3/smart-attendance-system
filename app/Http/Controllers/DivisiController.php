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
            'deskripsi' => 'required',
        ]);
        $completed_payload = array_merge($data, ['acara_id' => $request->input('acara_id')]);
        Divisi::create($completed_payload);

        return redirect()->route('acara.agenda', $request->input('acara_id'));

    }

    public function divisiAgenda($divisi_id)
    {
        $divisi = Divisi::find($divisi_id);
        $acara = Acara::find($divisi->acara_id);
        $panitia_available = DB::table('users')->whereNotIn('id', function ($query) use ($acara) {
            $query->select('user_id')->from('acara_user')->where('acara_id', '=', $acara->id);
        })->get();
        $panitia = DB::table('acara_user')
            ->join('users', 'acara_user.user_id', '=', 'users.id')
            ->where('acara_user.divisi_id', '=', $divisi_id)->get();

        return view('absensi.panitia', compact('panitia', 'divisi', 'acara', 'panitia_available'));
    }

    // Menambahkan Panitia Baru
    public function store_panitia(Request $request)
    {
        $data = [
            'user_id' => $request->input('user_id'),
            'acara_id' => $request->input('acara_id'),
            'divisi_id' => $request->input('divisi_id'),
        ];

        AcaraUser::create($data);

        return redirect()->route('agenda.divisi', $request->input('divisi_id'));

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
