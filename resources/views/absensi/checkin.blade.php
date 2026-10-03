@extends('layouts.app')
@section('page_title', 'Check-in Absensi')
@section('page_subtitle', 'Proses check-in dengan RFID')
@section('main-content')
    <div id="page-absensi" class="page active">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-center mb-4">
                    <div class="font-display font-700 text-slate-900 mb-4">Check-in {{ $agenda->nama }}</div>
                    <div
                        class="w-24 h-24 rounded-full border-2 border-blue-600 mx-auto flex items-center justify-center rfid-pulse mb-4 relative">
                        <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                    </div>
                    <div id="rfid-status" class="text-slate-500 text-sm mb-4">Tempelkan kartu RFID...</div>
                    <form id="form-scan" method="POST" action="{{ route('checkin.agenda', $agenda->id) }}">
                        @csrf <input type="text" autofocus class="inp" autocomplete="off" id="scan" style="opacity: 0;"
                            name="rfid" placeholder="Scan RFID di sini...">
                    </form>

                    <div id="notifikasi" class="mt-4 text-sm font-semibold text-green-600 font-bold" style="margin-top: -45px; ">Scan</div>
                    <div id="absen-result" class="mt-3 hidden"></div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
                    <div class="text-xs font-display font-600 uppercase tracking-wider text-slate-400 mb-3">Batas Waktu
                        Absen</div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Absen Masuk</span>
                            <span class="text-blue-600 font-600">{{ $agenda->checkin ? $agenda->checkin->format('H:i') : '-' }} -
                                {{ $agenda->batas_checkin ? $agenda->batas_checkin->format('H:i') : '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Absen Pulang</span>
                            <span class="text-red-600 font-600">{{ $agenda->checkout ? $agenda->checkout->format('H:i') : '-' }} -
                                {{ $agenda->batas_checkout ? $agenda->batas_checkout->format('H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between p-5 border-b border-slate-200">
                        <h3 class="font-display font-700 text-slate-900">Log Absensi Hari Ini</h3>
                        <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="printSection('absensi-log')">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </button>
                    </div>

                    <div id="absensi-log" class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="w-12 text-center">No</th>
                                    <th>Mahasiswa</th>
                                    <th>Divisi</th>
                                    <th>Jam Masuk</th>
                                    <th>Status</th>
                                    <th class="w-64 min-w-[200px]">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-absensi">
                                @include('partials.tabel_absensi', ['absensi' => $absensi, 'agenda' => $agenda])
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
            const updateKeteranganUrl = "{{ route('absensi.update_keterangan') }}";

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
            if (inputScan) {
                inputScan.addEventListener('keydown', function(e) {
                    const currentTime = Date.now();
                    const timeDifference = currentTime - lastKeyTime;

                    if (timeDifference > 50 && e.key !== 'Enter') {
                        inputScan.value = '';
                    }
                    lastKeyTime = currentTime;
                });
            }

            // Handle manual Keterangan saving
            function saveKeterangan(inputEl) {
                if (!inputEl) return;
                const absensiId = inputEl.dataset.id;
                const rfidUid = inputEl.dataset.rfid;
                const agendaId = inputEl.dataset.agenda;
                const keterangan = inputEl.value.trim();
                const csrfToken = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

                const wrapper = inputEl.closest('.keterangan-wrapper');
                const indicator = wrapper ? wrapper.querySelector('.indicator-saved') : null;
                const btnSave = wrapper ? wrapper.querySelector('.btn-save-keterangan') : null;
                const printText = inputEl.closest('td') ? inputEl.closest('td').querySelector('.keterangan-print-text') : null;

                if (btnSave) {
                    btnSave.innerHTML = `<svg class="w-4 h-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                }

                fetch(updateKeteranganUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        absensi_id: absensiId,
                        rfid_uid: rfidUid,
                        agenda_id: agendaId,
                        keterangan: keterangan
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        inputEl.dataset.original = keterangan;
                        if (printText) {
                            printText.textContent = keterangan || '-';
                        }
                        if (indicator) {
                            indicator.classList.remove('hidden');
                            indicator.classList.add('flex');
                            inputEl.classList.add('!border-emerald-500');
                            setTimeout(() => {
                                indicator.classList.add('hidden');
                                indicator.classList.remove('flex');
                                inputEl.classList.remove('!border-emerald-500');
                            }, 1500);
                        }
                        if (btnSave) {
                            btnSave.innerHTML = `<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`;
                            setTimeout(() => {
                                btnSave.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`;
                            }, 1500);
                        }
                    } else {
                        inputEl.classList.add('!border-red-500');
                        setTimeout(() => inputEl.classList.remove('!border-red-500'), 2000);
                        if (btnSave) {
                            btnSave.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error update keterangan:', err);
                    inputEl.classList.add('!border-red-500');
                    setTimeout(() => inputEl.classList.remove('!border-red-500'), 2000);
                    if (btnSave) {
                        btnSave.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>`;
                    }
                });
            }

            // Event delegation on tbody for Keterangan inputs
            if (tbodyAbsensi) {
                // Save on Enter
                tbodyAbsensi.addEventListener('keydown', function(e) {
                    if (e.target && e.target.classList.contains('keterangan-input') && e.key === 'Enter') {
                        e.preventDefault();
                        saveKeterangan(e.target);
                        e.target.blur();
                    }
                });

                // Save on click icon
                tbodyAbsensi.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-save-keterangan');
                    if (btn) {
                        const wrapper = btn.closest('.keterangan-wrapper');
                        const input = wrapper ? wrapper.querySelector('.keterangan-input') : null;
                        if (input) {
                            saveKeterangan(input);
                        }
                    }
                });

                // Auto-save on blur if value changed
                tbodyAbsensi.addEventListener('focusout', function(e) {
                    if (e.target && e.target.classList.contains('keterangan-input')) {
                        if (e.target.value.trim() !== (e.target.dataset.original || '')) {
                            saveKeterangan(e.target);
                        }
                    }
                });
            }

            // Focus management: prevent scanner from stealing focus while user is typing in keterangan
            let isUserTypingKeterangan = false;

            document.addEventListener('focusin', function(e) {
                if (e.target && (e.target.classList.contains('keterangan-input') || (e.target.tagName === 'INPUT' && e.target !== inputScan) || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'BUTTON')) {
                    isUserTypingKeterangan = true;
                }
            });

            document.addEventListener('focusout', function(e) {
                setTimeout(() => {
                    const active = document.activeElement;
                    if (!active || (!active.classList.contains('keterangan-input') && active !== inputScan && active.tagName !== 'INPUT' && active.tagName !== 'TEXTAREA' && active.tagName !== 'BUTTON')) {
                        isUserTypingKeterangan = false;
                        if (inputScan) inputScan.focus();
                    }
                }, 150);
            });

            if (inputScan) {
                inputScan.addEventListener('blur', () => {
                    setTimeout(() => {
                        if (!isUserTypingKeterangan) {
                            const active = document.activeElement;
                            if (active && (active.classList.contains('keterangan-input') || (active.tagName === 'INPUT' && active !== inputScan) || active.tagName === 'TEXTAREA')) {
                                return;
                            }
                            inputScan.focus();
                        }
                    }, 100);
                });
            }

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
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            rfid: rfidValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notifikasi.innerHTML = `<span class="text-green-600 font-bold">Scan</span>`;

                            if (tbodyAbsensi) {
                                tbodyAbsensi.innerHTML = data.html;
                            }
                            Swal.fire({
                                title: '<div class="font-display font-bold text-2xl text-slate-800">Berhasil Check-in!</div>',
                                html: `
                                    <div class="mt-2 text-left bg-slate-50 rounded-xl p-5 border border-slate-100 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Nama Lengkap</p>
                                                <p class="font-bold text-slate-800 text-lg">${data.nama}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Divisi</p>
                                                <p class="font-bold text-slate-800 text-lg">${data.divisi || 'Ini divisi'}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Waktu Check-in</p>
                                                <p class="font-bold text-slate-800 text-lg">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit', second:'2-digit'})}</p>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl border border-slate-100',
                                }
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    console.log("I was closed by the timer");
                                }
                            });
                        } else {
                            notifikasi.innerHTML =
                                `<span class="text-red-600 font-bold text-sm"> ${data.message}</span>`;
                            console.error("Detail Error PHP:", data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error Fetch:', error);
                        notifikasi.innerHTML =
                            `<span class="text-red-600 font-bold"> Gagal terhubung ke server.</span>`;
                    })
                    .finally(() => {
                        inputScan.value = '';
                        if (!isUserTypingKeterangan) {
                            inputScan.focus();
                        }
                    });
            });
        });
    </script>
@endsection
