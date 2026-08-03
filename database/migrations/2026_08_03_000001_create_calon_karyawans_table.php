<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon_karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();

            // Data Pribadi
            $table->string('nama');
            $table->string('panggilan')->nullable();
            $table->string('no_ktp', 30)->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('kota_ktp')->nullable();
            $table->string('gol_darah', 5)->nullable();
            $table->string('no_sim', 30)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('sex', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->unsignedSmallInteger('tinggi_cm')->nullable();
            $table->unsignedSmallInteger('berat_kg')->nullable();
            $table->string('warga_negara')->nullable();
            $table->string('status_nikah')->nullable();

            // Alamat & Kontak
            $table->text('alamat')->nullable();
            $table->string('no_telp', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('status_tempat_tinggal')->nullable();
            $table->string('hobby')->nullable();
            $table->text('keterangan')->nullable();

            // Data Pekerjaan
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_resigned')->nullable();
            $table->string('alasan_resigned')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('posting')->nullable();
            $table->boolean('aktif')->default(true);
            $table->string('awal_group_of_employee')->nullable();
            $table->string('awal_cabang')->nullable();
            $table->string('group_of_employee')->nullable();
            $table->unsignedTinyInteger('cuti_per_tahun')->default(12);
            $table->string('kategori')->nullable();
            $table->string('sub_kategori')->nullable();
            $table->string('divisi')->nullable();
            $table->string('jalur_pendaftaran')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('nrp', 30)->nullable();
            $table->string('organisasi')->nullable();
            $table->string('grup1')->nullable();
            $table->string('grup2')->nullable();
            $table->string('grup3')->nullable();

            // Dokumen & Identitas Tambahan
            $table->string('no_passport', 30)->nullable();
            $table->date('passport_expired')->nullable();
            $table->string('no_visa', 30)->nullable();
            $table->string('no_kk', 30)->nullable();
            $table->string('no_bpjs_kesehatan', 30)->nullable();
            $table->string('no_bpjs_tenaga_kerja', 30)->nullable();

            // Informasi Rekening
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening', 40)->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->string('tipe_rekening')->nullable();

            // Foto & password akun
            $table->string('foto_path')->nullable();
            $table->string('password')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon_karyawans');
    }
};
