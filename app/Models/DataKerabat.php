<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKerabat extends Model
{
    protected $guarded = ['id'];

    public function calonKaryawan()
    {
        return $this->belongsTo(CalonKaryawan::class);
    }
}
