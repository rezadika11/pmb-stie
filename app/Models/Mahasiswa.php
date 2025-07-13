<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $guarded = ['id'];
    protected $fillable = ['id_gelombang'];

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'id_gelombang');
    }
}
