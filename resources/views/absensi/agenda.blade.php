@extends('layouts.app')
@section('main-content')

<div id="page-acara" class="page active">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <p class="text-blue-600">
        <a href="/acara">Acara </a>/ 
        {{ $namaacara->nama }}
</p>
    <button class="btn-primary" onclick="showModal('modal-tambah-acara')">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Tambah Agenda
    </button>
  </div>

  <!-- Content -->
  <div id="acara-list" class="space-y-4">
    <div id="agenda-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Agenda</th>
            <th>Jam Mulai</th>
            <th>Batas Checkin</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody id="agenda-tbody">
          @foreach ($agenda as $idx => $item)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td>
                <div class="text-slate-900 font-500 text-sm">{{ $item->nama }}</div>
              </td>
              <td>
                <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                  {{ $item->jam_mulai }}
                </code>
              </td>
              <td>
                <code class="text-xs font-display text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                  {{ $item->batas_absen_masuk }}
                </code>
              </td>
              <td>
                <a href="/checkin/{{ $item->id }}/" class="text-blue-600 hover:text-blue-700">
                  <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                  </svg>
                </a>
              </td>
              <td>
                <a href="/checkout/{{ $item->id }}/" class="text-red-600 hover:text-red-700">
                  <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                  </svg>
                </a>
              </td>
              <td class="no-print">
                <div class="flex gap-2">
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

<!-- Modal: Tambah Agenda -->
<div id="modal-tambah-acara" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-acara')">
  <div class="modal-box" onclick="event.stopPropagation()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Agenda</h2>
        <p class="text-slate-500 text-sm mt-0.5">Buat agenda baru untuk acara</p>
      </div>
      <button
        onclick="closeModal(null,'modal-tambah-acara')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>

    <!-- Form -->
    <div class="space-y-4">
      <div>
        <label>Nama Agenda</label>
        <input type="text" class="inp" placeholder="Contoh: Pembukaan">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Jam Mulai</label>
          <input type="time" class="inp">
        </div>
        <div>
          <label>Jam Selesai</label>
          <input type="time" class="inp">
        </div>
      </div>

      <div>
        <label>Batas Checkin Masuk</label>
        <input type="time" class="inp">
      </div>

      <!-- Agenda Items -->
      <div class="border border-slate-300 rounded-xl p-4 bg-slate-50">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-display font-600 text-slate-900">Detail Agenda</h3>
          <button
            class="btn-green text-xs py-1.5 px-3"
            onclick="addAgendaRow()"
          >
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Item
          </button>
        </div>

        <div id="agenda-rows" class="space-y-2 mb-3">
          <div class="agenda-row grid grid-cols-12 gap-2 items-center">
            <input type="text" class="inp col-span-5 text-sm py-2" placeholder="Nama item">
            <input type="time" class="inp col-span-3 text-sm py-2" placeholder="Mulai">
            <input type="time" class="inp col-span-3 text-sm py-2" placeholder="Selesai">
            <button
              class="col-span-1 text-red-400 hover:text-red-500 flex items-center justify-center transition"
              onclick="removeAgenda(this)"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-12 gap-2 text-xs text-slate-400 mb-3">
          <div class="col-span-5">Nama Item</div>
          <div class="col-span-3">Jam Mulai</div>
          <div class="col-span-3">Jam Selesai</div>
        </div>

        <button class="btn-secondary w-full justify-center text-sm py-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          Generate Otomatis
        </button>
      </div>
    </div>

    <!-- Actions -->
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
        Simpan
      </button>
    </div>
  </div>
</div>

<!-- Modal: Detail Agenda -->
<div id="modal-detail-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-acara')">
  <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Detail Agenda</h2>
        <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
      </div>
      <button
        onclick="closeModal(null,'modal-detail-acara')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>

    <!-- Content -->
    <div id="detail-agenda-list" class="space-y-3"></div>

    <!-- Actions -->
    <div class="mt-6 pt-6 border-t border-slate-200 flex gap-2">
      <button
        class="btn-primary flex-1 justify-center"
        onclick="closeModal(null,'modal-detail-acara');navigate('absensi')"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        Absensi
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