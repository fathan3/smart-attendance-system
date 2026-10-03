@extends('layouts.app')
@section('page_title', 'Agenda Acara')
@section('page_subtitle', 'Kelola agenda untuk acara terpilih')
@section('main-content')
    <div id="page-acara" class="page active">
        <div class="flex items-center justify-between mb-6">
            <div class="text-sm flex items-center font-display font-600 uppercase tracking-wider text-slate-600 mb-3">
                <a href="/acara">Acara&nbsp;</a>/
                {{ $namaacara->nama }}
            </div>
            <button class="btn-primary" id="tambah-acara" onclick="showModal('modal-tambah-acara')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Agenda
            </button>
            <button class="btn-primary hidden" id="tambah-divisi" onclick="showModal('modal-tambah-divisi')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Divisi
            </button>
            <button class="btn-primary hidden" id="tambah-panitia" onclick="showModal('modal-tambah-panitia')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Panitia
            </button>
        </div>

        <div class="flex gap-2 mb-4 border-b border-slate-200 no-print">
            <button onclick="switchTab('tab-daftar', 'tambah-acara')" id="btn-tab-daftar"
                class="px-4 py-3 font-500 text-sm border-b-2 border-blue-600 text-blue-600 transition">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Daftar Agenda
            </button>
            <button onclick="switchTab('tab-divisi', 'tambah-divisi')" id="btn-tab-divisi"
                class="px-4 py-3 font-500 text-sm border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Daftar Divisi
            </button>
            <button onclick="switchTab('tab-panitia', 'tambah-panitia')" id="btn-tab-panitia"
                class="px-4 py-3 font-500 text-sm border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Daftar Panitia
            </button>
        </div>

        <div id="tab-daftar" class="tab-content active">
            <div id="acara-list" class="space-y-4">
                <div id="agenda-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Agenda</th>
                                <th>Jam Checkin</th>
                                <th>Batas Checkin</th>
                                <th class="text-center">Scan Masuk</th>
                                <th class="text-center">Scan Pulang</th>
                                <th class="no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="agenda-tbody">
                            @foreach ($agenda as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="text-slate-900 font-500 text-sm">{{ $item->nama }}</div>
                                    </td>
                                    <td>
                                        <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                                            {{ $item->checkin ? \Carbon\Carbon::parse($item->checkin)->format('d M Y, H:i') : '-' }}
                                        </code>
                                    </td>
                                    <td>
                                        <code class="text-xs font-display text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                                            {{ $item->batas_checkin ? \Carbon\Carbon::parse($item->batas_checkin)->format('d M Y, H:i') : '-' }}
                                        </code>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('checkin', ['id_agenda' => encrypt($item->id)])}}"
                                            class="text-blue-600 hover:text-blue-700 inline-flex justify-center" title="Buka Scanner Check-in">
                                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('checkout', ['id_agenda' => encrypt($item->id)])}}"
                                            class="text-red-600 hover:text-red-700 inline-flex justify-center" title="Buka Scanner Check-out">
                                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="no-print">
                                        <div class="flex gap-2">
                                            <button
                                                class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                                onclick="showDetailModal('{{ $item->id }}', '{{ $item->nama }}', '{{ $item->checkin }}', '{{ $item->batas_checkin }}', '{{ $item->checkout ?? '' }}', '{{ $item->batas_checkout ?? '' }}', '{{ encrypt($item->id) }}')">Detail</button>
                                            <button class="btn-secondary py-1.5 px-3 text-xs"
                                                onclick="showEditModal('{{ $item->id }}', '{{ $item->nama }}', '{{ $item->checkin }}', '{{ $item->batas_checkin }}', '{{ $item->checkout ?? '' }}', '{{ $item->batas_checkout ?? '' }}')">Edit</button>
                                            <button class="btn-danger py-1.5 px-3 text-xs" onclick="confirmDeleteAgenda('{{ encrypt($item->id) }}', '{{ addslashes($item->nama) }}')">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-divisi" class="tab-content hidden">
        <div id="template-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>Deskripsi</th>
                        <th>Panitia</th>
                        <th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody id="template-tbody">
                    @foreach ($divisi as $idx => $div)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $div->nama }}</td>
                            <td>{{ $div->deskripsi }}</td>
                            <td class="text-blue-400"><a href="{{ route('agenda.divisi', ['divisi_id' => encrypt($div->id)]) }}">Panitia</a></td>
                            <td class="no-print">
                                <div class="flex gap-2">
                                    <button
                                        class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                        onclick="showDetailDivisiModal('{{ $div->id }}', '{{ $div->nama }}', '{{ $div->deskripsi }}')"
                                        >Detail</button>
                                    <button class="btn-danger py-1.5 px-3 text-xs"
                                        onclick="confirmDeleteDivisi('{{ encrypt($div->id) }}', '{{ addslashes($div->nama) }}')">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="tab-panitia" class="tab-content hidden">
        <div id="template-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <!-- Filter & Search Toolbar -->
            <div class="p-4 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 no-print">
                <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                    <button type="button" onclick="filterPanitiaList('all')" id="btn-panitia-all" class="btn-panitia-filter px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white transition shadow-sm">
                        Semua ({{ $panitia->count() }})
                    </button>
                    <button type="button" onclick="filterPanitiaList('assigned')" id="btn-panitia-assigned" class="btn-panitia-filter px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                        Sudah Ada Divisi ({{ $panitia->whereNotNull('pivot_id')->count() }})
                    </button>
                    <button type="button" onclick="filterPanitiaList('unassigned')" id="btn-panitia-unassigned" class="btn-panitia-filter px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition">
                        Belum Ditugaskan ({{ $panitia->whereNull('pivot_id')->count() }})
                    </button>
                </div>
                <div class="relative w-full sm:w-64">
                    <input type="text" id="search-panitia" onkeyup="searchPanitiaList()" placeholder="Cari nama / RFID..." class="inp text-xs py-1.5 pl-8 pr-3">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>Nama</th>
                        <th>RFID</th>
                        <th>Divisi</th>
                        <th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody id="template-tbody">
                    @forelse ($panitia as $idx => $panpel)
                        <tr class="row-panitia" data-status="{{ $panpel->pivot_id ? 'assigned' : 'unassigned' }}" data-name="{{ strtolower($panpel->name) }}" data-rfid="{{ strtolower($panpel->rfid_uid ?? '') }}">
                            <td class="text-center font-medium text-slate-500 row-panitia-number"> {{ $idx+1 }} </td>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $panpel->name }}</div>
                                @if($panpel->email)
                                    <div class="text-xs text-slate-400">{{ $panpel->email }}</div>
                                @endif
                            </td>
                            <td>
                                @if($panpel->rfid_uid)
                                    <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                                        {{ $panpel->rfid_uid }}
                                    </code>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td>
                                @if($panpel->nama_divisi)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $panpel->nama_divisi }}
                                    </span>
                                @else
                                    <span class="badge badge-gray text-xs">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="no-print">
                                <div class="flex gap-2">
                                    @if($panpel->pivot_id)
                                        <button class="btn-danger py-1.5 px-3 text-xs"
                                            onclick="confirmDeletePanitia('{{ encrypt($panpel->pivot_id) }}', '{{ addslashes($panpel->name) }}')">Hapus</button>
                                    @else
                                        <button class="py-1.5 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition inline-flex items-center gap-1"
                                            onclick="openTambahPanitiaSingle('{{ $panpel->user_id ?? $panpel->id }}', '{{ addslashes($panpel->name) }}')">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tugaskan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500 font-medium text-sm">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Belum ada data mahasiswa atau panitia.
                            </td>
                        </tr>
                    @endforelse
                    <tr id="row-panitia-empty" class="hidden">
                        <td colspan="5" class="text-center py-8 text-slate-500 font-medium text-sm">
                            Tidak ada panitia yang sesuai dengan pencarian atau filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-tambah-acara" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-acara')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Agenda</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Buat agenda baru untuk acara</p>
                </div>
                <button onclick="closeModal(null,'modal-tambah-acara')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('agenda.store') }}">
                @csrf
                <div class="space-y-4">
                    <input type="hidden" name="acara_id" value="{{ $namaacara->id }}">
                    <div>
                        <label>Nama Agenda</label>
                        <input name="nama" required type="text" class="inp" placeholder="Contoh: Pembukaan">
                    </div>

                    <label><b>Checkin</b></label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Jam Mulai Checkin</label>
                            <input type="datetime-local" required name="checkin" class="inp">
                        </div>
                        <div>
                            <label>Jam Selesai Checkin</label>
                            <input type="datetime-local" required name="batas_checkin" class="inp">
                        </div>
                    </div>
                    <hr>
                    <label><b>Checkout</b></label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Jam Mulai Checkout</label>
                            <input type="datetime-local" required name="checkout" class="inp">
                        </div>
                        <div>
                            <label>Jam Selesai Checkout</label>
                            <input type="datetime-local" required name="batas_checkout" class="inp">
                        </div>
                    </div>

                </div>
                <div class="flex gap-3 mt-6">
                    <button class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-tambah-acara')">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="modal-tambah-divisi" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-divisi')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Divisi</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Tambah Divisi Baru di Acara <b>{{ $namaacara->nama }}</b></p>
                </div>
                <button onclick="closeModal(null,'modal-tambah-divisi')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('divisi.store') }}">
                @csrf
                <div class="space-y-4">
                    <input type="hidden" name="acara_id" value="{{ $namaacara->id }}">
                    <div>
                        <label>Nama Divisi</label>
                        <input name="nama" required type="text" class="inp" placeholder="Contoh: Pembukaan">
                    </div>

                    <div>
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="" class="inp"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-tambah-divisi')">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-detail-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-acara')">
        <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Detail Agenda</h2>
                    <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
                </div>
                <button onclick="closeModal(null,'modal-detail-acara')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-600 font-600 uppercase tracking-wide">Nama Agenda</p>
                            <p id="detail-nama" class="text-slate-900 font-500 mt-1"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="text-sm font-600 text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        Jadwal Checkin
                    </h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-slate-600 text-xs font-500">Jam Mulai</p>
                            <p id="detail-checkin" class="text-slate-900 font-600 mt-1"></p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-xs font-500">Batas Checkin</p>
                            <p id="detail-batas-checkin" class="text-slate-900 font-600 mt-1"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h3 class="text-sm font-600 text-red-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        Jadwal Checkout
                    </h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-slate-600 text-xs font-500">Jam Mulai</p>
                            <p id="detail-checkout" class="text-slate-900 font-600 mt-1">-</p>
                        </div>
                        <div>
                            <p class="text-slate-600 text-xs font-500">Batas Checkout</p>
                            <p id="detail-batas-checkout" class="text-slate-900 font-600 mt-1">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-200 flex gap-2">
                <a class="btn-primary flex-1 justify-center" id="link-absensi" href="#" >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Absensi
                </a>
                <a href="" id="cetak" target="_blank" class="btn-secondary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </a>
                <button class="btn-secondary justify-center" onclick="closeModal(null,'modal-detail-acara')">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <div id="modal-detail-divisi" class="modal-overlay hidden" onclick="closeModal(event,'modal-detail-divisi')">
        <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Detail Divisi</h2>
                    <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
                </div>
                <button onclick="closeModal(null,'modal-detail-divisi')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-600 font-600 uppercase tracking-wide">Nama Divisi</p>
                            <p id="detail-divisi-nama" class="text-slate-900 font-500 mt-1"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="text-sm font-600 text-blue-900 mb-3 flex items-center gap-2">
                        Deskripsi Divisi
                    </h3>
                    <div>
                        <p id="detail-divisi-deskripsi" class="text-slate-900 font-600 mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-200 flex gap-2">
                <button class="btn-secondary justify-center" onclick="closeModal(null,'modal-detail-divisi')">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="modal-edit-acara" class="modal-overlay hidden" onclick="closeModal(event,'modal-edit-acara')">
        <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 id="detail-acara-title" class="font-display font-800 text-slate-900 text-xl">Edit Agenda</h2>
                    <p id="detail-acara-sub" class="text-slate-500 text-sm mt-0.5"></p>
                </div>
                <button onclick="closeModal(null,'modal-edit-acara')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>
            <form method="POST" action="" id="form-edit-agenda">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label>Nama Agenda</label>
                        <input name="nama" required type="text" id="edit-agenda-nama" class="inp"
                            placeholder="Contoh: Pembukaan">
                        <input name="acara_id" required type="hidden" class="inp" value="{{ $namaacara->id }}">
                    </div>

                    <label><b>Checkin</b></label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Jam Mulai Checkin</label>
                            <input type="datetime-local" id="edit-agenda-checkin" required name="checkin"
                                class="inp">
                        </div>
                        <div>
                            <label>Jam Selesai Checkin</label>
                            <input type="datetime-local" required id="edit-agenda-batas-checkin" name="batas_checkin"
                                class="inp">
                        </div>
                    </div>
                    <hr>
                    <label><b>Checkout</b></label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Jam Mulai Checkout</label>
                            <input type="datetime-local" required id="edit-agenda-checkout" name="checkout"
                                class="inp">
                        </div>
                        <div>
                            <label>Jam Selesai Checkout</label>
                            <input type="datetime-local" required id="edit-agenda-batas-checkout" name="batas_checkout"
                                class="inp">
                        </div>
                    </div>

                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-edit-acara')">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary flex-1 justify-center">
                        Simpan Perubahan
                    </button>
                </div>
            </form>


        </div>
    </div>

    <!-- Modal Tambah Panitia -->
    <div id="modal-tambah-panitia" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-panitia')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Panitia</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Tugaskan mahasiswa ke divisi acara ini</p>
                </div>
                <button onclick="closeModal(null,'modal-tambah-panitia')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>

            @if($divisi->count() == 0)
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm mb-4">
                    Belum ada divisi yang dibuat pada acara ini. Silakan buat divisi terlebih dahulu di tab <strong>Daftar Divisi</strong>.
                </div>
                <div class="flex justify-end">
                    <button type="button" class="btn-secondary" onclick="closeModal(null,'modal-tambah-panitia')">Tutup</button>
                </div>
            @else
                <form action="{{ route('panitia.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="acara_id" value="{{ $namaacara->id }}">
                    <div class="space-y-4 mb-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="mb-0">Pilih Mahasiswa</label>
                                @if($panitia_available->count() > 0)
                                <button type="button" id="btn-toggle-multi" class="text-xs text-blue-600 hover:text-blue-700 font-600" onclick="togglePanitiaSelectionMode()">
                                    + Pilih Banyak Sekaligus
                                </button>
                                @endif
                            </div>

                            <div id="panitia-single-select-box">
                                <select class="inp" name="user_id" id="select-panitia-single">
                                    @forelse ($panitia_available as $p_avail)
                                        <option value="{{ $p_avail->id }}">{{ $p_avail->name }} ({{ $p_avail->rfid_uid }})</option>
                                    @empty
                                        <option value="">Semua mahasiswa aktif sudah terdaftar di acara ini</option>
                                    @endforelse
                                </select>
                            </div>

                            <div id="panitia-multi-select-box" class="hidden border border-slate-200 rounded-xl p-3 bg-slate-50">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200 text-xs text-slate-600 font-semibold mb-2">
                                    <label class="flex items-center gap-2 cursor-pointer mb-0">
                                        <input type="checkbox" id="check-all-panitia" onchange="toggleSelectAllPanitia(this)">
                                        <span>Pilih Semua Belum Ditugaskan ({{ $panitia_available->count() }})</span>
                                    </label>
                                </div>
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                                    @foreach ($panitia_available as $p_avail)
                                        <label class="flex items-center gap-2 text-xs text-slate-700 hover:bg-white p-1.5 rounded-lg cursor-pointer transition border border-transparent hover:border-slate-200 mb-0">
                                            <input type="checkbox" name="user_id[]" value="{{ $p_avail->id }}" class="check-panitia-item" disabled>
                                            <span class="font-medium text-slate-800">{{ $p_avail->name }}</span>
                                            <code class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded text-[10px] ml-auto font-mono">{{ $p_avail->rfid_uid }}</code>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label>Pilih Divisi</label>
                            <select class="inp" name="divisi_id" required>
                                @foreach ($divisi as $div_opt)
                                    <option value="{{ $div_opt->id }}">{{ $div_opt->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-tambah-panitia')">
                            Batal
                        </button>
                        <button class="btn-primary flex-1 justify-center" type="submit" {{ $panitia_available->count() == 0 ? 'disabled' : '' }}>
                            Simpan Panitia
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

<script>
    const agenda = true

    function showDetailModal(id, nama, checkin, batasCheckin, checkout, batasCheckout, encryptedId) {
        document.getElementById('detail-nama').textContent = nama;
        document.getElementById('detail-checkin').textContent = checkin || '-';
        document.getElementById('detail-batas-checkin').textContent = batasCheckin || '-';
        document.getElementById('detail-checkout').textContent = checkout || '-';
        document.getElementById('detail-batas-checkout').textContent = batasCheckout || '-';
        document.getElementById('link-absensi').href = `/agenda/absensi/${encryptedId}`;
        document.getElementById('cetak').href = `/report/agenda/${encryptedId}`;

        showModal('modal-detail-acara');
    }
    function showDetailDivisiModal(id, nama, detail) {
        document.getElementById('detail-divisi-nama').textContent = nama;
        document.getElementById('detail-divisi-deskripsi').textContent = detail || '-';
        showModal('modal-detail-divisi');
    }

    function formatForDateTimeLocal(dtStr) {
        if (!dtStr || dtStr === '-') return '';
        return dtStr.replace(' ', 'T').substring(0, 16);
    }

    function showEditModal(id, nama, checkin, batasCheckin, checkout, batasCheckout) {
        document.getElementById('form-edit-agenda').action = `/agenda/update/${id}`
        document.getElementById('edit-agenda-nama').value = nama;
        document.getElementById('edit-agenda-checkin').value = formatForDateTimeLocal(checkin);
        document.getElementById('edit-agenda-batas-checkin').value = formatForDateTimeLocal(batasCheckin);
        document.getElementById('edit-agenda-checkout').value = formatForDateTimeLocal(checkout);
        document.getElementById('edit-agenda-batas-checkout').value = formatForDateTimeLocal(batasCheckout);

        showModal('modal-edit-acara');
    }

    function printDetailAgenda() {
        window.print();
    }

    function confirmDeleteAgenda(encryptedId, nama) {
        Swal.fire({
            title: 'Hapus Agenda?',
            text: `Apakah Anda yakin ingin menghapus agenda "${nama}"? Semua data absensi terkait juga akan terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/agenda/delete/${encryptedId}`;
            }
        });
    }

    function confirmDeleteDivisi(encryptedId, nama) {
        Swal.fire({
            title: 'Hapus Divisi?',
            text: `Apakah Anda yakin ingin menghapus divisi "${nama}"? Penugasan panitia dalam divisi ini juga akan terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/divisi/delete/${encryptedId}`;
            }
        });
    }

    function confirmDeletePanitia(encryptedId, nama) {
        Swal.fire({
            title: 'Hapus Panitia?',
            text: `Apakah Anda yakin ingin menghapus "${nama}" dari kepanitiaan acara ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/panitia/delete/${encryptedId}`;
            }
        });
    }

    // Panitia Filtering & Search Logic
    let currentPanitiaFilter = 'all';

    function filterPanitiaList(type) {
        currentPanitiaFilter = type;

        const filterBtns = document.querySelectorAll('.btn-panitia-filter');
        filterBtns.forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            btn.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200');
        });

        const activeBtn = document.getElementById('btn-panitia-' + type);
        if (activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
        }

        applyPanitiaFiltering();
    }

    function searchPanitiaList() {
        applyPanitiaFiltering();
    }

    function applyPanitiaFiltering() {
        const query = (document.getElementById('search-panitia')?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.row-panitia');
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const name = row.getAttribute('data-name') || '';
            const rfid = row.getAttribute('data-rfid') || '';

            const matchesStatus = (currentPanitiaFilter === 'all') || (status === currentPanitiaFilter);
            const matchesQuery = !query || name.includes(query) || rfid.includes(query);

            if (matchesStatus && matchesQuery) {
                row.classList.remove('hidden');
                visibleCount++;
                const numberCell = row.querySelector('.row-panitia-number');
                if (numberCell) numberCell.textContent = visibleCount;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyRow = document.getElementById('row-panitia-empty');
        if (emptyRow) {
            if (visibleCount === 0) {
                emptyRow.classList.remove('hidden');
            } else {
                emptyRow.classList.add('hidden');
            }
        }
    }

    let isMultiSelectMode = false;
    function togglePanitiaSelectionMode() {
        isMultiSelectMode = !isMultiSelectMode;
        const singleBox = document.getElementById('panitia-single-select-box');
        const multiBox = document.getElementById('panitia-multi-select-box');
        const singleSelect = document.getElementById('select-panitia-single');
        const checkboxes = document.querySelectorAll('.check-panitia-item');
        const toggleBtn = document.getElementById('btn-toggle-multi');

        if (isMultiSelectMode) {
            if (singleBox) singleBox.classList.add('hidden');
            if (multiBox) multiBox.classList.remove('hidden');
            if (singleSelect) singleSelect.disabled = true;
            checkboxes.forEach(cb => cb.disabled = false);
            if (toggleBtn) toggleBtn.textContent = '← Pilih 1 Mahasiswa';
        } else {
            if (singleBox) singleBox.classList.remove('hidden');
            if (multiBox) multiBox.classList.add('hidden');
            if (singleSelect) singleSelect.disabled = false;
            checkboxes.forEach(cb => cb.disabled = true);
            if (toggleBtn) toggleBtn.textContent = '+ Pilih Banyak Sekaligus';
        }
    }

    function toggleSelectAllPanitia(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.check-panitia-item');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
    }

    function openTambahPanitiaSingle(userId, userName) {
        if (isMultiSelectMode) {
            togglePanitiaSelectionMode();
        }
        const singleSelect = document.getElementById('select-panitia-single');
        if (singleSelect) {
            let opt = singleSelect.querySelector(`option[value="${userId}"]`);
            if (!opt) {
                opt = document.createElement('option');
                opt.value = userId;
                opt.textContent = `${userName}`;
                singleSelect.appendChild(opt);
            }
            singleSelect.value = userId;
        }
        showModal('modal-tambah-panitia');
    }

    function switchTab(tabName, btnName) {
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.add('hidden'));

        const buttons = document.querySelectorAll('[id^="btn-tab-"]');
        const tambah = document.querySelectorAll('[id^="tambah-"]');

        tambah.forEach(bt => {
            bt.classList.add('hidden')
        })
        buttons.forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-slate-600', 'hover:text-slate-900',
                'hover:border-slate-300');
        });

        const selectedTab = document.getElementById(tabName);
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }

        const selectedButton = document.getElementById('btn-' + tabName);
        if (selectedButton) {
            selectedButton.classList.remove('border-transparent', 'text-slate-600', 'hover:text-slate-900',
                'hover:border-slate-300');
            selectedButton.classList.add('border-blue-600', 'text-blue-600');
        }

        localStorage.setItem('agendaActiveTab', tabName);
        if (btnName != '-') {
            btnn = document.getElementById(btnName)
            if (btnn) {
                btnn.classList.remove('hidden')
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = localStorage.getItem('agendaActiveTab') || 'tab-daftar';
        let btnName = 'tambah-acara';
        if (activeTab === 'tab-divisi') {
            btnName = 'tambah-divisi';
        } else if (activeTab === 'tab-panitia') {
            btnName = 'tambah-panitia';
        }
        switchTab(activeTab, btnName);
    });
</script>
