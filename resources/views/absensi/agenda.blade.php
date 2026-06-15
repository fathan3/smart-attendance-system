@extends('layouts.app')
@section('main-content')

<div id="page-acara" class="page active">
    <div class="flex items-center justify-between mb-6">
    <div class="text-sm flex items-center font-display font-600 uppercase tracking-wider text-slate-600 mb-3">
        <a href="/acara">Acara&nbsp;</a>/ 
        {{ $namaacara->nama }}
</div>
    <button class="btn-primary" id="tambah-acara"  onclick="showModal('modal-tambah-acara')">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Tambah Agenda
    </button>
    <button class="btn-primary " id="tambah-divisi" onclick="showModal('modal-tambah-divisi')">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Tambah Divisi
    </button>
  </div>

    <div class="flex gap-2 mb-4 border-b border-slate-200 no-print">
    <button 
      onclick="switchTab('tab-daftar', 'tambah-acara')" 
      id="btn-tab-daftar"
      class="px-4 py-3 font-500 text-sm border-b-2 border-blue-600 text-blue-600 transition"
    >
      <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      Daftar Agenda
    </button>
    <button 
      onclick="switchTab('tab-divisi', 'tambah-divisi')" 
      id="btn-tab-divisi"
      class="px-4 py-3 font-500 text-sm border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition"
    >
      <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      Daftar Divisi
    </button>
    <button 
      onclick="switchTab('tab-panitia', '-')" 
      id="btn-tab-panitia"
      class="px-4 py-3 font-500 text-sm border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition"
    >
      <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      Daftar Panitia
    </button>
  </div>

    <div id="tab-daftar" class="tab-content active">
    <div id="acara-list" class="space-y-4">
    <div id="agenda-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Agenda</th>
            <th>Jam Checkin</th>
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
                  {{ $item->checkin }}
                </code>
              </td>
              <td>
                <code class="text-xs font-display text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                  {{ $item->batas_checkin }}
                </code>
              </td>
              <td class="text-center">
                <a href="/checkin/{{ $item->id }}/" class="text-blue-600 hover:text-blue-700 inline-flex justify-center">
                  <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                  </svg>
                </a>
              </td>
              <td class="text-center">
                <a href="/checkout/{{ $item->id }}/" class="text-red-600 hover:text-red-700 inline-flex justify-center">
                  <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                  </svg>
                </a>
              </td>
              <td class="no-print">
                <div class="flex gap-2">
                  <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama }}', '{{ $item->checkin }}', '{{ $item->batas_checkin }}', '{{ $item->checkout ?? '' }}', '{{ $item->batas_checkout ?? '' }}')">Detail</button>
                  <button class="btn-secondary py-1.5 px-3 text-xs" onclick="showEditModal('{{ $item->id }}', '{{ $item->nama }}', '{{ $item->checkin }}', '{{ $item->batas_checkin }}', '{{ $item->checkout ?? '' }}', '{{ $item->batas_checkout ?? '' }}')" >Edit</button>
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
  </div>

    <div id="tab-divisi" class="tab-content hidden">
    <div id="template-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Divisi</th>
            <th>Deskripsi</th>
            <th>Panitia</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody id="template-tbody">
                     @foreach ($divisi as $idx => $div )
             
           <tr class="bg-slate-50 border-b border-slate-100">
             <td class="text-slate-400">{{ $idx + 1 }}</td>
             <td class="text-slate-400">{{ $div->nama }}</td>
             <td class="text-slate-400">{{ $div->deskripsi }}</td>
             <td class="text-blue-400"><a href="{{ route('agenda.divisi', $div->id)  }}">Panitia</a></td>
             <td class="no-print">
               <div class="flex gap-2">
                 <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled opacity-50 cursor-not-allowed" disabled>Detail</button>
                 <button class="btn-secondary py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Edit</button>
                 <button class="btn-danger py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Hapus</button>
               </div>
             </td>
           </tr>
           
           @endforeach
        </tbody>
      </table>
    </div>
  </div>
    <div id="tab-panitia" class="tab-content hidden">
    <div id="template-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>RFID</th>
            <th>Divisi</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody id="template-tbody">
                    <tr class="bg-slate-50 border-b border-slate-100">
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="no-print">
              <div class="flex gap-2">
                <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled opacity-50 cursor-not-allowed" disabled>Detail</button>
                <button class="btn-secondary py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Edit</button>
                <button class="btn-danger py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Hapus</button>
              </div>
            </td>
          </tr>
          <tr class="bg-slate-50 border-b border-slate-100">
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="no-print">
              <div class="flex gap-2">
                <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled opacity-50 cursor-not-allowed" disabled>Detail</button>
                <button class="btn-secondary py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Edit</button>
                <button class="btn-danger py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Hapus</button>
              </div>
            </td>
          </tr>
          <tr class="bg-slate-50 border-b border-slate-100">
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="text-slate-400">-</td>
            <td class="no-print">
              <div class="flex gap-2">
                <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled opacity-50 cursor-not-allowed" disabled>Detail</button>
                <button class="btn-secondary py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Edit</button>
                <button class="btn-danger py-1.5 px-3 text-xs disabled opacity-50 cursor-not-allowed" disabled>Hapus</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

