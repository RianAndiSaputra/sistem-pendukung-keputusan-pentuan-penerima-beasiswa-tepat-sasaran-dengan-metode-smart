<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function index()
    {
        // Ambil periode aktif
        $periodeAktif = PeriodeSeleksi::where('is_active', true)->first();
        
        // Ambil SEMUA mahasiswa dari periode aktif (baik yang sudah dihitung maupun belum)
        $mahasiswas = Mahasiswa::with(['hasilSeleksi', 'periode'])
            ->when($periodeAktif, function($query) use ($periodeAktif) {
                $query->where('periode_id', $periodeAktif->id);
            })
            ->latest()
            ->get();
            
        $kriterias = Kriteria::all();
        $hasData = HasilSeleksi::count() > 0;
        $totalMahasiswa = $mahasiswas->count();
        
        // Hitung yang sudah dan belum dihitung
        $totalSudahDihitung = $mahasiswas->filter(function($mahasiswa) {
            return !is_null($mahasiswa->hasilSeleksi);
        })->count();
        
        $totalBelumDihitung = $totalMahasiswa - $totalSudahDihitung;

        return view('perhitungan.index', compact(
            'mahasiswas', 
            'kriterias', 
            'hasData',
            'totalMahasiswa',
            'totalSudahDihitung',
            'totalBelumDihitung',
            'periodeAktif'
        ));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id'
        ]);

        $periodeId = $request->periode_id;
        $periode = PeriodeSeleksi::find($periodeId);
        
        // Hapus hasil sebelumnya untuk periode ini
        HasilSeleksi::whereHas('mahasiswa', function($q) use ($periodeId) {
            $q->where('periode_id', $periodeId);
        })->delete();

        // Ambil SEMUA mahasiswa dari periode yang dipilih
        $mahasiswas = Mahasiswa::where('periode_id', $periodeId)->get();
        $kriterias = Kriteria::all()->keyBy('nama');

        if ($mahasiswas->isEmpty()) {
            return redirect()->route('perhitungan.index')->with('error', 'Tidak ada data mahasiswa untuk periode yang dipilih.');
        }

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

        // Tentukan status berdasarkan kuota periode
        $kuota = $periode->kuota_penerima;
        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;
            $result['status'] = ($index + 1) <= $kuota; // Hanya yang rankingnya <= kuota yang lolos
        }

        // Simpan ke database
        HasilSeleksi::insert($results);

        $totalLolos = collect($results)->where('status', true)->count();

        return redirect()->route('perhitungan.index')->with('success', 
            'Perhitungan SMART berhasil diproses! ' . 
            count($results) . ' mahasiswa telah dihitung. ' .
            $totalLolos . ' mahasiswa lolos (Kuota: ' . $kuota . '). ' .
            'Periode: ' . $periode->nama_periode
        );
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