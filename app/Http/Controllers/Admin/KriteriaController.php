<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $totalBobot = Kriteria::sum('bobot');
        return view('kriteria.index', compact('kriterias', 'totalBobot'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bobot' => 'required|array',
            'bobot.*' => 'required|numeric|min:0',
        ]);

        $totalBobot = array_sum($request->bobot);
        
        if ($totalBobot != 100) {
            return back()->with('error', 'Total bobot harus 100%. Current total: ' . $totalBobot . '%');
        }

        foreach ($request->bobot as $id => $bobot) {
            Kriteria::where('id', $id)->update(['bobot' => $bobot]);
        }

        return redirect()->route('kriteria.index')->with('success', 'Bobot kriteria berhasil diperbarui.');
    }
}