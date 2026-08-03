<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonKaryawan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_masuk' => 'date',
        'tgl_resigned' => 'date',
        'passport_expired' => 'date',
        'aktif' => 'boolean',
    ];

    public function dataKerabats()
    {
        return $this->hasMany(DataKerabat::class);
    }
}
