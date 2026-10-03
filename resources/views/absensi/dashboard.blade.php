@extends('layouts.app')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan sistem absensi')
@section('main-content')
    <div id="page-dashboard" class="page active">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
                <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Total Mahasiswa</div>
                <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['total_mahasiswa'] ?? 0 }}</div>
            </div>
            <div
                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
                <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Hadir Hari Ini</div>
                <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['hadir_hari_ini'] ?? 0 }}</div>
            </div>
            <div
                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
                <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Acara Aktif</div>
                <div class="font-display text-3xl font-800 text-slate-900 mb-1">{{ $stats['acara_aktif'] ?? 0 }}</div>
            </div>
            <div
                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all p-5">
                <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-3">Tingkat Hadir</div>
                @php
                    $tingkat_hadir =
                        isset($stats['total_mahasiswa']) && $stats['total_mahasiswa'] > 0
                            ? round(($stats['hadir_hari_ini'] / $stats['total_mahasiswa']) * 100)
                            : 0;
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
                                <span
                                    class="text-blue-600 font-bold text-xs">{{ substr($absen->user->name ?? '?', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-slate-900 text-sm font-500 truncate">{{ $absen->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-slate-500 text-xs">{{ $absen->agenda->acara->nama ?? 'Acara' }} &mdash;
                                    {{ $absen->waktu_masuk ? \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i') : '-' }}
                                </div>
                            </div>
                            @if ($absen->status == 'hadir')
                                <span class="badge badge-green text-xs">Hadir</span>
                            @elseif($absen->status == 'terlambat')
                                <span class="badge badge-orange text-xs">Terlambat</span>
                            @elseif($absen->status == 'izin')
                                <span class="badge badge-blue text-xs">Izin</span>
                            @else
                                <span class="badge badge-red text-xs">Tidak Hadir</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-slate-500 text-sm py-4">Belum ada aktivitas</div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions & Info Panel -->
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                    <h3 class="font-display font-700 text-slate-900 mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="/acara" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition group text-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-blue-600">Acara & Agenda</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Kelola sesi absensi</span>
                        </a>

                        <a href="/mahasiswa" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition group text-center">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-emerald-600">Data Mahasiswa</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Daftar kartu RFID</span>
                        </a>

                        <a href="/laporan" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 hover:border-purple-500 hover:bg-purple-50/50 transition group text-center">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-purple-600">Laporan Kehadiran</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Rekapitulasi absensi</span>
                        </a>

                        <div class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 bg-slate-50 text-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-700">Scanner RFID</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">Tersedia di setiap agenda</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl p-5 text-white shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-display font-700 text-sm">Smart Attendance System</h4>
                    </div>
                    <p class="text-xs text-blue-100 leading-relaxed">
                        Sistem absensi berbasis RFID terintegrasi HIMATIF. Pastikan kartu RFID mahasiswa telah terdaftar di menu Mahasiswa sebelum agenda berlangsung.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
