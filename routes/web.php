<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalonKaryawanController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('hrd')->group(function () {
    Route::get('/calon-karyawan', [CalonKaryawanController::class, 'index'])->name('calon-karyawan.index');
    Route::get('/calon-karyawan/create', [CalonKaryawanController::class, 'create'])->name('calon-karyawan.create');
    Route::post('/calon-karyawan', [CalonKaryawanController::class, 'store'])->name('calon-karyawan.store');
    Route::get('/calon-karyawan/{calon}', [CalonKaryawanController::class, 'show'])->name('calon-karyawan.show');
    Route::get('/calon-karyawan/{calon}/edit', [CalonKaryawanController::class, 'edit'])->name('calon-karyawan.edit');
    Route::put('/calon-karyawan/{calon}', [CalonKaryawanController::class, 'update'])->name('calon-karyawan.update');
    Route::delete('/calon-karyawan/{calon}', [CalonKaryawanController::class, 'destroy'])->name('calon-karyawan.destroy');
});