@extends('layouts.app')
@section('main-content')

<div id="page-mahasiswa" class="page active">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div class="flex gap-2 flex-1 flex-wrap">
      <span> {{ $acara->nama }} / {{ $divisi->nama }} </span>
    </div>
    <div class="flex gap-2">
      <button class="btn-secondary no-print" onclick="printSection('mahasiswa-table')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak
      </button>
      <button class="btn-primary" onclick="showModal('modal-tambah-mahasiswa')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Panitia
      </button>
    </div>
  </div>

    <div id="mahasiswa-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <table class="data-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Mahasiswa</th>
          <th>RFID</th>
          <th>Email</th>
          <th>Status</th>
          <th class="no-print">Aksi</th>
        </tr>
      </thead>
      <tbody id="mahasiswa-tbody">
        @foreach ($panitia as $idx => $mhs)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td>
              <div class="text-slate-900 font-500 text-sm">{{ $mhs->name }}</div>
            </td>
            <td>
              <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                {{ $mhs->rfid_uid }}
              </code>
            </td>
            <td>{{ $mhs->email }}</td>
            <td>
              <span class="badge {{ $mhs->is_active == '1' ? 'badge-green' : 'badge-gray' }}">
                {{ $mhs->is_active == '1' ? 'Aktif' : 'Nonaktif' }}
              </span>
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

<div id="modal-tambah-mahasiswa" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-mahasiswa')">
  <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Panitia</h2>
        <p class="text-red-500 text-sm mt-0.5">Hanya bisa menambahkan panitia jika panitia yang ingin ditambahkan belum terdaftar di acara ini</p>
      </div>
      <button
        onclick="closeModal(null,'modal-tambah-mahasiswa')"
        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition"
      >
        ✕
      </button>
    </div>
    <form action="{{route('panitia.store')}}" method="POST">
    
        <div class="space-y-4 mb-4">
      <div>
        <label>Nama Panitia</label>
        <select class="inp" name="user_id" id="panitia-select">
          @forelse ($panitia_available as $panitia)
            <option value="{{$panitia->id}}">{{$panitia->name}}</option>
            @empty
            <option value="-">Tidak ada panitia tersedia</option>
          @endforelse
        </select>
        <input type="hidden" name="divisi_id" value="{{ $divisi->id }}" >
        <input type="hidden" name="acara_id" value="{{ $acara->id }}" >
      </div>
      <hr>
      <p class="text-slate-600 text-xs font-500"><b>Detail Panitia</b></p>
      <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>
            <p class="text-slate-600 text-xs font-500">RFID</p>
            <p id="detail-checkin" class="text-slate-900 font-600 mt-1"></p>
          </div>
          <div>
            <p class="text-blue-600 text-xs font-500" id="rfid-span" >-</p>
            <p id="detail-batas-checkin" class="text-blue-500 font-600 mt-1"></p>
          </div>
        </div>
        <br>
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>
            <p class="text-slate-600 text-xs font-500">Email</p>
            <p id="detail-checkin" class="text-slate-900 font-600 mt-1"></p>
          </div>
          <div>
            <p class="text-blue-500 text-xs font-500" id="email-span">-</p>
            <p id="detail-batas-checkin" class="text-slate-900 font-600 mt-1"></p>
          </div>
        </div>
      </div>
    </div>

        <div class="flex gap-3 mt-6">
      <button
        class="btn-secondary flex-1 justify-center"
        onclick="closeModal(null,'modal-tambah-mahasiswa')"
      >
        Batal
      </button>
      <button
        class="btn-primary flex-1 justify-center"
        
        type="submit"
      >
        Simpan Mahasiswa
      </button>
    </div>
    </form>
  </div>
</div>

<script>
  const panitia = @json($panitia_available);
  const panitiaselect = document.getElementById('panitia-select')
  const rfidspan = document.getElementById('rfid-span')
  const emailspan = document.getElementById('email-span')
  panitiaselect.addEventListener('change',(e)=>{
      let selected_id = panitiaselect.value
      panitia.forEach(element => {
        if(element['id'] == selected_id){
          rfidspan.innerHTML = element['rfid_uid']
          emailspan.innerHTML = element['email']
        }
      });
  })
</script>
@endsection
