@extends('layouts.app')
@section('main-content')

<div id="page-absensi" class="page active">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-center mb-4">
        <div class="font-display font-700 text-slate-900 mb-4">Chek-in {{ $agenda->nama }}</div>
        <div class="w-24 h-24 rounded-full border-2 border-blue-600 mx-auto flex items-center justify-center rfid-pulse mb-4 relative">
          <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
          </svg>
        </div>
        <div id="rfid-status" class="text-slate-500 text-sm mb-4">Tempelkan kartu RFID...</div>
        <form id="form-scan" method="POST" action="{{ route('checkin.agenda', $agenda->id) }}">
  @csrf <input type="text" autofocus class="inp" autocomplete="off" id="scan" name="rfid" placeholder="Scan RFID di sini...">
</form>

<div id="notifikasi" class="mt-4 text-sm font-semibold"></div>
        <div id="absen-result" class="mt-3 hidden"></div>
      </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <div class="text-xs font-display font-600 uppercase tracking-wider text-slate-400 mb-3">Batas Waktu Absen</div>
        <div class="space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-slate-600">Absen Masuk</span>
            <span class="text-blue-600 font-600">{{ $agenda->checkin->format('H:i') }} - {{ $agenda->batas_checkin->format('H:i') }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-slate-600">Absen Pulang</span>
            <span class="text-red-600 font-600">{{ $agenda->checkout->format('H:i') }} - {{ $agenda->batas_checkout->format('H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

        <div class="lg:col-span-2">
      <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between p-5 border-b border-slate-200">
          <h3 class="font-display font-700 text-slate-900">Log Absensi Hari Ini</h3>
          <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="printSection('absensi-log')">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak
          </button>
        </div>

                <div id="absensi-log">
          <table class="data-table">
            <thead>
              <tr>
                <th>Mahasiswa</th>
                <th>Agenda</th>
                <th>Jam Masuk</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="tabel-absensi">
              @include('partials.tabel_absensi', ['absensis' => $absensi])
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const formScan = document.getElementById('form-scan');
    const inputScan = document.getElementById('scan');
    const notifikasi = document.getElementById('notifikasi');
    const tbodyAbsensi = document.getElementById('tabel-absensi');
    
    const rfidPulseContainer = document.querySelector('.rfid-pulse'); 

    if (inputScan && rfidPulseContainer) {
        inputScan.addEventListener('focus', () => {
            rfidPulseContainer.classList.add('rfid-pulse');
        });
        
        inputScan.addEventListener('blur', () => {
            rfidPulseContainer.classList.remove('rfid-pulse');
        });
    }

    let lastKeyTime = 0;
    inputScan.addEventListener('keydown', function(e) {
        const currentTime = Date.now();
        const timeDifference = currentTime - lastKeyTime;
        
        if (timeDifference > 50 && e.key !== 'Enter') {
            inputScan.value = ''; // Kosongkan input seketika
        }
        lastKeyTime = currentTime;
    });

    formScan.addEventListener('submit', function(e) {
        e.preventDefault();

        const rfidValue = inputScan.value;
        const url = formScan.action;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        if (!rfidValue || rfidValue.trim() === '') return;

        notifikasi.innerHTML = `<span class="text-blue-500">Memproses absensi...</span>`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // Memastikan Laravel mendeteksi request sebagai AJAX
            },
            body: JSON.stringify({ rfid: rfidValue })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                notifikasi.innerHTML = `<span class="text-green-600 font-bold">Scan</span>`;
                
                if (tbodyAbsensi) {
                    tbodyAbsensi.innerHTML = data.html;
                }
                let timerInterval;
                  Swal.fire({
                    title: "Berhasil Checkin!",
                    html: 
                    `
                    <table width="100%">
                    <tr>
                      <td>Nama</td>
                      <td>${data.nama}</td>
                    </tr>
                    <tr>
                      <td>Divisi</td>
                      <td>Ini divisi</td>
                    </tr>
                    <table>
                    `
                    ,
                    icon: "success",
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: () => {
                      Swal.showLoading();
                      const timer = Swal.getPopup().querySelector("b");
                      timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                      }, 100);
                    },
                    willClose: () => {
                      clearInterval(timerInterval);
                    }
                  }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) console.log("I was closed by the timer");
                  });
            } else {
                notifikasi.innerHTML = `<span class="text-red-600 font-bold text-sm">❌ ${data.message}</span>`;
                console.error("Detail Error PHP:", data.message);
            }
        })
        .catch(error => {
            console.error('Error Fetch:', error);
            notifikasi.innerHTML = `<span class="text-red-600 font-bold">❌ Gagal terhubung ke server.</span>`;
        })
        .finally(() => {
            inputScan.value = '';
            inputScan.focus();
        });
    });

});
</script>
@endsection