<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = User::query()
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%$v%")
                ->orWhere('email', 'like', "%$v%")
                ->orWhere('rfid_uid', 'like', "%$v%")
            )
            ->when($request->divisi, fn ($q, $v) => $q->whereHas('acara', fn ($q) => $q->wherePivot('divisi_id', $v)
            )
            )
            ->orderBy('name', 'asc')
            ->get();

        return view('absensi.mahasiswa', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rfid_uid' => 'required|string|unique:users,rfid_uid',
            'email' => 'nullable|email|unique:users,email',
        ]);
        $completed_payload = [
            'name' => $data['name'],
            'rfid_uid' => $data['rfid_uid'],
            'email' => $request->filled('email') ? $request->input('email') : null,
            'password' => bcrypt('123'),
            'is_active' => 1,
        ];

        User::create($completed_payload);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'rfid_uid' => 'required|string|unique:users,rfid_uid,'.$id,
            'email' => 'nullable|email|unique:users,email,'.$id,
        ]);
        $completed_payload = [
            'name' => $data['name'],
            'rfid_uid' => $data['rfid_uid'],
            'email' => $request->filled('email') ? $request->input('email') : null,
            'is_active' => $request->input('status') == '1' ? 1 : 0,
        ];

        $mhs = User::findOrFail($id);
        $mhs->update($completed_payload);
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $id = decrypt($id);
        $mhs = User::findOrFail($id);
        $mhs->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
