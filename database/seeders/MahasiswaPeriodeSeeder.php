<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeSeleksi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MahasiswaPeriodeSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Mahasiswa::truncate();
        PeriodeSeleksi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Seed periode
        $this->seedPeriodes();
        
        // Seed mahasiswa
        $this->seedMahasiswas();
    }

    private function seedPeriodes()
    {
        $today = '2025-12-29'; // Tanggal sekarang
        $nextMonth = Carbon::parse($today)->addMonth()->format('Y-m-d'); // 29 Januari 2026
        
        $periodes = [
            [
                'nama_periode' => 'Seleksi Beasiswa Periode Ganjil 2024',
                'tanggal_mulai' => $today, // Mulai hari ini
                'tanggal_berakhir' => $nextMonth, // Sampai sebulan lagi
                'kuota_penerima' => 12,
                'is_active' => true, // AKTIF
            ],
            [
                'nama_periode' => 'Seleksi Beasiswa Periode Genap 2024',
                'tanggal_mulai' => $today, // Mulai hari ini
                'tanggal_berakhir' => $nextMonth, // Sampai sebulan lagi
                'kuota_penerima' => 15,
                'is_active' => true, // AKTIF
            ],
            [
                'nama_periode' => 'Seleksi Beasiswa Periode Ganjil 2025',
                'tanggal_mulai' => $today, // Mulai hari ini
                'tanggal_berakhir' => $nextMonth, // Sampai sebulan lagi
                'kuota_penerima' => 18,
                'is_active' => true, // AKTIF
            ],
        ];

        foreach ($periodes as $periode) {
            PeriodeSeleksi::create($periode);
        }
    }

    private function seedMahasiswas()
    {
        $prodis = [
            'Agroteknologi',
            'Industri Peternakan', 
            'Teknologi Hasil Pertanian',
            'Magister Ilmu Pangan',
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen',
            'Akuntansi',
            'Ilmu Komunikasi dan Multimedia',
            'Psikologi',
            'Magister Psikologi Sains',
        ];

        $namaMahasiswas = [
            'Ahmad Rizki', 'Budi Santoso', 'Cahyo Pratama', 'Dedi Setiawan', 'Eko Prasetyo',
            'Fajar Nugroho', 'Gunawan Surya', 'Hadi Wijaya', 'Indra Kusuma', 'Joko Susilo',
            'Kurniawan Adi', 'Lukman Hakim', 'Muhammad Arif', 'Nurhadi Putra', 'Oki Ramadhan',
            'Puji Santoso', 'Rizki Maulana', 'Surya Dharma', 'Teguh Wijaya', 'Umar Faruq',
            'Amelia Putri', 'Bunga Lestari', 'Citra Dewi', 'Dewi Sartika', 'Eva Rahmawati',
            'Fitriani Sari', 'Gita Maharani', 'Hana Septiani', 'Intan Permata', 'Juliastuti',
            'Kartika Sari', 'Lestari Wulandari', 'Maya Indah', 'Nadia Ayu', 'Olivia Putri',
            'Putri Anggraini', 'Queena Zahra', 'Rani Permatasari', 'Sari Dewi', 'Tina Marlina',
            'Umi Kulsum', 'Vina Anggraeni', 'Wulan Sari', 'Yuni Astuti', 'Zahra Fitriani',
            'Anisa Rahma', 'Bella Citra', 'Cindy Amelia', 'Diana Puspita', 'Elsa Natalia',
        ];

        $mahasiswas = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $nim = '2022' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $nama = $namaMahasiswas[array_rand($namaMahasiswas)];
            $prodi = $prodis[array_rand($prodis)];
            
            $semester = rand(3, 8);
            $ipk = round(rand(250, 380) / 100, 2);
            $penghasilan = rand(1000000, 8000000);
            $jumlahTanggungan = rand(1, 5);
            $prestasi = rand(1, 5);
            
            $rand = rand(1, 100);
            if ($rand <= 33) {
                $periodeId = 1; // Periode 2024
            } elseif ($rand <= 66) {
                $periodeId = 2; // Periode 2024 Genap
            } else {
                $periodeId = 3; // Periode 2025
            }

            $hasKhs = rand(0, 1);
            $hasPenghasilan = rand(0, 1);
            $hasSertifikat = rand(0, 1);

            $mahasiswas[] = [
                'nim' => $nim,
                'nama' => $nama,
                'prodi' => $prodi,
                'semester' => $semester,
                'ipk' => $ipk,
                'penghasilan_ortu' => $penghasilan,
                'jumlah_tanggungan' => $jumlahTanggungan,
                'prestasi' => $prestasi,
                'periode_id' => $periodeId,
                'khs_file' => $hasKhs ? 'khs/dummy_khs.pdf' : null,
                'penghasilan_file' => $hasPenghasilan ? 'penghasilan/dummy_slip.pdf' : null,
                'sertifikat_file' => $hasSertifikat ? 'sertifikat/dummy_sertifikat.pdf' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($mahasiswas, 10) as $chunk) {
            Mahasiswa::insert($chunk);
        }
    }
}