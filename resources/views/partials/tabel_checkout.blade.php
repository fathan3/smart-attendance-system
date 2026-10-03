@forelse($absensi as $idx => $mhs)
    <tr>
        <td>{{ $idx + 1 }}</td>
        <td>{{ $mhs->name }}</td>
        <td>{{ $mhs->nama_divisi ?? $mhs->nama ?? '-' }}</td>
        <td>{{ $mhs->waktu_masuk ? \Carbon\Carbon::parse($mhs->waktu_masuk)->format('H:i:s') : '-' }}</td>
        <td>
            @if($mhs->waktu_pulang == NULL)
                <span class="badge badge-red text-xs">Belum Checkout</span>
            @else
                {{ \Carbon\Carbon::parse($mhs->waktu_pulang)->format('H:i:s') }}
            @endif
        </td>
        <td>
            @if ($agenda && $agenda->batas_checkin && \Carbon\Carbon::parse($mhs->waktu_masuk)->greaterThan(\Carbon\Carbon::parse($agenda->batas_checkin)))
                <span class="badge badge-orange text-xs">Terlambat</span>
            @else
                <span class="badge badge-green text-xs">Tepat Waktu</span>
            @endif
        </td>
        <td>
            @if($mhs->waktu_pulang == NULL)
                <span class="badge badge-gray text-xs">Belum Checkout</span>
            @elseif ($agenda && $agenda->batas_checkout && \Carbon\Carbon::parse($mhs->waktu_pulang)->greaterThan(\Carbon\Carbon::parse($agenda->batas_checkout)))
                <span class="badge badge-orange text-xs">Terlambat</span>
            @else
                <span class="badge badge-green text-xs">Tepat Waktu</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-3 text-center text-slate-500">Belum ada data absensi.</td>
    </tr>
@endforelse
