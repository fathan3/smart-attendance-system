<aside
  id="sidebar"
  class="w-64 h-screen flex-shrink-0 bg-white border-r border-slate-200 flex flex-col overflow-hidden z-50"
  style="transition: width 0.3s cubic-bezier(0.4,0,0.2,1);"
>
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-200">
    <img src="{{ asset('images/logo.png') }}" width="50px">
    <div class="sidebar-logo-text transition-all duration-300">
      <div class="font-display font-800 text-slate-900 text-base leading-tight">Absensi</div>
      <div class="text-slate-500 text-xs">HIMATIF 2026</div>
    </div>
  </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="sidebar-section-title text-xs font-display font-600 text-slate-400 uppercase tracking-widest mb-2 px-2 transition-all duration-300">
      Menu Utama
    </div>

    <a
      class="nav-item {{ request()->is('/') ? 'active' : '' }}"
      href="/"
    >
      <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1" />
        <rect x="14" y="3" width="7" height="7" rx="1" />
        <rect x="3" y="14" width="7" height="7" rx="1" />
        <rect x="14" y="14" width="7" height="7" rx="1" />
      </svg>
      <span class="nav-label">Dashboard</span>
    </a>

    <a
      class="nav-item {{ request()->is('mahasiswa*') ? 'active' : '' }}"
      href="/mahasiswa"
    >
      <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <span class="nav-label">Daftar Mahasiswa</span>
    </a>

    <a
      class="nav-item {{ request()->is('acara*') ? 'active' : '' }}"
      href="/acara"
    >
      <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <span class="nav-label">Acara & Agenda</span>
    </a>

    <a
      class="nav-item {{ request()->is('absensi*') ? 'active' : '' }}"
      href="/absensi"
    >
      <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
      </svg>
      <span class="nav-label">Absensi</span>
    </a>

        <div class="sidebar-section-title text-xs font-display font-600 text-slate-400 uppercase tracking-widest mb-2 px-2 mt-5 transition-all duration-300">
      Laporan
    </div>

    <a
      class="nav-item {{ request()->is('laporan*') ? 'active' : '' }}"
      href="/laporan"
    >
      <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <span class="nav-label">Laporan & Cetak</span>
    </a>
  </nav>

    <div class="p-3 border-t border-slate-200">
    <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 cursor-pointer transition">
      <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
        <span class="text-blue-600 font-display font-700 text-sm">A</span>
      </div>
      <div class="sidebar-logo-text transition-all duration-300 min-w-0">
        <div class="text-slate-900 text-sm font-600 truncate">Admin</div>
        <div class="text-slate-500 text-xs truncate">admin@siabsen.id</div>
      </div>
    </div>
  </div>
</aside>
