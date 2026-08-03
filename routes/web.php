<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalonKaryawanController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('hrd')->group(function () {
    Route::get('/calon-karyawan', [CalonKaryawanController::class, 'index'])->name('calon-karyawan.index');
    Route::get('/calon-karyawan/create', [CalonKaryawanController::class, 'create'])->name('calon-karyawan.create');
    Route::post('/calon-karyawan', [CalonKaryawanController::class, 'store'])->name('calon-karyawan.store');
});