<div id="modal-tambah-acara" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-acara')">
  <div class="modal-box" onclick="event.stopPropagation()">
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

         <form method="POST" action="{{route('agenda.store')}}" >
      @csrf
    <div class="space-y-4">
      <input type="hidden" name="acara_id" value="{{ $namaacara->id }}">
      <div>
        <label>Nama Agenda</label>
        <input name="nama" required type="text" class="inp" placeholder="Contoh: Pembukaan">
      </div>

      <label><b>Checkin</b></label>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Jam Mulai Checkin</label>
          <input type="datetime-local" required name="checkin" class="inp">
        </div>
        <div>
          <label>Jam Selesai Checkin</label>
          <input type="datetime-local" required name="batas_checkin" class="inp">
        </div>
      </div>
      <hr>
      <label><b>Checkout</b></label>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Jam Mulai Checkout</label>
          <input type="datetime-local" required name="checkout" class="inp">
        </div>
        <div>
          <label>Jam Selesai Checkout</label>
          <input type="datetime-local" required name="batas_checkout" class="inp">
        </div>
      </div>

          </div>
        <div class="flex gap-3 mt-6">
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="closeModal(null,'modal-tambah-acara')"
      >
        Batal
      </button>
      <button type="submit"
        class="btn-primary flex-1 justify-center"
      >
        Simpan
      </button>
    </div>
     </form>
  </div>
</div>
<div id="modal-tambah-divisi" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-divisi')">
  <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Divisi</h2>
        <p class="text-slate-500 text-sm mt-0.5">Tambah Divisi Baru di Acara <b>{{ $namaacara->nama }}</b></p>
      </div>
      <button
        onclick="closeModal(null,'modal-tambah-divisi')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>

         <form method="POST" action="{{route('divisi.store')}}" >
      @csrf
    <div class="space-y-4">
      <input type="hidden" name="acara_id" value="{{ $namaacara->id }}">
      <div>
        <label>Nama Divisi</label>
        <input name="nama" required type="text" class="inp" placeholder="Contoh: Pembukaan">
      </div>

      <div>
        <label>Deskripsi</label>
        <textarea name="deskripsi" id="" class="inp"></textarea>
      </div>
          </div>
        <div class="flex gap-3 mt-6">
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="closeModal(null,'modal-tambah-divisi')"
      >
        Batal
      </button>
      <button type="submit"
        class="btn-primary flex-1 justify-center"
      >
        Simpan
      </button>
    </div>
     </form>
  </div>
</div>

<div id="modal-detail-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-acara')">
  <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
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

        <div class="space-y-4">
      <div class="bg-slate-50 p-4 rounded-lg">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-xs text-slate-600 font-600 uppercase tracking-wide">Nama Agenda</p>
            <p id="detail-nama" class="text-slate-900 font-500 mt-1"></p>
          </div>
        </div>
      </div>

      <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h3 class="text-sm font-600 text-blue-900 mb-3 flex items-center gap-2">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0z"/>
          </svg>
          Jadwal Checkin
        </h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>
            <p class="text-slate-600 text-xs font-500">Jam Mulai</p>
            <p id="detail-checkin" class="text-slate-900 font-600 mt-1"></p>
          </div>
          <div>
            <p class="text-slate-600 text-xs font-500">Batas Checkin</p>
            <p id="detail-batas-checkin" class="text-slate-900 font-600 mt-1"></p>
          </div>
        </div>
      </div>

      <div class="bg-red-50 p-4 rounded-lg border border-red-200">
        <h3 class="text-sm font-600 text-red-900 mb-3 flex items-center gap-2">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0z"/>
          </svg>
          Jadwal Checkout
        </h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>
            <p class="text-slate-600 text-xs font-500">Jam Mulai</p>
            <p id="detail-checkout" class="text-slate-900 font-600 mt-1">-</p>
          </div>
          <div>
            <p class="text-slate-600 text-xs font-500">Batas Checkout</p>
            <p id="detail-batas-checkout" class="text-slate-900 font-600 mt-1">-</p>
          </div>
        </div>
      </div>
    </div>

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
        onclick="printDetailAgenda()"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak
      </button>
      <button
        class="btn-secondary justify-center"
        onclick="closeModal(null,'modal-detail-acara')"
      >
        Tutup
      </button>
    </div>
  </div>
