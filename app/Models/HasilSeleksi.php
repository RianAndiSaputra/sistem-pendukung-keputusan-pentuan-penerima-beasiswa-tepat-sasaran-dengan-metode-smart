<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'skor_ipk',
        'skor_penghasilan',
        'skor_tanggungan',
        'skor_prestasi',
        'total_skor',
        'ranking',
        'status'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}