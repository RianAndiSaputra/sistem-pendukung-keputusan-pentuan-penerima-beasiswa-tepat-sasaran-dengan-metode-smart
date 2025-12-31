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
        'is_active',
        'kuota_penerima'
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'periode_id');
    }

    // Di model PeriodeSeleksi, tambahkan:
    public function hasilSeleksi()
    {
        return $this->hasMany(HasilSeleksi::class, 'periode_id');
    }

    // PERBAIKAN: Tambahkan foreign key 'periode_id' juga di sini
    public function mahasiswasYangLolos()
    {
        return $this->hasMany(HasilSeleksi::class, 'periode_id')->where('status', true);
    }
}