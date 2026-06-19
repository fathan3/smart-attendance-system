# Smart Attendance System (Sistem Absensi Himatif)

Sistem Absensi Himatif adalah aplikasi web terintegrasi dengan scanner RFID untuk mencatat kehadiran mahasiswa dan panitia dalam acara Himatif secara real-time.

---

## Fitur Utama

1. **Dashboard Statistik**: Menampilkan jumlah mahasiswa aktif dan kehadiran harian.
2. **Manajemen Acara & Agenda**: Pengelolaan acara, jadwal agenda, batas waktu check-in/checkout, dan lokasi.
3. **Manajemen Divisi & Panitia**: Pengelompokan panitia acara berdasarkan divisi.
4. **Data Mahasiswa & RFID**: Pendaftaran mahasiswa beserta UID kartu RFID.
5. **Absensi RFID Presisi**: Check-in dan checkout menggunakan scan RFID dengan sistem auto-focus, pendeteksi kecepatan input keyboard (mencegah input manual), dan notifikasi pop-up.
6. **Laporan Kehadiran**: Rekapitulasi absensi per agenda dan cetak log kehadiran.

---

## Stack Teknologi

* **Backend**: Laravel 13.x (PHP >= 8.3)
* **Frontend**: Tailwind CSS v4, Blade, Vite
* **Database**: MySQL / SQLite
* **Ekstensi**: SweetAlert2, Laravel DomPDF

---

## Prasyarat Sistem

* PHP >= 8.3
* Composer
* Node.js & NPM
* MySQL / MariaDB Server
* RFID Reader (Keyboard Emulation USB HID)

---

## Panduan Instalasi & Konfigurasi

### 1. Kloning Repositori
```bash
git clone https://github.com/muhammadalimurtadho05/smart-attendance-system.git
cd smart-attendance-system
```

### 2. Jalankan Perintah Setup / Migrasi
Anda dapat menggunakan script otomatis:
```bash
composer setup
```

Atau jalankan perintah secara manual:
```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```
*Catatan: Pastikan database kosong sudah dibuat sebelum menjalankan migrasi.*

### 3. Konfigurasi Database (.env)
Sesuaikan koneksi database di file `.env` jika diperlukan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Jalankan Aplikasi
Jalankan server Laravel dan compiler Vite menggunakan perintah berikut:

Terminal 1 (Laravel Server):
```bash
php artisan serve
```

Terminal 2 (Vite Assets compilation):
```bash
npm run dev
```

Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## Cara Penggunaan Absensi RFID

1. Daftarkan mahasiswa beserta UID RFID pada menu **Mahasiswa**.
2. Buka menu **Acara**, pilih agenda yang aktif, lalu masuk ke halaman **Check-in** atau **Checkout**.
3. Tempelkan kartu RFID pada scanner. Sistem akan membaca UID kartu secara otomatis dan memproses data absensi.
