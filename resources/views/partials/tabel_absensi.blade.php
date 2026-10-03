@forelse($absensi as $idx => $mhs)
    @php
        $absId = $mhs->absensi_id ?? $mhs->id;
        $keteranganVal = ($mhs->keterangan && $mhs->keterangan !== '-') ? $mhs->keterangan : '';
    @endphp
    <tr data-absensi-id="{{ $absId }}">
        <td class="text-center font-medium text-slate-500">{{ $idx + 1 }}</td>
        <td class="font-semibold text-slate-800">{{ $mhs->name }}</td>
        <td class="text-slate-600">{{ $mhs->nama_divisi ?? $mhs->nama ?? '-' }}</td>
        <td class="text-slate-700 font-medium">{{ $mhs->waktu_masuk ? \Carbon\Carbon::parse($mhs->waktu_masuk)->format('H:i:s') : '-' }}</td>
        <td>
            @if ($agenda && $agenda->batas_checkin && \Carbon\Carbon::parse($mhs->waktu_masuk)->greaterThan(\Carbon\Carbon::parse($agenda->batas_checkin)))
                <span class="badge badge-orange text-xs">Terlambat</span>
            @else
                <span class="badge badge-green text-xs">Tepat Waktu</span>
            @endif
        </td>
        <td class="keterangan-cell">
            <div class="keterangan-wrapper flex items-center gap-1.5 no-print">
                <div class="relative flex-1">
                    <input type="text"
                        class="keterangan-input text-xs px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition w-full text-slate-700 placeholder:text-slate-400 font-medium shadow-sm"
                        data-id="{{ $absId }}"
                        data-rfid="{{ $mhs->rfid_uid }}"
                        data-agenda="{{ $agenda->id ?? $mhs->agenda_id }}"
                        data-original="{{ $keteranganVal }}"
                        placeholder="Isi keterangan..."
                        value="{{ $keteranganVal }}"
                        title="Ketik keterangan, tekan Enter atau klik ikon simpan"
                    >
                    <span class="indicator-saved absolute right-2.5 top-1/2 -translate-y-1/2 text-emerald-500 text-xs hidden items-center pointer-events-none">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <button type="button"
                    class="btn-save-keterangan p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-200 transition flex-shrink-0"
                    title="Simpan Keterangan">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
            <span class="keterangan-print-text hidden print-only text-xs">{{ $keteranganVal ?: '-' }}</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-3 text-center text-slate-500">Belum ada data absensi.</td>
    </tr>
@endforelse
