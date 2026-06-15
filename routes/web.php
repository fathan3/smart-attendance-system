<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\MahasiswaController;
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

// Delete
Route::get('/acara/delete/{id}', [AcaraController::class, 'delete'])->name('acara.delete');

Route::get('/acara/agenda/{acara_id}', [AgendaController::class, 'index'])->name('acara.agenda');
Route::get('acara/agenda/divisi/{divisi_id}', [DivisiController::class, 'divisiAgenda'])->name('agenda.divisi');

Route::get('/checkin/{id_agenda}', [AgendaController::class, 'checkin'])->name('checkin');
Route::post('/checkin/agenda/{agenda_id}', [AgendaController::class, 'checkin_panitia'])->name('checkin.agenda');
Route::get('/checkout/{id_agenda}', [AgendaController::class, 'checkout']);

// Route::get('/laporan', [LaporanController::class, 'index']);