</div>

<div id="modal-edit-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-edit-acara')">
  <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
      <div>
        <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Edit Agenda</h2>
        <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
      </div>
      <button
        onclick="closeModal(null,'modal-edit-acara')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>
<form method="POST" action="" id="form-edit-agenda">
      @csrf
    <div class="space-y-4">
      <div>
        <label>Nama Agenda</label>
        <input name="nama" required type="text" id="edit-agenda-nama" class="inp" placeholder="Contoh: Pembukaan">
        <input name="acara_id" required type="hidden"  class="inp" value="{{ $namaacara->id }}">
      </div>

      <label><b>Checkin</b></label>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Jam Mulai Checkin</label>
          <input type="datetime-local" id="edit-agenda-checkin" required name="checkin" class="inp">
        </div>
        <div>
          <label>Jam Selesai Checkin</label>
          <input type="datetime-local" required id="edit-agenda-batas-checkin" name="batas_checkin" class="inp">
        </div>
      </div>
      <hr>
      <label><b>Checkout</b></label>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Jam Mulai Checkout</label>
          <input type="datetime-local" required id="edit-agenda-checkout" name="checkout" class="inp">
        </div>
        <div>
          <label>Jam Selesai Checkout</label>
          <input type="datetime-local" required id="edit-agenda-batas-checkout" name="batas_checkout" class="inp">
        </div>
      </div>

          </div>
        <div class="flex gap-3 mt-6">
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="closeModal(null,'modal-tambah-acara')"
      >
        Batal
      </button>
      <button type="submit"
        class="btn-primary flex-1 justify-center"
      >
        Simpan
      </button>
    </div>
     </form>

        
  </div>
</div>

@endsection

<script>
  const agenda = true
function showDetailModal(id, nama, checkin, batasCheckin, checkout, batasCheckout) {
  document.getElementById('detail-nama').textContent = nama;
  document.getElementById('detail-checkin').textContent = checkin || '-';
  document.getElementById('detail-batas-checkin').textContent = batasCheckin || '-';
  document.getElementById('detail-checkout').textContent = checkout || '-';
  document.getElementById('detail-batas-checkout').textContent = batasCheckout || '-';
  
  showModal('modal-detail-acara');
}

function showEditModal(id, nama, checkin, batasCheckin, checkout, batasCheckout) {
  document.getElementById('form-edit-agenda').action = `/agenda/update/${id}`
  document.getElementById('edit-agenda-nama').value = nama;
  document.getElementById('edit-agenda-checkin').value = checkin || '-';
  document.getElementById('edit-agenda-batas-checkin').value = batasCheckin || '-';
  document.getElementById('edit-agenda-checkout').value = checkout || '-';
  document.getElementById('edit-agenda-batas-checkout').value = batasCheckout || '-';
  
  showModal('modal-edit-acara');
}

function printDetailAgenda() {
  window.print();
}

function switchTab(tabName, btnName) {
  const tabs = document.querySelectorAll('.tab-content');
  tabs.forEach(tab => tab.classList.add('hidden'));
  
  const buttons = document.querySelectorAll('[id^="btn-tab-"]');
  const tambah = document.querySelectorAll('[id^="tambah-"]');

  tambah.forEach(bt => {
    bt.classList.add('hidden')
  })
  buttons.forEach(btn => {
    btn.classList.remove('border-blue-600', 'text-blue-600');
    btn.classList.add('border-transparent', 'text-slate-600', 'hover:text-slate-900', 'hover:border-slate-300');
  });
  
  const selectedTab = document.getElementById(tabName);
  if (selectedTab) {
    selectedTab.classList.remove('hidden');
  }
  
  const selectedButton = document.getElementById('btn-' + tabName);
  if (selectedButton) {
    selectedButton.classList.remove('border-transparent', 'text-slate-600', 'hover:text-slate-900', 'hover:border-slate-300');
    selectedButton.classList.add('border-blue-600', 'text-blue-600');
  }
  
  localStorage.setItem('agendaActiveTab', tabName);
  if(btnName != '-'){
    btnn = document.getElementById(btnName)
    btnn.classList.remove('hidden')
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const activeTab = localStorage.getItem('agendaActiveTab') || 'tab-daftar';
  switchTab(activeTab, 'tambah-acara');
});

</script>