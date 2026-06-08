@extends('layouts.app')
@section('main-content')
<div id="page-acara" class="page active">
      <div class="flex items-center justify-between mb-6">
        <div class="flex gap-2">
          <button class="btn-secondary no-print" onclick="printSection('acara-list')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak</button>
          <button class="btn-primary" onclick="showModal('modal-tambah-acara')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Tambah Acara</button>
        </div>
      </div>
      <div id="acara-list" class="space-y-4">
        <!-- Populated by JS -->
      </div>
    </div>

    <div id="modal-tambah-acara" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-acara')">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between mb-6">
      <div><h2 class="font-display font-800 text-slate-900 text-xl">Tambah Acara</h2><p class="text-slate-500 text-sm mt-0.5">Buat acara baru dengan agenda</p></div>
      <button onclick="closeModal(null,'modal-tambah-acara')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-ink-600 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">✕</button>
    </div>
    <div class="space-y-4">
      <div><label>Nama Acara</label><input type="text" class="inp" placeholder="Contoh: Seminar Nasional AI 2025"></div>
      <div class="grid grid-cols-2 gap-4">
        <div><label>Tanggal Mulai</label><input type="date" class="inp"></div>
        <div><label>Tanggal Selesai</label><input type="date" class="inp"></div>
      </div>
      <div><label>Lokasi</label><input type="text" class="inp" placeholder="Nama ruangan / tempat"></div>
      <div><label>Deskripsi</label><textarea class="inp h-20 resize-none" placeholder="Deskripsi singkat acara"></textarea></div>

      <!-- Agenda Generator -->
      <div class="border border-ink-600 rounded-xl p-4 bg-white">
        <div class="flex items-center justify-between mb-3">
          <div class="text-sm font-display font-600 text-slate-900">Agenda Acara</div>
          <button class="btn-green text-xs py-1.5 px-3" onclick="addAgendaRow()"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>+ Agenda</button>
        </div>
        <div id="agenda-rows" class="space-y-2">
          <div class="agenda-row grid grid-cols-12 gap-2 items-center">
            <input type="text" class="inp col-span-5 text-sm py-2" placeholder="Nama agenda">
            <input type="time" class="inp col-span-3 text-sm py-2">
            <input type="time" class="inp col-span-3 text-sm py-2">
            <button class="col-span-1 text-red-400 hover:text-red-300 flex items-center justify-center" onclick="removeAgenda(this)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
          </div>
        </div>
        <div class="flex gap-2 mt-2">
          <div class="text-xs text-slate-400 col-span-5">Nama Agenda</div>
          <div class="text-xs text-slate-400 col-span-3">Jam Mulai</div>
          <div class="text-xs text-slate-400 col-span-3">Jam Selesai</div>
        </div>
        <button class="btn-secondary w-full justify-center mt-3 text-sm py-2" onclick="generateAgenda()"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Generate Agenda Otomatis</button>
      </div>
    </div>
    <div class="flex gap-3 mt-6">
      <button class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-tambah-acara')">Batal</button>
      <button class="btn-primary flex-1 justify-center" onclick="saveAcara()">Simpan Acara</button>
    </div>
  </div>
</div>

<div id="modal-detail-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-acara')">
  <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between mb-6">
      <div><h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Detail Acara</h2><p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p></div>
      <button onclick="closeModal(null,'modal-detail-acara')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-ink-600 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">✕</button>
    </div>
    <div id="detail-agenda-list" class="space-y-3"></div>
    <div class="mt-4 pt-4 border-t border-slate-200 flex gap-2">
      <button class="btn-primary" onclick="closeModal(null,'modal-detail-acara');navigate('absensi')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>Buka Absensi</button>
      <button class="btn-secondary" onclick="printSection('detail-agenda-list')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak</button>
    </div>
  </div>
</div>
@endsection