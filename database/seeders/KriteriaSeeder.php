<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $kriterias = [
            ['nama' => 'IPK', 'bobot' => 40.00, 'tipe' => 'benefit'],
            ['nama' => 'Penghasilan Orang Tua', 'bobot' => 30.00, 'tipe' => 'cost'],
            ['nama' => 'Jumlah Tanggungan', 'bobot' => 20.00, 'tipe' => 'benefit'],
            ['nama' => 'Sertifikasi/Prestasi Akademik', 'bobot' => 10.00, 'tipe' => 'benefit'],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}