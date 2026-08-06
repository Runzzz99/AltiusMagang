<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataKerabat extends Model
{
    // Tabel relasi karyawan pada skema SQL Server lama.
    protected $table = 'EmployeeRelationship';

    protected $primaryKey = 'Kode';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected $appFieldMap = [
        'kode'          => 'Kode',
        'kode_relasi'   => 'KodeRelasi',
        'tipe_relasi'   => 'TipeRelasi',
        'operator'      => 'Operator',
        'tgl_entry'     => 'TglEntry',
        'create_by'     => 'CreateBy',
        'create_date'   => 'CreateDate',
    ];

    public function getAttribute($key)
    {
        if ($key === 'nama') {
            return $this->relatedEmployee()?->Nama;
        }

        if ($key === 'hubungan') {
            return $this->relationshipName();
        }

        if ($key === 'no_telp') {
            return $this->relatedEmployee()?->NoHP ?? $this->relatedEmployee()?->Telp;
        }

        if ($key === 'pekerjaan') {
            return null;
        }

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
            'TglEntry'   => 'datetime',
            'CreateDate' => 'datetime',
        ];
    }

    public function calonKaryawan()
    {
        return $this->belongsTo(CalonKaryawan::class, 'Kode', 'Kode');
    }

    private function relatedEmployee(): ?object
    {
        static $employees = [];

        $kodeRelasi = parent::getAttribute('KodeRelasi');
        if (!$kodeRelasi) {
            return null;
        }

        return $employees[$kodeRelasi] ??= DB::table('Employee')->where('Kode', $kodeRelasi)->first();
    }

    private function relationshipName(): ?string
    {
        static $types = [];

        $tipeRelasi = parent::getAttribute('TipeRelasi');
        if (!$tipeRelasi) {
            return null;
        }

        return $types[$tipeRelasi] ??= DB::table('EmployeeRelationshipType')->where('Kode', $tipeRelasi)->value('Keterangan');
    }
}
