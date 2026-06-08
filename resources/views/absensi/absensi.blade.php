@extends('layouts.app')
@section('main-content')
<div id="page-absensi" class="page active">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- RFID Scanner Panel -->
        <div class="lg:col-span-1">
          <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-center mb-4">
            <div class="font-display font-700 text-slate-900 mb-4">Scanner RFID</div>
            <div class="w-24 h-24 rounded-full border-2 border-accent mx-auto flex items-center justify-center rfid-pulse mb-4 relative">
              <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
            </div>
            <div id="rfid-status" class="text-slate-500 text-sm mb-4">Tempelkan kartu RFID...</div>
            <input type="text" id="rfid-input" class="inp text-center text-lg font-display font-700 tracking-widest mb-3" placeholder="atau ketik RFID" maxlength="16">
            <select class="inp mb-3" id="absen-agenda">
              <option value="">Pilih Agenda</option>
              <option value="1">Seminar AI — Sesi Pagi</option>
              <option value="2">Seminar AI — Sesi Siang</option>
              <option value="3">Workshop UI/UX</option>
            </select>
            <div class="flex gap-2">
              <button class="btn-green flex-1" onclick="processAbsensi('masuk')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>Absen Masuk</button>
              <button class="btn-secondary flex-1" onclick="processAbsensi('pulang')"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16l4-4m0 0l-4-4m4 4H3"/></svg>Pulang</button>
            </div>
            <div id="absen-result" class="mt-3 hidden"></div>
          </div>

          <!-- Batas waktu -->
          <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
            <div class="text-xs font-display font-600 uppercase tracking-wider text-slate-400 mb-3">Batas Waktu Absen</div>
            <div class="space-y-2">
              <div class="flex justify-between text-sm"><span class="text-slate-600">Absen Masuk</span><span class="text-slate-900 font-600">08:00 – 09:00</span></div>
              <div class="flex justify-between text-sm"><span class="text-slate-600">Batas Terlambat</span><span class="text-blue-600 font-600">09:01 – 09:30</span></div>
              <div class="flex justify-between text-sm"><span class="text-slate-600">Absen Pulang</span><span class="text-slate-900 font-600">15:00 – 17:00</span></div>
            </div>
          </div>
        </div>

        <!-- Log Absensi -->
        <div class="lg:col-span-2">
          <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
              <h3 class="font-display font-700 text-slate-900">Log Absensi Hari Ini</h3>
              <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="printSection('absensi-log')"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak</button>
            </div>
            <div id="absensi-log">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Mahasiswa</th>
                    <th>Agenda</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="absensi-tbody">
                  <!-- Populated by JS -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endsection