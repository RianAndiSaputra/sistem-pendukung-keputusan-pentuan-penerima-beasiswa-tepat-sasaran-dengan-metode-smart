<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    public function index()
    {
        $periodes = PeriodeSeleksi::withCount('mahasiswas')->latest()->get();
        return view('laporan.index', compact('periodes'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id'
        ]);

        $periode = PeriodeSeleksi::find($request->periode_id);
        $hasilSeleksi = HasilSeleksi::with('mahasiswa')
            ->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('periode_id', $request->periode_id);
            })
            ->orderBy('ranking')
            ->get();

        // Calculate statistics
        $statistics = [
            'totalPeserta' => $hasilSeleksi->count(),
            'totalLolos' => $hasilSeleksi->where('status', true)->count(),
            'rataSkor' => $hasilSeleksi->avg('total_skor') ? number_format($hasilSeleksi->avg('total_skor'), 2) : '0.00'
        ];

        return response()->json([
            'hasilSeleksi' => $hasilSeleksi,
            'periode' => $periode,
            'statistics' => $statistics
        ]);
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id',
            'type' => 'required|in:pdf,excel'
        ]);

        $periode = PeriodeSeleksi::find($request->periode_id);
        $hasilSeleksi = HasilSeleksi::with('mahasiswa')
            ->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('periode_id', $request->periode_id);
            })
            ->orderBy('ranking')
            ->get();

        if ($request->type === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf', compact('hasilSeleksi', 'periode'));
            return $pdf->download('laporan-beasiswa-' . $periode->nama_periode . '.pdf');
        }

        // Untuk Excel bisa ditambahkan later
        return redirect()->back()->with('error', 'Fitur export Excel sedang dalam pengembangan.');
    }
}