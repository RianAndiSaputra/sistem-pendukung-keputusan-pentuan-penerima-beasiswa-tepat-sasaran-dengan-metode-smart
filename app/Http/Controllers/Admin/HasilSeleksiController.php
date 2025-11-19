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
        $query = HasilSeleksi::with('mahasiswa')->orderBy('ranking');

        if ($request->has('periode_id') && $request->periode_id != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('periode_id', $request->periode_id);
            });
        }

        if ($request->has('prodi') && $request->prodi != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        $hasilSeleksi = $query->get();
        $periodes = PeriodeSeleksi::all();
        $prodis = HasilSeleksi::with('mahasiswa')
            ->get()
            ->pluck('mahasiswa.prodi')
            ->unique()
            ->filter();

        return view('hasil.index', compact('hasilSeleksi', 'periodes', 'prodis'));
    }
}