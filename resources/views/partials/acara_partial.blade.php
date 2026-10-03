@forelse($acara as $idx => $acr)
    <tr>
        <td>{{ $idx + 1 }}</td>
        <td>
            <div class="text-slate-900 font-500 text-sm">{{ $acr->nama }}</div>
        </td>
        <td>
            <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                {{ $acr->deskripsi }}
            </code>
        </td>
        <td>{{ $acr->tanggal_mulai->format('d-m-Y') }}</td>
        <td>{{ $acr->lokasi }}</td>
        <td class="no-print">
            <div class="flex gap-2">
                <a href="{{ route('acara.agenda', ['acara_id' => encrypt($acr->id)]) }}"
                    class="btn-primary py-1.5 px-3 text-xs">Detail</a>
                <button class="btn-secondary py-1.5 px-3 text-xs" data-id="{{ $acr->id }}"
                    data-nama="{{ $acr->nama }}"
                    data-tgl-mulai="{{ $acr->tanggal_mulai->format('Y-m-d') }}"
                    data-tgl-selesai="{{ $acr->tanggal_selesai->format('Y-m-d') }}"
                    data-lokasi="{{ $acr->lokasi }}" data-deskripsi="{{ $acr->deskripsi }}"
                    onclick="openEditAcara(this)">Edit</button>
                <button class="btn-danger py-1.5 px-3 text-xs"
                    onclick="confirmDeleteAcara('{{ encrypt($acr->id) }}', '{{ addslashes($acr->nama) }}')">Hapus</button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="p-3 text-center text-slate-500">Belum ada data acara.</td>
    </tr>
@endforelse
