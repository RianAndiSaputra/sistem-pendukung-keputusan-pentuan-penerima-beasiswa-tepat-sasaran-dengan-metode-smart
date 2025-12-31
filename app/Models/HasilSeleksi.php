<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSeleksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'periode_id', // TAMBAHKAN INI
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

    // TAMBAHKAN RELASI KE PERIODE
    public function periode()
    {
        return $this->belongsTo(PeriodeSeleksi::class);
    }

    // Scope untuk filter mudah
    public function scopePeriode($query, $periodeId)
    {
        return $query->where('periode_id', $periodeId);
    }

    // Scope untuk yang lolos
    public function scopeLolos($query)
    {
        return $query->where('status', true);
    }
}