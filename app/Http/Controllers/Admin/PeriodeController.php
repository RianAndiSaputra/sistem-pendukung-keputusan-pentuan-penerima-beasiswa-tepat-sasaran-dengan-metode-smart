<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
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
}