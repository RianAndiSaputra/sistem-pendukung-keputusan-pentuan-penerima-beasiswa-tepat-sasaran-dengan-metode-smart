<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN: Load mahasiswa dan periode sekaligus
        $query = HasilSeleksi::with(['mahasiswa', 'periode'])->orderBy('ranking');

        // Filter Periode - SEKARANG LEBIH SIMPLE
        if ($request->has('periode_id') && $request->periode_id != '') {
            $query->where('periode_id', $request->periode_id); // LANGSUNG, TIDAK PERLU WHEREHAS
        }

        // Filter Prodi
        if ($request->has('prodi') && $request->prodi != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        // Filter Status (lolos/tidak lolos)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status == 'lolos' ? true : false);
        }

        $hasilSeleksi = $query->get();
        
        // Ambil periode untuk dropdown filter
        $periodes = PeriodeSeleksi::all();
        
        // Ambil periode yang sedang dipilih (untuk ditampilkan di header)
        $selectedPeriode = null;
        if ($request->has('periode_id') && $request->periode_id != '') {
            $selectedPeriode = PeriodeSeleksi::find($request->periode_id);
        }

        // Ambil prodi untuk dropdown filter
        $prodis = HasilSeleksi::with('mahasiswa')
            ->get()
            ->pluck('mahasiswa.prodi')
            ->unique()
            ->filter()
            ->sort();

        // Statistik
        $totalPeserta = $hasilSeleksi->count();
        $totalLolos = $hasilSeleksi->where('status', true)->count();
        $totalTidakLolos = $totalPeserta - $totalLolos;

        return view('hasil.index', compact(
            'hasilSeleksi', 
            'periodes', 
            'prodis', 
            'selectedPeriode',
            'totalPeserta',
            'totalLolos',
            'totalTidakLolos'
        ));
    }

    // Method baru untuk export/cetak laporan
    public function export(Request $request)
    {
        $query = HasilSeleksi::with(['mahasiswa', 'periode'])->orderBy('ranking');

        if ($request->has('periode_id') && $request->periode_id != '') {
            $query->where('periode_id', $request->periode_id);
        }

        $hasilSeleksi = $query->get();
        $periode = PeriodeSeleksi::find($request->periode_id);

        return view('hasil.export', compact('hasilSeleksi', 'periode'));
    }

    // TAMBAHKAN METHOD INI UNTUK DETAIL AJAX
    public function getDetail($id)
    {
        try {
            $hasil = HasilSeleksi::with(['mahasiswa', 'periode'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $hasil->id,
                    'mahasiswa' => [
                        'nama' => $hasil->mahasiswa->nama,
                        'nim' => $hasil->mahasiswa->nim,
                        'prodi' => $hasil->mahasiswa->prodi,
                    ],
                    'periode' => $hasil->periode ? [
                        'id' => $hasil->periode->id,
                        'nama_periode' => $hasil->periode->nama_periode,
                    ] : null,
                    'skor_ipk' => number_format($hasil->skor_ipk, 2),
                    'skor_penghasilan' => number_format($hasil->skor_penghasilan, 2),
                    'skor_tanggungan' => number_format($hasil->skor_tanggungan, 2),
                    'skor_prestasi' => number_format($hasil->skor_prestasi, 2),
                    'total_skor' => number_format($hasil->total_skor, 2),
                    'ranking' => $hasil->ranking,
                    'status' => $hasil->status,
                    'created_at' => $hasil->created_at->format('d M Y H:i'),
                    'updated_at' => $hasil->updated_at->format('d M Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau terjadi kesalahan'
            ], 404);
        }
    }
}