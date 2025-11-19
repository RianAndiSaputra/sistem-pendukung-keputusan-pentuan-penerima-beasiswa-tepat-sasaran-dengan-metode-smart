<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('periode')->latest()->get();
        $periodes = PeriodeSeleksi::where('is_active', true)->get();
        return view('mahasiswa.index', compact('mahasiswas', 'periodes'));
    }

    public function create()
    {
        $periodes = PeriodeSeleksi::where('is_active', true)->get();
        return view('mahasiswa.create', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama' => 'required',
            'prodi' => 'required',
            'semester' => 'required|integer',
            'ipk' => 'required|numeric|between:0,4',
            'penghasilan_ortu' => 'required|numeric',
            'jumlah_tanggungan' => 'required|integer',
            'prestasi' => 'required|integer|between:1,5',
            'periode_id' => 'required|exists:periode_seleksis,id',
            'khs_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'penghasilan_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->except(['khs_file', 'penghasilan_file', 'sertifikat_file']);

        // Upload files
        if ($request->hasFile('khs_file')) {
            $data['khs_file'] = $request->file('khs_file')->store('khs', 'public');
        }
        if ($request->hasFile('penghasilan_file')) {
            $data['penghasilan_file'] = $request->file('penghasilan_file')->store('penghasilan', 'public');
        }
        if ($request->hasFile('sertifikat_file')) {
            $data['sertifikat_file'] = $request->file('sertifikat_file')->store('sertifikat', 'public');
        }

        Mahasiswa::create($data);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $periodes = PeriodeSeleksi::where('is_active', true)->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'periodes'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required',
            'prodi' => 'required',
            'semester' => 'required|integer',
            'ipk' => 'required|numeric|between:0,4',
            'penghasilan_ortu' => 'required|numeric',
            'jumlah_tanggungan' => 'required|integer',
            'prestasi' => 'required|integer|between:1,5',
            'periode_id' => 'required|exists:periode_seleksis,id',
            'khs_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'penghasilan_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->except(['khs_file', 'penghasilan_file', 'sertifikat_file']);

        // Upload files
        if ($request->hasFile('khs_file')) {
            if ($mahasiswa->khs_file) Storage::disk('public')->delete($mahasiswa->khs_file);
            $data['khs_file'] = $request->file('khs_file')->store('khs', 'public');
        }
        if ($request->hasFile('penghasilan_file')) {
            if ($mahasiswa->penghasilan_file) Storage::disk('public')->delete($mahasiswa->penghasilan_file);
            $data['penghasilan_file'] = $request->file('penghasilan_file')->store('penghasilan', 'public');
        }
        if ($request->hasFile('sertifikat_file')) {
            if ($mahasiswa->sertifikat_file) Storage::disk('public')->delete($mahasiswa->sertifikat_file);
            $data['sertifikat_file'] = $request->file('sertifikat_file')->store('sertifikat', 'public');
        }

        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        // Delete files
        if ($mahasiswa->khs_file) Storage::disk('public')->delete($mahasiswa->khs_file);
        if ($mahasiswa->penghasilan_file) Storage::disk('public')->delete($mahasiswa->penghasilan_file);
        if ($mahasiswa->sertifikat_file) Storage::disk('public')->delete($mahasiswa->sertifikat_file);

        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}