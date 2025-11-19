<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\HasilSeleksi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendaftar = Mahasiswa::count();
        $totalLolos = HasilSeleksi::where('status', true)->count();
        $totalTidakLolos = HasilSeleksi::where('status', false)->count();
        $rataIpk = Mahasiswa::avg('ipk') ?? 0;

        // Data untuk chart
        $rataKriteria = [
            'ipk' => HasilSeleksi::avg('skor_ipk') ?? 0,
            'penghasilan' => HasilSeleksi::avg('skor_penghasilan') ?? 0,
            'tanggungan' => HasilSeleksi::avg('skor_tanggungan') ?? 0,
            'prestasi' => HasilSeleksi::avg('skor_prestasi') ?? 0,
        ];

        // Top 3 mahasiswa
        $topMahasiswa = HasilSeleksi::with('mahasiswa')
            ->orderBy('total_skor', 'DESC')
            ->limit(3)
            ->get();

        return view('dashboard.index', compact(
            'totalPendaftar',
            'totalLolos',
            'totalTidakLolos',
            'rataIpk',
            'rataKriteria',
            'topMahasiswa'
        ));
    }
}