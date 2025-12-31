<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PeriodeController extends Controller
{
    public function index()
    {
        // Update status periode berdasarkan tanggal
        $this->updatePeriodeStatus();
        
        $periodes = PeriodeSeleksi::withCount('mahasiswas')->latest()->get();
        return view('periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|unique:periode_seleksis',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'kuota_penerima' => 'required|integer|min:1'
        ]);

        // Cek jika periode melewati tanggal berakhir
        if (Carbon::parse($request->tanggal_berakhir)->isPast()) {
            $request->merge(['is_active' => false]);
        }

        // Nonaktifkan periode lain jika yang baru aktif
        if ($request->is_active) {
            PeriodeSeleksi::where('is_active', true)->update(['is_active' => false]);
        }

        PeriodeSeleksi::create($request->all());

        return redirect()->route('periode.index')->with('success', 'Periode seleksi berhasil ditambahkan.');
    }

    public function edit(PeriodeSeleksi $periode)
    {
        return view('periode.edit', compact('periode'));
    }

    public function update(Request $request, PeriodeSeleksi $periode)
    {
        $request->validate([
            'nama_periode' => 'required|unique:periode_seleksis,nama_periode,' . $periode->id,
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'kuota_penerima' => 'required|integer|min:1'
        ]);

        // Cek jika periode melewati tanggal berakhir
        if (Carbon::parse($request->tanggal_berakhir)->isPast()) {
            $request->merge(['is_active' => false]);
        }

        // Nonaktifkan periode lain jika yang ini diaktifkan
        if ($request->is_active) {
            PeriodeSeleksi::where('is_active', true)->where('id', '!=', $periode->id)->update(['is_active' => false]);
        }

        $periode->update($request->all());

        return redirect()->route('periode.index')->with('success', 'Periode seleksi berhasil diperbarui.');
    }

    public function destroy(PeriodeSeleksi $periode)
    {
        // Cek apakah periode memiliki mahasiswa
        if ($periode->mahasiswas()->count() > 0) {
            return redirect()->route('periode.index')->with('error', 'Tidak dapat menghapus periode yang sudah memiliki data mahasiswa.');
        }

        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode seleksi berhasil dihapus.');
    }

    /**
     * Get periode data for modal (AJAX request)
     */
    public function getAjaxData(PeriodeSeleksi $periode): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $periode->id,
                    'nama_periode' => $periode->nama_periode,
                    'tanggal_mulai' => $periode->tanggal_mulai,
                    'tanggal_berakhir' => $periode->tanggal_berakhir,
                    'kuota_penerima' => $periode->kuota_penerima,
                    'is_active' => (bool) $periode->is_active,
                    'mahasiswas_count' => $periode->mahasiswas_count ?? $periode->mahasiswas()->count(),
                    'created_at' => $periode->created_at,
                    'updated_at' => $periode->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data periode'
            ], 500);
        }
    }

    /**
     * Get periode data for modal (for backward compatibility)
     */
    public function getForModal(PeriodeSeleksi $periode): JsonResponse
    {
        return $this->getAjaxData($periode);
    }

    /**
     * Update status periode berdasarkan tanggal
     */
    private function updatePeriodeStatus()
    {
        $today = Carbon::now();
        
        // Nonaktifkan periode yang sudah lewat tanggal berakhir
        PeriodeSeleksi::where('tanggal_berakhir', '<', $today)
            ->where('is_active', true)
            ->update(['is_active' => false]);
            
        // Aktifkan periode yang sedang berjalan (jika belum ada yang aktif)
        $periodeBerjalan = PeriodeSeleksi::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_berakhir', '>=', $today)
            ->where('is_active', false)
            ->first();
            
        if ($periodeBerjalan && !PeriodeSeleksi::where('is_active', true)->exists()) {
            $periodeBerjalan->update(['is_active' => true]);
        }
    }
}