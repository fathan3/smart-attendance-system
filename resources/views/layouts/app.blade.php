<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Attedance</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Inter', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            DEFAULT: '#0F172A',
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A'
                        },
                        accent: {
                            DEFAULT: '#3B82F6',
                            light: '#60A5FA',
                            dark: '#2563EB'
                        },
                        jade: {
                            DEFAULT: '#10B981',
                            light: '#34D399',
                            dark: '#059669'
                        },
                        sky: {
                            DEFAULT: '#0EA5E9',
                            light: '#38BDF8',
                            dark: '#0284C7'
                        },
                    }
                }
            }
        }
    </script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F8FAFC;
            color: #0F172A;
            margin: 0;
        }

        .font-display {
            font-family: 'Inter', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #F8FAFC;
        }

        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 3px;
        }

        /* Sidebar */
        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.collapsed {
            width: 72px !important;
        }

        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .sidebar-logo-text,
        #sidebar.collapsed .sidebar-section-title {
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
            width: 0;
        }

        #sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 12px;
        }

        #sidebar.collapsed .nav-icon {
            margin: 0;
        }

        /* Page transitions */
        .page {
            display: none;
            animation: fadeUp 0.35s ease;
        }

        .page.active {
            display: block;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cards */
        /* Tailwind utilities are used for cards now */

        /* Nav items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            color: #64748B;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 4px;
            white-space: nowrap;
        }

        .nav-item:hover {
            background: #F1F5F9;
            color: #0F172A;
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: #2563EB;
        }

        .nav-item.active svg {
            color: #2563EB;
        }

        .nav-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748B;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
            font-weight: 600;
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #F1F5F9;
            font-size: 14px;
            color: #334155;
        }

        .data-table tbody tr:hover {
            background: #F8FAFC;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Buttons */
        .btn-primary {
            background: #3B82F6;
            color: white;
            border: 1px solid #2563EB;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Inter';
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background: #2563EB;
        }

        .btn-secondary {
            background: #FFFFFF;
            color: #334155;
            border: 1px solid #CBD5E1;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Inter';
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: #F8FAFC;
            border-color: #94A3B8;
        }

        .btn-green {
            background: #10B981;
            color: white;
            border: 1px solid #059669;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter';
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-green:hover {
            background: #059669;
        }

        .btn-danger {
            background: #EF4444;
            color: white;
            border: 1px solid #DC2626;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter';
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-danger:hover {
            background: #DC2626;
        }

        /* Inputs */
        .inp {
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 10px 14px;
            color: #0F172A;
            font-size: 14px;
            font-family: 'Inter';
            width: 100%;
            transition: all 0.2s;
            outline: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .inp:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .inp::placeholder {
            color: #94A3B8;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            margin-bottom: 6px;
            font-family: 'Inter';
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .modal-box {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 560px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 32px;
            position: relative;
            animation: modalIn 0.2s ease-out;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.98) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-green {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-orange {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-blue {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-red {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-gray {
            background: rgba(148, 163, 184, 0.1);
            color: #94A3B8;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        /* RFID pulse */
        .rfid-pulse {
            animation: rfidPulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes rfidPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            50% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0.2);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .rfid-pulse-red {
            animation: rfidPulseRed 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes rfidPulseRed {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            50% {
                box-shadow: 0 0 0 15px rgba(239, 68, 68, 0.2);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        /* Progress bar */
        .progress-bar {
            background: #E2E8F0;
            border-radius: 99px;
            height: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }

        /* Accordion */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .accordion-content.open {
            max-height: 2000px;
        }

        /* Print */
        @media print {

            #sidebar,
            #topbar,
            .no-print {
                display: none !important;
            }

            #main-content {
                margin: 0 !important;
                padding: 20px !important;
            }

            body {
                background: white;
                color: black;
            }

            .bg-white {
                border: 1px solid #ccc;
                box-shadow: none;
            }
        }

        /* Loading Overlay */
        #global-loader {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.5); /* Semi transparan */
            backdrop-filter: blur(4px); /* Efek ngeblur untuk elemen di belakangnya */
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        #global-loader.hidden-loader {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-logo {
            width: 70px;
            height: auto;
            animation: flipAnimation 1.2s infinite ease-in-out;
            transform-style: preserve-3d;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        @keyframes flipAnimation {
            0% {
                transform: perspective(400px) rotateY(0deg);
            }
            100% {
                transform: perspective(400px) rotateY(360deg);
            }
        }
    </style>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
</head>

<body class="flex h-screen overflow-hidden">

    <!-- Loading Screen -->
    <div id="global-loader">
        <img src="{{ asset('images/logo.png') }}" alt="Loading..." class="loader-logo">
    </div>

    @include('components.sidebar')


    <div class="flex-1 flex flex-col overflow-hidden">

        @include('components.topbar')

        <main id="main-content" class="flex-1 overflow-y-auto bg-slate-50 p-6">

            @yield('main-content')
        </main>

        @include('components.footer')
    </div>

    <div id="toast"
        class="fixed bottom-6 right-6 z-[9999] transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
        <div id="toast-inner"
            class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-2xl text-sm font-500 bg-emerald-500 text-ink-900">
        </div>
    </div>

    <script>
        const mahasiswaData = [{
                id: 1,
                rfid: 'A1B2C3D4',
                nama: 'Ahmad Fauzi',
                email: 'ahmad@kampus.ac.id',
                jabatan: 'Ketua',
                foto: '',
                status: 'aktif'
            },
            {
                id: 2,
                rfid: 'E5F6G7H8',
                nama: 'Siti Rahayu',
                email: 'siti@kampus.ac.id',
                jabatan: 'Sekretaris',
                foto: '',
                status: 'aktif'
            },
            {
                id: 3,
                rfid: 'I9J0K1L2',
                nama: 'Budi Santoso',
                email: 'budi@kampus.ac.id',
                jabatan: 'Anggota',
                foto: '',
                status: 'aktif'
            },
            {
                id: 4,
                rfid: 'M3N4O5P6',
                nama: 'Dewi Lestari',
                email: 'dewi@kampus.ac.id',
                jabatan: 'Bendahara',
                foto: '',
                status: 'aktif'
            },
            {
                id: 5,
                rfid: 'Q7R8S9T0',
                nama: 'Rudi Hermawan',
                email: 'rudi@kampus.ac.id',
                jabatan: 'Anggota',
                foto: '',
                status: 'nonaktif'
            },
        ];

        const acaraData = [{
                id: 1,
                nama: 'Seminar Nasional AI 2025',
                tanggalMulai: '2025-06-06',
                tanggalSelesai: '2025-06-06',
                lokasi: 'Aula Utama',
                deskripsi: 'Seminar tentang perkembangan AI di Indonesia',
                status: 'aktif',
                agenda: [{
                        id: 1,
                        nama: 'Pembukaan & Registrasi',
                        mulai: '07:30',
                        selesai: '08:00',
                        batasAbsen: '08:15'
                    },
                    {
                        id: 2,
                        nama: 'Sesi I: AI Fundamentals',
                        mulai: '08:00',
                        selesai: '10:00',
                        batasAbsen: '08:30'
                    },
                    {
                        id: 3,
                        nama: 'Sesi II: Applied AI',
                        mulai: '13:00',
                        selesai: '15:00',
                        batasAbsen: '13:30'
                    },
                ]
            },
            {
                id: 2,
                nama: 'Workshop UI/UX Design',
                tanggalMulai: '2025-06-07',
                tanggalSelesai: '2025-06-07',
                lokasi: 'Lab Komputer 2',
                deskripsi: 'Workshop praktis desain antarmuka',
                status: 'upcoming',
                agenda: [{
                        id: 4,
                        nama: 'Materi Figma Dasar',
                        mulai: '09:00',
                        selesai: '11:00',
                        batasAbsen: '09:20'
                    },
                    {
                        id: 5,
                        nama: 'Praktik Desain',
                        mulai: '13:00',
                        selesai: '15:00',
                        batasAbsen: '13:15'
                    },
                ]
            },
            {
                id: 3,
                nama: 'Rapat Himpunan Mahasiswa',
                tanggalMulai: '2025-06-09',
                tanggalSelesai: '2025-06-09',
                lokasi: 'Ruang Rapat',
                deskripsi: 'Rapat koordinasi bulanan',
                status: 'upcoming',
                agenda: [{
                    id: 6,
                    nama: 'Rapat Pleno',
                    mulai: '13:00',
                    selesai: '17:00',
                    batasAbsen: '13:30'
                }]
            },
        ];

        const absensiLog = [{
                mahasiswa: 'Ahmad Fauzi',
                agenda: 'Seminar AI — Sesi I',
                masuk: '07:58',
                pulang: '10:05',
                status: 'hadir'
            },
            {
                mahasiswa: 'Siti Rahayu',
                agenda: 'Seminar AI — Sesi I',
                masuk: '08:02',
                pulang: '10:00',
                status: 'hadir'
            },
            {
                mahasiswa: 'Budi Santoso',
                agenda: 'Seminar AI — Sesi I',
                masuk: '09:15',
                pulang: '10:08',
                status: 'terlambat'
            },
            {
                mahasiswa: 'Dewi Lestari',
                agenda: 'Seminar AI — Sesi I',
                masuk: '07:55',
                pulang: '10:02',
                status: 'hadir'
            },
            {
                mahasiswa: 'Rudi Hermawan',
                agenda: 'Seminar AI — Sesi I',
                masuk: '-',
                pulang: '-',
                status: 'tidak-hadir'
            },
        ];

        window.onload = () => {
            // Sembunyikan loading screen setelah semua elemen termuat
            setTimeout(() => {
                const loader = document.getElementById('global-loader');
                if(loader) loader.classList.add('hidden-loader');
            }, 800); // 800ms delay agar animasi terlihat minimal sekali putaran

            startClock();
            renderMahasiswaTable(mahasiswaData);
            renderAcaraList();
            renderAbsensiLog();
            initCharts();
        };

        function navigate(page) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            document.getElementById('page-' + page).classList.add('active');
            document.querySelector(`[onclick="navigate('${page}')"]`).classList.add('active');
            const titles = {
                dashboard: ['Dashboard', 'Ringkasan sistem absensi'],
                mahasiswa: ['Daftar Mahasiswa', 'Manajemen data mahasiswa'],
                acara: ['Acara & Agenda', 'Kelola acara dan agenda'],
                absensi: ['Absensi', 'Proses absensi dengan RFID'],
                laporan: ['Laporan & Cetak', 'Rekap dan export data']
            };
            document.getElementById('page-title').textContent = titles[page][0];
            document.getElementById('page-sub').textContent = titles[page][1];
            if (page === 'laporan') setTimeout(initLaporanChart, 100);
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function startClock() {
            const update = () => {
                const now = new Date();
                document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('datestr').textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            };
            update();
            setInterval(update, 1000);
        }


        function filterTable(q) {
            const filtered = mahasiswaData.filter(m => m.nama.toLowerCase().includes(q.toLowerCase()) || m.rfid
            .toLowerCase().includes(q.toLowerCase()) || m.email.toLowerCase().includes(q.toLowerCase()));
            renderMahasiswaTable(filtered);
        }

        function saveMahasiswa() {
            showToast('✓ Mahasiswa berhasil ditambahkan!', 'jade');
            closeModal(null, 'modal-tambah-mahasiswa');
        }

        function editMahasiswa(id) {
            showToast('✏️ Mode edit mahasiswa #' + id, 'sky');
        }

        function hapusMahasiswa(id) {
            showToast('🗑️ Mahasiswa dihapus', 'red');
        }

        function toggleAccordion(id) {
            const el = document.getElementById(id);
            el.classList.toggle('open');
        }

        function saveAcara() {
            showToast('✓ Acara berhasil disimpan!', 'jade');
            closeModal(null, 'modal-tambah-acara');
        }

        function addAgendaRow() {
            const row = document.createElement('div');
            row.className = 'agenda-row grid grid-cols-12 gap-2 items-center';
            row.innerHTML =
                `<input type="text" class="inp col-span-5 text-sm py-2" placeholder="Nama agenda"><input type="time" class="inp col-span-3 text-sm py-2"><input type="time" class="inp col-span-3 text-sm py-2"><button class="col-span-1 text-red-400 hover:text-red-300 flex items-center justify-center" onclick="removeAgenda(this)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
            document.getElementById('agenda-rows').appendChild(row);
        }

        function removeAgenda(btn) {
            btn.closest('.agenda-row').remove();
        }

        function generateAgenda() {
            const templates = [
                ['Pembukaan & Registrasi', '07:30', '08:00'],
                ['Sesi Materi I', '08:00', '10:00'],
                ['Istirahat', '10:00', '10:30'],
                ['Sesi Materi II', '10:30', '12:00'],
                ['Ishoma', '12:00', '13:00'],
                ['Sesi Materi III', '13:00', '15:00'],
                ['Penutupan', '15:00', '15:30']
            ];
            const container = document.getElementById('agenda-rows');
            container.innerHTML = '';
            templates.forEach(([nama, mulai, selesai]) => {
                const row = document.createElement('div');
                row.className = 'agenda-row grid grid-cols-12 gap-2 items-center';
                row.innerHTML =
                    `<input type="text" class="inp col-span-5 text-sm py-2" value="${nama}"><input type="time" class="inp col-span-3 text-sm py-2" value="${mulai}"><input type="time" class="inp col-span-3 text-sm py-2" value="${selesai}"><button class="col-span-1 text-red-400 hover:text-red-300 flex items-center justify-center" onclick="removeAgenda(this)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
                container.appendChild(row);
            });
            showToast('⚡ 7 agenda berhasil di-generate!', 'jade');
        }

        function addAgendaToAcara(id) {
            showToast('+ Tambah agenda ke acara #' + id, 'sky');
        }

        function renderAbsensiLog() {
            const tbody = document.getElementById('absensi-tbody');
            tbody.innerHTML = absensiLog.map(a => {
                const statusMap = {
                    hadir: 'badge-green',
                    terlambat: 'badge-orange',
                    'tidak-hadir': 'badge-red'
                };
                const statusLabel = {
                    hadir: 'Hadir',
                    terlambat: 'Terlambat',
                    'tidak-hadir': 'Tidak Hadir'
                };
                return `<tr><td class="text-slate-900 font-500">${a.mahasiswa}</td><td>${a.agenda}</td><td class="${a.masuk !== '-' ? 'text-emerald-600' : 'text-slate-400'}">${a.masuk}</td><td class="${a.pulang !== '-' ? 'text-sky-600' : 'text-slate-400'}">${a.pulang}</td><td><span class="badge ${statusMap[a.status]}">${statusLabel[a.status]}</span></td></tr>`;
            }).join('');
        }

        function processAbsensi(type) {
            const rfid = document.getElementById('rfid-input').value;
            const agenda = document.getElementById('absen-agenda').value;
            if (!rfid) {
                showToast('⚠️ Tempelkan atau masukkan RFID', 'orange');
                return;
            }
            if (!agenda) {
                showToast('⚠️ Pilih agenda terlebih dahulu', 'orange');
                return;
            }
            const now = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const result = document.getElementById('absen-result');
            result.className = 'mt-3 p-3 rounded-xl text-sm text-center';
            if (type === 'masuk') {
                result.className += ' bg-emerald-50 text-emerald-600 border border-emerald-200';
                result.innerHTML = `✓ Absen masuk berhasil · ${now}`;
                showToast('✓ Absen masuk tercatat: ' + rfid, 'jade');
            } else {
                result.className += ' bg-sky-50 text-sky-600 border border-sky-200';
                result.innerHTML = `✓ Absen pulang berhasil · ${now}`;
                showToast('✓ Absen pulang tercatat: ' + rfid, 'sky');
            }
            result.classList.remove('hidden');
            document.getElementById('rfid-input').value = '';
        }

        function initCharts() {
            const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            const hadir = [210, 195, 220, 205, 192, 180, 215];
            const absen = [38, 53, 28, 43, 56, 68, 33];

            const attChartEl = document.getElementById('attendanceChart');
            if (attChartEl) {
                new Chart(attChartEl, {
                    type: 'bar',
                    data: {
                        labels: days,
                        datasets: [{
                                label: 'Hadir',
                                data: hadir,
                                backgroundColor: '#10B981',
                                borderRadius: 4
                            },
                            {
                                label: 'Absen',
                                data: absen,
                                backgroundColor: '#334155',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(255,255,255,0.02)'
                                },
                                ticks: {
                                    color: '#94A3B8',
                                    font: {
                                        family: 'Inter',
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(255,255,255,0.02)'
                                },
                                ticks: {
                                    color: '#94A3B8',
                                    font: {
                                        family: 'Inter',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const statChartEl = document.getElementById('statusChart');
            if (statChartEl) {
                new Chart(statChartEl, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
                        datasets: [{
                            data: [192, 24, 32],
                            backgroundColor: ['#10B981', '#F59E0B', '#334155'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }

        function initLaporanChart() {
            const ctx = document.getElementById('laporanChart');
            if (!ctx || ctx._chartDestroyed) return;
            if (ctx._chart) {
                ctx._chart.destroy();
            }
            const c = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Kehadiran (%)',
                        data: [78, 82, 79, 85, 88, 84],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.02)'
                            },
                            ticks: {
                                color: '#94A3B8',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.02)'
                            },
                            ticks: {
                                color: '#94A3B8',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            },
                            min: 60,
                            max: 100
                        }
                    }
                }
            });
            ctx._chart = c;
        }

        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(event, id) {
            if (!event || event.target === document.getElementById(id)) document.getElementById(id).classList.add('hidden');
        }

        let toastTimer;

        function showToast(msg, color = 'jade') {
            clearTimeout(toastTimer);
            const t = document.getElementById('toast');
            const inner = document.getElementById('toast-inner');
            const colors = {
                jade: 'bg-emerald-500 text-ink-900',
                orange: 'bg-blue-600 text-slate-900',
                sky: 'bg-sky-500 text-slate-900',
                red: 'bg-red-500 text-slate-900'
            };
            inner.className =
                `flex items-center gap-3 px-5 py-3 rounded-xl shadow-2xl text-sm font-500 ${colors[color] || colors.jade}`;
            inner.textContent = msg;
            t.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            t.classList.add('opacity-100', 'translate-y-0');
            toastTimer = setTimeout(() => {
                t.classList.add('opacity-0', 'translate-y-4');
                t.classList.remove('opacity-100', 'translate-y-0');
            }, 3000);
        }

        function printSection(id) {
            const el = document.getElementById(id);
            if (!el) {
                window.print();
                return;
            }
            const win = window.open('', '_blank');
            win.document.write(`<!DOCTYPE html><html><head><title>SiAbsen - Laporan</title>
  <style>
    body { font-family: 'DM Sans', Arial, sans-serif; color: #111; background: #fff; padding: 32px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f0f0f0; padding: 10px 14px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #555; }
    td { padding: 10px 14px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; }
    h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
    .header { border-bottom: 2px solid #FF4D00; padding-bottom: 12px; margin-bottom: 24px; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .hadir { background: #e8faf4; color: #00a87a; }
    .terlambat { background: #fff1eb; color: #FF4D00; }
    .tidak-hadir { background: #fef2f2; color: #e00; }
    @media print { body { padding: 0; } }
  </style></head><body>
  <div class="header"><h1>Laporan Absensi</h1><p style="color:#888;font-size:13px">Dicetak: ${new Date().toLocaleString('id-ID')}</p></div>
  ${el.innerHTML}
  <script>window.onload=()=>{ window.print(); }; window.onafterprint=()=>{ window.close(); };<\/script>
  </body></html>`);
            win.document.close();
        }

        function formatDate(d) {
            const date = new Date(d);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

        }

        if (typeof agenda == 'undefined') {
            localStorage.removeItem('agendaActiveTab')
        }
    </script>
</body>

</html>
