<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('periode');

        // Filter prodi
        if ($request->has('prodi') && $request->prodi != '') {
            $query->where('prodi', $request->prodi);
        }

        // Filter periode
        if ($request->has('periode_id') && $request->periode_id != '') {
            $query->where('periode_id', $request->periode_id);
        }

        // Search nama atau NIM
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $query->latest()->paginate(10);
        $periodes = PeriodeSeleksi::where('is_active', true)->get();
        
        // Get unique prodi for filter dropdown
        $prodis = Mahasiswa::select('prodi')->distinct()->get()->pluck('prodi');

        return view('mahasiswa.index', compact('mahasiswas', 'periodes', 'prodis'));
    }

    public function create()
    {
        $periodes = PeriodeSeleksi::where('is_active', true)->get();
        return view('mahasiswa.create', compact('periodes'));
    }

    public function store(Request $request)
    {
        // Format penghasilan - hapus pemisah ribuan
        $penghasilan = str_replace('.', '', $request->penghasilan_ortu);
        
        $request->merge([
            'penghasilan_ortu' => $penghasilan
        ]);

        $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama' => 'required',
            'prodi' => 'required',
            'semester' => 'required|integer|min:1|max:14',
            'ipk' => 'required|numeric|between:0,4',
            'penghasilan_ortu' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'prestasi' => 'required|integer|between:1,5',
            'periode_id' => 'required|exists:periode_seleksis,id',
            'khs_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'penghasilan_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

    public function show(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('periode')->findOrFail($id);
        
        // Untuk AJAX request dari modal detail
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        }
        
        // Fallback: redirect ke index jika diakses langsung via URL
        return redirect()->route('mahasiswa.index');
    }

    public function edit(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Untuk AJAX request dari modal edit
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        }
        
        // Fallback: redirect ke index jika diakses langsung via URL
        return redirect()->route('mahasiswa.index');
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Format penghasilan - hapus pemisah ribuan
        $penghasilan = str_replace('.', '', $request->penghasilan_ortu);
        
        $request->merge([
            'penghasilan_ortu' => $penghasilan
        ]);

        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required',
            'prodi' => 'required',
            'semester' => 'required|integer|min:1|max:14',
            'ipk' => 'required|numeric|between:0,4',
            'penghasilan_ortu' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'prestasi' => 'required|integer|between:1,5',
            'periode_id' => 'required|exists:periode_seleksis,id',
            'khs_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'penghasilan_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

    public function destroy(Request $request, $id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswaName = $mahasiswa->nama;
            
            // Delete files
            if ($mahasiswa->khs_file) Storage::disk('public')->delete($mahasiswa->khs_file);
            if ($mahasiswa->penghasilan_file) Storage::disk('public')->delete($mahasiswa->penghasilan_file);
            if ($mahasiswa->sertifikat_file) Storage::disk('public')->delete($mahasiswa->sertifikat_file);

            $mahasiswa->delete();

            // Untuk AJAX request dari modal delete
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data mahasiswa ' . $mahasiswaName . ' berhasil dihapus.'
                ]);
            }

            // Untuk regular form submission
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa ' . $mahasiswaName . ' berhasil dihapus.');

        } catch (\Exception $e) {
            // Untuk AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            // Untuk regular request
            return redirect()->route('mahasiswa.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}