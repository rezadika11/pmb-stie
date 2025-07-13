<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';
    protected $guarded = ['id'];

    public function gelombang()
    {
        return $this->hasMany(Gelombang::class);
    }
}
