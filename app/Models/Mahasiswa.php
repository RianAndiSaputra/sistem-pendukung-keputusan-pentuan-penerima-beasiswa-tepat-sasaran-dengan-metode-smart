<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'semester',
        'ipk',
        'penghasilan_ortu',
        'jumlah_tanggungan',
        'prestasi',
        'khs_file',
        'penghasilan_file',
        'sertifikat_file',
        'periode_id'
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeSeleksi::class, 'periode_id');
    }

    public function hasilSeleksi()
    {
        return $this->hasOne(HasilSeleksi::class);
    }
}