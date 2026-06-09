<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
{
    $mahasiswa = User::query()
        ->when($request->search, fn($q, $v) =>
            $q->where('name', 'like', "%$v%")
              ->orWhere('email', 'like', "%$v%")
              ->orWhere('rfid_uid', 'like', "%$v%")
        )
        ->when($request->divisi, fn($q, $v) =>
            $q->whereHas('acara', fn($q) =>
                $q->wherePivot('divisi_id', $v)
            )
        )
        ->paginate(20);

    return view('absensi.mahasiswa', compact('mahasiswa'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users',
        'rfid_uid' => 'required|string|unique:users',
        'photo'    => 'nullable|image|max:2048',
        'password' => 'required|min:8',
    ]);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('photos', 'public');
    }

    $data['password'] = bcrypt($data['password']);

    User::create($data);

    return redirect()->route('mahasiswa.index')
                     ->with('success', 'Mahasiswa berhasil ditambahkan.');
}

public function update(Request $request, User $mahasiswa)
{
    $data = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => "required|email|unique:users,email,{$mahasiswa->id}",
        'rfid_uid' => "required|string|unique:users,rfid_uid,{$mahasiswa->id}",
        'photo'    => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('photos', 'public');
    }

    $mahasiswa->update($data);

    return redirect()->route('mahasiswa.index')
                     ->with('success', 'Data mahasiswa diperbarui.');
}
}
