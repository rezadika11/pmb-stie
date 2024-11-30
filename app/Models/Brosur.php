<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brosur extends Model
{
    protected $table = 'brosur';
    protected $fillable = ['name', 'slug', 'content', 'filename', 'path'];
}
