@extends('layouts.app')
@section('main-content')
<div id="page-laporan" class="page active">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
          <div class="text-xs font-display uppercase tracking-wider text-slate-400 mb-2">Filter Laporan</div>
          <div class="space-y-3">
            <div><label>Periode</label><select class="inp"><option>Hari Ini</option><option>Minggu Ini</option><option>Bulan Ini</option><option>Custom</option></select></div>
            <div><label>Acara</label><select class="inp"><option>Semua Acara</option><option>Seminar AI</option><option>Workshop UI/UX</option></select></div>
            <div><label>Status</label><select class="inp"><option>Semua Status</option><option>Hadir</option><option>Terlambat</option><option>Tidak Hadir</option></select></div>
            <button class="btn-primary w-full justify-center">Tampilkan</button>
          </div>
        </div>
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-5">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-700 text-slate-900">Ringkasan Laporan</h3>
            <div class="flex gap-2">
              <button class="btn-secondary text-xs py-1.5 px-3" onclick="printSection('laporan-content')"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak PDF</button>
              <button class="btn-green text-xs py-1.5 px-3"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Export Excel</button>
            </div>
          </div>
          <div id="laporan-content" class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-xl p-4 text-center"><div class="font-display text-2xl font-800 text-slate-900">248</div><div class="text-slate-500 text-xs mt-1">Total Mahasiswa</div></div>
            <div class="bg-white rounded-xl p-4 text-center"><div class="font-display text-2xl font-800 text-emerald-600">84%</div><div class="text-slate-500 text-xs mt-1">Rata-rata Hadir</div></div>
            <div class="bg-white rounded-xl p-4 text-center"><div class="font-display text-2xl font-800 text-blue-600">32</div><div class="text-slate-500 text-xs mt-1">Tidak Hadir</div></div>
            <div class="bg-white rounded-xl p-4 text-center"><div class="font-display text-2xl font-800 text-sky-600">12</div><div class="text-slate-500 text-xs mt-1">Acara Selesai</div></div>
          </div>
          <canvas id="laporanChart" class="mt-4" height="120"></canvas>
        </div>
      </div>

      <!-- Laporan Detail Table -->
      <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-slate-200">
          <h3 class="font-display font-700 text-slate-900">Detail Kehadiran Per Mahasiswa</h3>
          <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="window.print()"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>Cetak Semua</button>
        </div>
        <table class="data-table">
          <thead><tr><th>No</th><th>Nama Mahasiswa</th><th>Total Acara</th><th>Hadir</th><th>Terlambat</th><th>Tidak Hadir</th><th>Persentase</th></tr></thead>
          <tbody>
            <tr><td class="text-slate-400">1</td><td class="text-slate-900 font-500">Ahmad Fauzi</td><td>12</td><td class="text-emerald-600">11</td><td class="text-blue-600">1</td><td class="text-red-400">0</td><td><div class="flex items-center gap-2"><div class="progress-bar flex-1 w-20"><div class="progress-fill bg-emerald-500" style="width:91%"></div></div><span class="text-slate-900 text-xs w-8">91%</span></div></td></tr>
            <tr><td class="text-slate-400">2</td><td class="text-slate-900 font-500">Siti Rahayu</td><td>12</td><td class="text-emerald-600">10</td><td class="text-blue-600">2</td><td class="text-red-400">0</td><td><div class="flex items-center gap-2"><div class="progress-bar flex-1 w-20"><div class="progress-fill bg-emerald-500" style="width:83%"></div></div><span class="text-slate-900 text-xs w-8">83%</span></div></td></tr>
            <tr><td class="text-slate-400">3</td><td class="text-slate-900 font-500">Budi Santoso</td><td>12</td><td class="text-emerald-600">8</td><td class="text-blue-600">3</td><td class="text-red-400">1</td><td><div class="flex items-center gap-2"><div class="progress-bar flex-1 w-20"><div class="progress-fill bg-blue-600" style="width:66%"></div></div><span class="text-slate-900 text-xs w-8">66%</span></div></td></tr>
            <tr><td class="text-slate-400">4</td><td class="text-slate-900 font-500">Dewi Lestari</td><td>12</td><td class="text-emerald-600">12</td><td class="text-blue-600">0</td><td class="text-red-400">0</td><td><div class="flex items-center gap-2"><div class="progress-bar flex-1 w-20"><div class="progress-fill bg-emerald-500" style="width:100%"></div></div><span class="text-slate-900 text-xs w-8">100%</span></div></td></tr>
            <tr><td class="text-slate-400">5</td><td class="text-slate-900 font-500">Rudi Hermawan</td><td>12</td><td class="text-emerald-600">9</td><td class="text-blue-600">1</td><td class="text-red-400">2</td><td><div class="flex items-center gap-2"><div class="progress-bar flex-1 w-20"><div class="progress-fill bg-blue-600" style="width:75%"></div></div><span class="text-slate-900 text-xs w-8">75%</span></div></td></tr>
          </tbody>
        </table>
      </div>
    </div>
    @endsection