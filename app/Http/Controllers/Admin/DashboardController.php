<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = $request->get('periode_id');
        
        // Get all periodes for dropdown
        $allPeriodes = PeriodeSeleksi::all();
        $periodeAktif = PeriodeSeleksi::where('is_active', true)->first();
        
        // If specific periode selected, use it. Otherwise use active periode
        $selectedPeriode = $periodeId ? PeriodeSeleksi::find($periodeId) : $periodeAktif;

        // Base queries
        $mahasiswaQuery = Mahasiswa::query();
        $hasilSeleksiQuery = HasilSeleksi::with('mahasiswa');

        // Filter by periode if selected
        if ($selectedPeriode) {
            $mahasiswaQuery->where('periode_id', $selectedPeriode->id);
            
            // Perbaikan: Filter hasil seleksi berdasarkan mahasiswa yang memiliki periode_id yang sesuai
            $hasilSeleksiQuery->whereHas('mahasiswa', function($q) use ($selectedPeriode) {
                $q->where('periode_id', $selectedPeriode->id);
            });
        }

        // Calculate statistics
        $totalPendaftar = $mahasiswaQuery->count();
        
        // Perbaikan: Hitung hasil seleksi yang lolos dan tidak lolos dengan benar
        $totalLolos = clone $hasilSeleksiQuery;
        $totalLolos = $totalLolos->where('status', 1)->count();
        
        $totalTidakLolos = clone $hasilSeleksiQuery;
        $totalTidakLolos = $totalTidakLolos->where('status', 0)->count();
        
        $rataIpk = $mahasiswaQuery->avg('ipk') ?? 0;
        
        // Calculate percentages
        $persentaseTidakLolos = $totalPendaftar > 0 ? round(($totalTidakLolos / $totalPendaftar) * 100, 1) : 0;
        
        // Perbaikan: Hitung rata-rata skor kriteria dengan query yang benar
        $rataKriteria = [
            'ipk' => $hasilSeleksiQuery->avg('skor_ipk') ?? 0,
            'penghasilan' => $hasilSeleksiQuery->avg('skor_penghasilan') ?? 0,
            'tanggungan' => $hasilSeleksiQuery->avg('skor_tanggungan') ?? 0,
            'prestasi' => $hasilSeleksiQuery->avg('skor_prestasi') ?? 0,
        ];

        // Perbaikan: Top 3 mahasiswa - pastikan query tidak terpengaruh oleh perubahan sebelumnya
        $topMahasiswaQuery = HasilSeleksi::with('mahasiswa');
        
        if ($selectedPeriode) {
            $topMahasiswaQuery->whereHas('mahasiswa', function($q) use ($selectedPeriode) {
                $q->where('periode_id', $selectedPeriode->id);
            });
        }
        
        $topMahasiswa = $topMahasiswaQuery->orderBy('total_skor', 'DESC')
            ->limit(3)
            ->get();

        // Additional stats
        $totalPeriodes = PeriodeSeleksi::count();
        
        // Perbaikan: Hitung rata-rata skor total
        $rataSkorQuery = HasilSeleksi::query();
        if ($selectedPeriode) {
            $rataSkorQuery->whereHas('mahasiswa', function($q) use ($selectedPeriode) {
                $q->where('periode_id', $selectedPeriode->id);
            });
        }
        $rataSkor = $rataSkorQuery->avg('total_skor') ?? 0;

        return view('dashboard.index', compact(
            'totalPendaftar',
            'totalLolos',
            'totalTidakLolos',
            'rataIpk',
            'rataKriteria',
            'topMahasiswa',
            'allPeriodes',
            'periodeAktif',
            'selectedPeriode',
            'totalPeriodes',
            'rataSkor',
            'persentaseTidakLolos'
        ));
    }
}