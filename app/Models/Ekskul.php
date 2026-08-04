<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    protected $table = 'ekskuls';
    protected $primaryKey = 'id_ekskul';
    
    protected $fillable = [
        'nama_ekskul',
        'kategori',
        'deskripsi'
    ];
}