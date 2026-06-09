<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('absensi.dashboard');
});

Route::get('/mahasiswa',[MahasiswaController::class, 'index']);

Route::get('/acara',[AcaraController::class,'index']);
Route::get('/acara/agenda/{acara_id}',[AgendaController::class,'index']);

Route::get('/checkin/{id_agenda}',[AgendaController::class,'checkin']);
Route::get('/checkout/{id_agenda}',[AgendaController::class,'checkout']);

Route::get('/laporan', function () {
    return view('absensi.laporan');
});
