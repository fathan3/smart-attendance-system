@extends('layouts.app')
@section('main-content')

<div id="page-laporan" class="page active">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200/60 rounded-lg p-5">
      <div class="text-xs font-medium text-slate-500 mb-3">Filter Laporan</div>
      <div class="space-y-3">
        <div>
          <label>Periode</label>
          <select class="inp">
            <option>Hari Ini</option>
            <option>Minggu Ini</option>
            <option>Bulan Ini</option>
            <option>Custom</option>
          </select>
        </div>
        <div>
          <label>Acara</label>
          <select class="inp">
            <option>Semua Acara</option>
          </select>
        </div>
        <div>
          <label>Status</label>
          <select class="inp">
            <option>Semua Status</option>
            <option>Hadir</option>
            <option>Terlambat</option>
            <option>Tidak Hadir</option>
          </select>
        </div>
        <button class="btn-primary w-full justify-center">Tampilkan</button>
      </div>
    </div>

        <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-lg p-5">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="font-semibold text-slate-900">Ringkasan Laporan</h3>
        </div>
        <div class="flex gap-2">
          <button class="btn-secondary text-xs py-1.5 px-3" onclick="printSection('laporan-content')">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak PDF
          </button>
          <button class="btn-green text-xs py-1.5 px-3">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Excel
          </button>
        </div>
      </div>

      <div id="laporan-content" class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-slate-50 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold tracking-tight text-slate-900">{{ $total_mahasiswa }}</div>
          <div class="text-slate-500 text-xs mt-1">Total Mahasiswa</div>
        </div>
        <div class="bg-slate-50 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold tracking-tight text-emerald-600">{{ $rata_hadir }}%</div>
          <div class="text-slate-500 text-xs mt-1">Rata-rata Hadir</div>
        </div>
        <div class="bg-slate-50 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold tracking-tight text-blue-600">{{ $total_tidak_hadir }}</div>
          <div class="text-slate-500 text-xs mt-1">Total Tidak Hadir</div>
        </div>
        <div class="bg-slate-50 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold tracking-tight text-sky-600">{{ $acara_selesai }}</div>
          <div class="text-slate-500 text-xs mt-1">Acara Selesai</div>
        </div>
      </div>
    </div>
  </div>

    <div class="bg-white border border-slate-200/60 rounded-lg overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-slate-200/60">
      <h3 class="font-semibold text-slate-900">Detail Kehadiran Per Mahasiswa</h3>
      <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="window.print()">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak Semua
      </button>
    </div>
    <div class="overflow-x-auto">
      <table class="data-table w-full">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Mahasiswa</th>
            <th>Total Acara</th>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Tidak Hadir</th>
            <th>Persentase</th>
          </tr>
        </thead>
        <tbody>
          @forelse($mahasiswa as $idx => $mhs)
          <tr>
            <td class="text-slate-400">{{ $idx + 1 }}</td>
            <td class="text-slate-900 font-medium">{{ $mhs->name }}</td>
            <td>{{ $mhs->laporan->total_agenda }}</td>
            <td class="text-emerald-600">{{ $mhs->laporan->hadir }}</td>
            <td class="text-blue-600">{{ $mhs->laporan->terlambat }}</td>
            <td class="text-red-400">{{ $mhs->laporan->tidak_hadir }}</td>
            <td>
              <div class="flex items-center gap-2">
                <div class="progress-bar flex-1 min-w-[80px]">
                  <div class="progress-fill {{ $mhs->laporan->persentase >= 80 ? 'bg-emerald-500' : ($mhs->laporan->persentase >= 50 ? 'bg-blue-600' : 'bg-red-500') }}" style="width:{{ $mhs->laporan->persentase }}%"></div>
                </div>
                <span class="text-slate-900 text-xs w-8">{{ $mhs->laporan->persentase }}%</span>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-slate-500 py-4">Data mahasiswa belum tersedia</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection