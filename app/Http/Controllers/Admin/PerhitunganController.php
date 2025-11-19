<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\HasilSeleksi;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('hasilSeleksi')->get();
        $kriterias = Kriteria::all();
        $hasData = HasilSeleksi::count() > 0;

        return view('perhitungan.index', compact('mahasiswas', 'kriterias', 'hasData'));
    }

    public function proses(Request $request)
    {
        // Hapus hasil sebelumnya
        HasilSeleksi::truncate();

        $mahasiswas = Mahasiswa::all();
        $kriterias = Kriteria::all()->keyBy('nama');

        $results = [];

        foreach ($mahasiswas as $mahasiswa) {
            // Hitung skor untuk setiap kriteria
            $skorIpk = $this->hitungSkorIpk($mahasiswa->ipk);
            $skorPenghasilan = $this->hitungSkorPenghasilan($mahasiswa->penghasilan_ortu);
            $skorTanggungan = $this->hitungSkorTanggungan($mahasiswa->jumlah_tanggungan);
            $skorPrestasi = $mahasiswa->prestasi;

            // Hitung nilai utility
            $utilityIpk = $skorIpk * ($kriterias['IPK']->bobot / 100);
            $utilityPenghasilan = $skorPenghasilan * ($kriterias['Penghasilan Orang Tua']->bobot / 100);
            $utilityTanggungan = $skorTanggungan * ($kriterias['Jumlah Tanggungan']->bobot / 100);
            $utilityPrestasi = $skorPrestasi * ($kriterias['Sertifikasi/Prestasi Akademik']->bobot / 100);

            $totalSkor = $utilityIpk + $utilityPenghasilan + $utilityTanggungan + $utilityPrestasi;

            $results[] = [
                'mahasiswa_id' => $mahasiswa->id,
                'skor_ipk' => $utilityIpk,
                'skor_penghasilan' => $utilityPenghasilan,
                'skor_tanggungan' => $utilityTanggungan,
                'skor_prestasi' => $utilityPrestasi,
                'total_skor' => $totalSkor,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Urutkan berdasarkan total skor
        usort($results, function($a, $b) {
            return $b['total_skor'] <=> $a['total_skor'];
        });

        // Tambahkan ranking dan status
        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;
            $result['status'] = ($index + 1) <= 10; // Asumsi 10 penerima beasiswa
        }

        // Simpan ke database
        HasilSeleksi::insert($results);

        return redirect()->route('perhitungan.index')->with('success', 'Perhitungan SMART berhasil diproses.');
    }

    private function hitungSkorIpk($ipk)
    {
        if ($ipk >= 3.80) return 5;
        if ($ipk >= 3.50) return 4;
        if ($ipk >= 3.00) return 3;
        if ($ipk >= 2.50) return 2;
        return 1;
    }

    private function hitungSkorPenghasilan($penghasilan)
    {
        if ($penghasilan <= 1000000) return 5;
        if ($penghasilan <= 2500000) return 4;
        if ($penghasilan <= 5000000) return 3;
        if ($penghasilan <= 7500000) return 2;
        return 1;
    }

    private function hitungSkorTanggungan($tanggungan)
    {
        if ($tanggungan >= 5) return 5;
        if ($tanggungan == 4) return 4;
        if ($tanggungan == 3) return 3;
        if ($tanggungan == 2) return 2;
        return 1;
    }
}