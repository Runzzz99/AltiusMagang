<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonKaryawan extends Model
{
    use HasFactory;

    // Tabel shared SQL Server milik perusahaan (skema lama), bukan tabel migrasi sendiri.
    protected $table = 'CalonEmployee';

    protected $primaryKey = 'Kode';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $guarded = [];

    /**
     * Mapping nama field aplikasi (snake_case) -> kolom database (PascalCase).
     * Aplikasi memakai nama field aplikasi; kolom database diakses via $this->getAttribute.
     */
    protected $appFieldMap = [
        'kode'                  => 'Kode',
        'nama'                  => 'Nama',
        'panggilan'             => 'Panggilan',
        'alamat'                => 'Alamat',
        'kota'                  => 'Kota',
        'no_telp'               => 'Telp',
        'no_hp'                 => 'NoHP',
        'no_ktp'                => 'NoKTP',
        'alamat_ktp'            => 'AlamatKTP',
        'kota_ktp'              => 'KotaKTP',
        'no_sim'                => 'NoSIM_A',
        'tempat_lahir'          => 'TempatLahir',
        'tgl_lahir'             => 'TglLahir',
        'sex'                   => 'Sex',
        'agama'                 => 'Agama',
        'tinggi_cm'             => 'TinggiDlmCm',
        'berat_kg'              => 'BeratDlmKg',
        'warga_negara'          => 'WargaNegara',
        'status_nikah'          => 'StatusNikah',
        'tgl_masuk'             => 'TglMasuk',
        'status_tempat_tinggal' => 'StatusTempatTinggal',
        'hobby'                 => 'Hobby',
        'tgl_resigned'          => 'TglResigned',
        'foto_path'             => 'FileFoto',
        'keterangan'            => 'Keterangan',
        'cost_center'           => 'CostCenter',
        'posting'               => 'Posting',
        'aktif'                 => 'Aktif',
        'awal_group_of_employee'=> 'AwalGroupOfEmployee',
        'tgl_entry'             => 'TglEntry',
        'operator'              => 'Operator',
        'create_by'             => 'CreateBy',
        'create_date'           => 'CreateDate',
        'nama_ayah'             => 'NamaAyah',
        'nama_ibu'              => 'NamaIbu',
        'nama_istri'            => 'NamaIstri',
        'pekerjaan_ayah'        => 'PekerjaanAyah',
        'pekerjaan_ibu'         => 'PekerjaanIbu',
        'pekerjaan_istri'       => 'PekerjaanIstri',
        'jumlah_anak'           => 'JumlahAnak',
        'alasan_resigned'       => 'AlasanResigned',
        'cuti_per_tahun'        => 'CutiPerTahun',
        'no_rekening'           => 'NoRekening',
        'nama_bank'             => 'NamaBank',
        'atas_nama_rekening'    => 'AtasNama',
        'gol_darah'             => 'GolDarah',
        'kode_pos'              => 'KodePos',
        'awal_cabang'           => 'AwalCabang',
        'no_passport'           => 'NoPassport',
        'no_visa'               => 'NoVisa',
        'passport_expired'      => 'PassportExpired',
        'tipe'                  => 'Tipe',
        'tgl_kontrak_berakhir'  => 'TglKontrakBerakhir',
        'id'                    => 'ID',
        'password'              => 'Password',
        'email'                 => 'Email',
        'nrp'                   => 'NRP',
        'kategori'              => 'Kategori',
        'sub_kategori'          => 'SubKategori',
        'divisi'                => 'Divisi',
        'jalur_pendaftaran'     => 'JalurPendaftaran',
        'pangkat'               => 'Pangkat',
        'korps'                 => 'Korps',
        'thn_angkatan'          => 'ThnAngkatan',
        'pangkat_of_sub_kategori'=> 'PangkatOfSubKategori',
        'kesatuan'              => 'Kesatuan',
        'grup1'                 => 'Grup1',
        'grup2'                 => 'Grup2',
        'grup3'                 => 'Grup3',
        'lokasi'                => 'Lokasi',
        'kode_lama'             => 'KodeLama',
        'alias_kode'            => 'AliasKode',
        'org'                   => 'Org',
        'organisasi'            => 'Org',
        'tipe_rekening'         => 'Tipe',
        'no_kk'                 => 'NoKK',
        'no_bpjs_kesehatan'     => 'NoBPJSKesehatan',
        'no_bpjs_tenaga_kerja'  => 'NoBPJSTenagaKerja',
        'tgl_gambar'            => 'TglGambar',
        'status_kerja'          => 'StatusKerja',
        'nama_ktp'              => 'NamaKTP',
        'npwp'                  => 'NPWP',
        'nama_npwp'             => 'NamaNPWP',
        'alamat_npwp'           => 'AlamatNPWP',
        'group_of_employee'     => 'GroupOfEmployee',
        'rt'                    => 'RT',
        'rw'                    => 'RW',
        'kelurahan'             => 'Kelurahan',
        'kecamatan'             => 'Kecamatan',
        'no_hp2'                => 'NoHP2',
        'sales'                 => 'Sales',
        'propinsi'              => 'Propinsi',
        'staff1'                => 'Staff1',
        'staff2'                => 'Staff2',
        'mekanik'               => 'Mekanik',
        'foreman'               => 'Foreman',
        'insentif1'             => 'Insentif1',
        'insentif2'             => 'Insentif2',
        'insentif3'             => 'Insentif3',
        'insentif4'             => 'Insentif4',
        'insentif5'             => 'Insentif5',
        'checked'               => 'Checked',
        'checked_by'            => 'CheckedBy',
    ];

    public function getAttribute($key)
    {
        $dbKey = $this->appFieldMap[$key] ?? $key;

        return parent::getAttribute($dbKey);
    }

    public function setAttribute($key, $value)
    {
        $dbKey = $this->appFieldMap[$key] ?? $key;

        return parent::setAttribute($dbKey, $value);
    }

    protected function casts(): array
    {
        return [
            'TglLahir'            => 'date',
            'TglMasuk'            => 'date',
            'TglResigned'         => 'date',
            'PassportExpired'     => 'date',
            'Aktif'               => 'boolean',
            'TglEntry'            => 'datetime',
            'CreateDate'          => 'datetime',
            'TglKontrakBerakhir'  => 'datetime',
            'TglGambar'           => 'datetime',
            'Insentif1'           => 'boolean',
            'Insentif2'           => 'boolean',
            'Insentif3'           => 'boolean',
            'Insentif4'           => 'boolean',
            'Insentif5'           => 'boolean',
            'Checked'             => 'boolean',
        ];
    }

    public function getFotoPathAttribute()
    {
        return (string) $this->getAttribute('FileFoto');
    }

    public function dataKerabats()
    {
        return $this->hasMany(DataKerabat::class, 'Kode', 'Kode');
    }
}
