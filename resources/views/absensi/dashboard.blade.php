@extends('layouts.app')
@section('main-content')

<div id="page-dashboard" class="page active">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
      <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Total Mahasiswa</div>
      <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['total_mahasiswa'] ?? 0 }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
      <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Hadir Hari Ini</div>
      <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['hadir_hari_ini'] ?? 0 }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
      <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Acara Aktif</div>
      <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['acara_aktif'] ?? 0 }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
      <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Tingkat Hadir</div>
      @php
          $tingkat_hadir = (isset($stats['total_mahasiswa']) && $stats['total_mahasiswa'] > 0) ? round(($stats['hadir_hari_ini'] / $stats['total_mahasiswa']) * 100) : 0;
      @endphp
      <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $tingkat_hadir }}%</div>
      <div class="progress-bar mt-3">
        <div class="progress-fill bg-blue-600" style="width:{{ $tingkat_hadir }}%"></div>
      </div>
    </div>
  </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-700 text-slate-900">Aktivitas Terbaru</h3>
        <a href="/laporan" class="text-xs text-blue-600 hover:underline">Lihat Semua</a>
      </div>
      <div class="space-y-3">
        @forelse($aktivitas_terbaru as $absen)
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="text-blue-600 font-bold text-xs">{{ substr($absen->user->name ?? '?', 0, 1) }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-slate-900 text-sm font-500 truncate">{{ $absen->user->name ?? 'Unknown' }}</div>
            <div class="text-slate-500 text-xs">{{ $absen->agenda->acara->nama ?? 'Acara' }} &mdash; {{ $absen->waktu_masuk ? \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i') : '-' }}</div>
          </div>
          @if($absen->status == 'hadir')
            <span class="badge badge-green text-xs">Hadir</span>
          @elseif($absen->status == 'terlambat')
            <span class="badge badge-orange text-xs">Terlambat</span>
          @else
            <span class="badge badge-red text-xs">Tidak Hadir</span>
          @endif
        </div>
        @empty
        <div class="text-center text-slate-500 text-sm py-4">Belum ada aktivitas</div>
        @endforelse
      </div>
    </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-700 text-slate-900">Acara Mendatang</h3>
        <a href="/acara" class="btn-secondary text-xs py-1.5 px-3 no-print">+ Tambah</a>
      </div>
      <div class="space-y-3">
        @forelse($acara_mendatang as $acara)
        <div class="p-3 rounded-xl border {{ $acara->status == 'aktif' ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-white' }}">
          <div class="flex justify-between items-start mb-1">
            <span class="text-slate-900 text-sm font-600">{{ $acara->nama }}</span>
            <span class="badge {{ $acara->status == 'aktif' ? 'badge-green' : 'badge-blue' }} text-xs">{{ ucfirst($acara->status) }}</span>
          </div>
          <div class="text-slate-500 text-xs">{{ \Carbon\Carbon::parse($acara->tanggal_mulai)->format('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($acara->tanggal_selesai)->format('d M Y') }}</div>
          <div class="text-slate-500 text-xs mt-1">{{ $acara->agenda()->count() ?? 0 }} agenda &middot; {{ $acara->lokasi ?? '-' }}</div>
        </div>
        @empty
        <div class="text-center text-slate-500 text-sm py-4">Tidak ada acara mendatang</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection