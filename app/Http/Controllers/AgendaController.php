<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($acara_id)
    {
        $agenda = DB::table('agenda')->where('acara_id','=', $acara_id)->get();
        $namaacara = Acara::find($acara_id, ['nama']);
        return view('absensi.agenda', compact('agenda', 'namaacara'));

    }
    public function checkin($agenda_id){
        $agenda = Agenda::find($agenda_id);
        return view('absensi.checkin', compact('agenda'));
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
        //
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
