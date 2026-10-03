<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PDFReportingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

Route::get('/acara', [AcaraController::class, 'index'])->name('acara');

// Store
Route::post('/acara/store', [AcaraController::class, 'store'])->name('acara.store');
Route::post('/agenda/store', [AgendaController::class, 'store'])->name('agenda.store');
Route::post('/divisi/store', [DivisiController::class, 'store'])->name('divisi.store');
Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
Route::post('/panitia/store', [DivisiController::class, 'store_panitia'])->name('panitia.store');

// Update
Route::post('/acara/update/{id}', [AcaraController::class, 'update'])->name('acara.update');
Route::post('/agenda/update/{id}', [AgendaController::class, 'update'])->name('agenda.update');
Route::post('/mahasiswa/update/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');

// Delete
Route::get('/acara/delete/{id}', [AcaraController::class, 'delete'])->name('acara.delete');
Route::get('/agenda/delete/{id}', [AgendaController::class, 'delete'])->name('agenda.delete');
Route::get('/divisi/delete/{id}', [DivisiController::class, 'delete_divisi'])->name('divisi.delete');
Route::get('/mahasiswa/delete/{id}', [MahasiswaController::class, 'delete'])->name('mahasiswa.delete');
Route::get('/panitia/delete/{id}', [DivisiController::class, 'delete_panitia'])->name('panitia.delete');

Route::get('/acara/agenda/{acara_id}', [AgendaController::class, 'index'])->name('acara.agenda');
Route::get('acara/agenda/divisi/{divisi_id}', [DivisiController::class, 'divisiAgenda'])->name('agenda.divisi');
Route::get('agenda/absensi/{agenda_id}', [AgendaController::class, 'daftarAbsensi'])->name('agenda.absensi');
Route::post('agenda/absensi/update', [AgendaController::class, 'updateAbsensi'])->name('agenda.absensi.update');

Route::get('/checkin/{id_agenda}', [AgendaController::class, 'checkin'])->name('checkin');
Route::post('/checkin/agenda/{agenda_id}', [AgendaController::class, 'checkin_panitia'])->name('checkin.agenda');
Route::post('/absensi/update-keterangan', [AgendaController::class, 'updateKeterangan'])->name('absensi.update_keterangan');
Route::get('/checkout/{id_agenda}', [AgendaController::class, 'checkout'])->name('checkout');
Route::post('/checkout/agenda/{agenda_id}', [AgendaController::class, 'checkout_panitia'])->name('checkout.agenda');

// Reporting
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.pdf');
Route::get('/report/agenda/{agenda_id}', [PDFReportingController::class, 'generatePDF'])->name('report.agenda');
