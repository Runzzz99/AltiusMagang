<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kerabats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_karyawan_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('hubungan')->nullable(); // Suami/Istri, Anak, Orang Tua, dll
            $table->string('no_telp', 30)->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kerabats');
    }
};
