@extends('layouts.app')
@section('main-content')

<div id="page-acara" class="page active">
    <div class="flex items-center justify-between mb-6">
    <div class="flex gap-2">
      <button class="btn-secondary no-print" onclick="printSection('acara-list')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak
      </button>
      <button class="btn-primary" onclick="showModal('modal-tambah-acara')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Acara
      </button>
    </div>
  </div>

    <div id="acara-list" class="space-y-4">
    <div id="acara-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Acara</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
            <th>Tempat</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody id="acara-tbody">
          @foreach ($acara as $idx => $acr)
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
                  <a href="acara/agenda/{{ $acr->id }}" class="btn-primary py-1.5 px-3 text-xs">Detail</a>
                  <button class="btn-secondary py-1.5 px-3 text-xs">Edit</button>
                  <button class="btn-danger py-1.5 px-3 text-xs">Hapus</button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="modal-tambah-acara" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-acara')">
  <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Acara</h2>
        <p class="text-slate-500 text-sm mt-0.5">Buat acara baru</p>
      </div>
      <button
        onclick="closeModal(null,'modal-tambah-acara')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>

         <form action="{{route('acara.store')}}" method="POST">
      @csrf
    <div class="space-y-4">
      <div>
        <label>Nama Acara</label>
        <input type="text" name="nama"  class="inp" placeholder="Contoh: Seminar Nasional AI 2025">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="inp">
        </div>
        <div>
          <label>Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="inp">
        </div>
      </div>

      <div>
        <label>Lokasi</label>
        <input type="text" name="lokasi" class="inp" placeholder="Nama ruangan / tempat">
      </div>

      <div>
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="inp h-20 resize-none" placeholder="Deskripsi singkat acara"></textarea>
      </div>
      
    </div>

        <div class="flex gap-3 mt-6">
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="closeModal(null,'modal-tambah-acara')"
      >
        Batal
      </button>
      <button
        class="btn-primary flex-1 justify-center"
        onclick="saveAcara()"
      >
        Simpan Acara
      </button>
    </div>
</form>
      </div>
</div>

<div id="modal-detail-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-acara')">
  <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
      <div>
        <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Detail Acara</h2>
        <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
      </div>
      <button
        onclick="closeModal(null,'modal-detail-acara')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>

        <div id="detail-agenda-list" class="space-y-3"></div>

        <div class="mt-6 pt-6 border-t border-slate-200 flex gap-2">
      <button
        class="btn-primary flex-1 justify-center"
        onclick="closeModal(null,'modal-detail-acara');navigate('absensi')"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Buka Absensi
      </button>
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="printSection('detail-agenda-list')"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak
      </button>
    </div>
  </div>
</div>

@endsection