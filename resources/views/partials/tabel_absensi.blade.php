@forelse($absensi as $idx => $mhs)
    <tr>
        <td>{{ $idx + 1 }}</td>
        <td>{{ $mhs->name }}</td>
        <td>{{ $mhs->waktu_masuk }}</td>
        <td>{{ $mhs->status }}</td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="p-3 text-center text-slate-500">Belum ada data absensi.</td>
    </tr>
@endforelse
