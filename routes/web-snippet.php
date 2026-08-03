<?php

// Tambahkan baris ini ke routes/web.php pada project Laravel kamu
// (jangan lupa import: use App\Http\Controllers\CalonKaryawanController;)

use App\Http\Controllers\CalonKaryawanController;

Route::prefix('hrd')->group(function () {
    Route::get('/calon-karyawan', [CalonKaryawanController::class, 'index'])->name('calon-karyawan.index');
    Route::get('/calon-karyawan/create', [CalonKaryawanController::class, 'create'])->name('calon-karyawan.create');
    Route::post('/calon-karyawan', [CalonKaryawanController::class, 'store'])->name('calon-karyawan.store');
});
