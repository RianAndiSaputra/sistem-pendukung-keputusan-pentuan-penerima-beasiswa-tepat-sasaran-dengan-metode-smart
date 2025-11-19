<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeSeleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_berakhir',
        'is_active'
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'periode_id');
    }
}