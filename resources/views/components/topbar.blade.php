<header id="topbar"
    class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 flex-shrink-0 z-40">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()"
            class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h1 id="page-title" class="font-display text-slate-900 font-700 text-lg leading-none">Dashboard</h1>
            <p id="page-sub" class="text-slate-500 text-xs mt-0.5">Ringkasan sistem absensi</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
            <div id="clock" class="font-display text-slate-900 font-600 text-sm"></div>
            <div id="datestr" class="text-slate-500 text-xs"></div>
        </div>
        <div class="w-px h-8 bg-slate-100"></div>
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
    </div>
</header>
