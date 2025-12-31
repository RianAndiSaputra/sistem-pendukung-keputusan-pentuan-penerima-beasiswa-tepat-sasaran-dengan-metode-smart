<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    // Halaman utama laporan
    public function index()
    {
        $periodes = PeriodeSeleksi::withCount(['mahasiswas'])->latest()->get();
        
        // Tambahkan count untuk yang lolos di setiap periode
        foreach ($periodes as $periode) {
            $periode->hasil_lolos_count = HasilSeleksi::where('periode_id', $periode->id)
                ->where('status', true)
                ->count();
        }
        
        return view('laporan.index', compact('periodes'));
    }

    // Preview laporan via AJAX
    public function preview(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id'
        ]);

        $periode = PeriodeSeleksi::find($request->periode_id);
        
        // Ambil hasil seleksi dengan semua data
        $hasilSeleksi = HasilSeleksi::with(['mahasiswa', 'periode'])
            ->where('periode_id', $request->periode_id)
            ->orderBy('ranking')
            ->get();

        // Hitung statistik detail
        $statistics = [
            'totalPeserta' => $hasilSeleksi->count(),
            'totalLolos' => $hasilSeleksi->where('status', true)->count(),
            'rataSkor' => $hasilSeleksi->avg('total_skor') ? number_format($hasilSeleksi->avg('total_skor'), 2) : '0.00',
            'skorTertinggi' => $hasilSeleksi->max('total_skor') ? number_format($hasilSeleksi->max('total_skor'), 2) : '0.00',
            'skorTerendah' => $hasilSeleksi->min('total_skor') ? number_format($hasilSeleksi->min('total_skor'), 2) : '0.00',
            'kuota' => $periode->kuota_penerima,
        ];

        // Statistik per prodi
        $prodiStats = [];
        foreach ($hasilSeleksi->groupBy('mahasiswa.prodi') as $prodi => $items) {
            $prodiStats[] = [
                'prodi' => $prodi,
                'total' => $items->count(),
                'lolos' => $items->where('status', true)->count(),
                'persentase' => $items->count() > 0 ? 
                    round(($items->where('status', true)->count() / $items->count()) * 100, 2) : 0
            ];
        }

        // Detail skor rata-rata
        $avgScores = [
            'ipk' => number_format($hasilSeleksi->avg('skor_ipk'), 2),
            'penghasilan' => number_format($hasilSeleksi->avg('skor_penghasilan'), 2),
            'tanggungan' => number_format($hasilSeleksi->avg('skor_tanggungan'), 2),
            'prestasi' => number_format($hasilSeleksi->avg('skor_prestasi'), 2),
        ];

        return response()->json([
            'hasilSeleksi' => $hasilSeleksi,
            'periode' => $periode,
            'statistics' => $statistics,
            'prodiStats' => $prodiStats,
            'avgScores' => $avgScores,
        ]);
    }

    // Cetak laporan (PDF/Excel)
    public function cetak(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id',
            'type' => 'required|in:pdf,excel'
        ]);

        $periode = PeriodeSeleksi::find($request->periode_id);
        $hasilSeleksi = HasilSeleksi::with(['mahasiswa', 'periode'])
            ->where('periode_id', $request->periode_id)
            ->orderBy('ranking')
            ->get();

        // Hitung statistik untuk laporan
        $statistics = [
            'totalPeserta' => $hasilSeleksi->count(),
            'totalLolos' => $hasilSeleksi->where('status', true)->count(),
            'rataSkor' => number_format($hasilSeleksi->avg('total_skor'), 2),
            'kuota' => $periode->kuota_penerima,
        ];

        if ($request->type === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf', [
                'hasilSeleksi' => $hasilSeleksi,
                'periode' => $periode,
                'statistics' => $statistics,
                'tanggalCetak' => now()->format('d F Y H:i:s'),
            ]);
            
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download("laporan-seleksi-{$periode->nama_periode}.pdf");
        }

        // Jika belum install Laravel Excel, redirect ke PDF
        return redirect()->back()->with('error', 'Fitur export Excel sedang dalam pengembangan. Gunakan PDF untuk saat ini.');
    }

    // Laporan komprehensif (semua periode)
    public function laporanKomprehensif(Request $request)
    {
        $startDate = $request->get('start_date', now()->subYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        // Ambil semua periode dalam rentang waktu
        $periodes = PeriodeSeleksi::withCount(['mahasiswas'])
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Hitung statistik komprehensif
        $stats = [
            'totalPeriode' => $periodes->count(),
            'totalPeserta' => 0,
            'totalLolos' => 0,
            'avgSkorPerPeriode' => 0,
        ];

        // Tambahkan data detail untuk setiap periode
        foreach ($periodes as $periode) {
            $hasil = HasilSeleksi::where('periode_id', $periode->id)->get();
            $periode->total_peserta = $hasil->count();
            $periode->total_lolos = $hasil->where('status', true)->count();
            $periode->skor_tertinggi = $hasil->max('total_skor') ? number_format($hasil->max('total_skor'), 2) : '0.00';
            $periode->skor_terendah = $hasil->min('total_skor') ? number_format($hasil->min('total_skor'), 2) : '0.00';
            $periode->persentase_lolos = $hasil->count() > 0 ? 
                round(($hasil->where('status', true)->count() / $hasil->count()) * 100, 2) : 0;
            
            // Tambahkan ke total stats
            $stats['totalPeserta'] += $periode->total_peserta;
            $stats['totalLolos'] += $periode->total_lolos;
        }

        // Hitung rata-rata skor
        if ($stats['totalPeriode'] > 0) {
            $totalSkor = 0;
            $periodesWithData = 0;
            
            foreach ($periodes as $periode) {
                $avgSkor = HasilSeleksi::where('periode_id', $periode->id)->avg('total_skor');
                if ($avgSkor) {
                    $totalSkor += $avgSkor;
                    $periodesWithData++;
                }
            }
            
            $stats['avgSkorPerPeriode'] = $periodesWithData > 0 ? 
                number_format($totalSkor / $periodesWithData, 2) : '0.00';
        }

        return view('laporan.komprehensif', compact('periodes', 'stats', 'startDate', 'endDate'));
    }

    // Export laporan komprehensif
    public function exportKomprehensif(Request $request)
    {
        $startDate = $request->get('start_date', now()->subYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $periodes = PeriodeSeleksi::whereBetween('tanggal_mulai', [$startDate, $endDate])->get();

        $pdf = PDF::loadView('laporan.komprehensif-pdf', [
            'periodes' => $periodes,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tanggalCetak' => now()->format('d F Y H:i:s'),
        ]);
        
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("laporan-komprehensif-{$startDate}-to-{$endDate}.pdf");
    }
}