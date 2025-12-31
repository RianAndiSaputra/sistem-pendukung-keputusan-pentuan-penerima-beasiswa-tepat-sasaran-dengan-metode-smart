<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\HasilSeleksi;
use App\Models\PeriodeSeleksi;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua periode untuk dropdown
        $allPeriodes = PeriodeSeleksi::orderBy('is_active', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
            
        // Ambil periode yang dipilih dari request atau ambil yang aktif
        $selectedPeriodeId = $request->periode_id;
        
        // Jika tidak ada periode yang dipilih, ambil yang aktif pertama
        if (!$selectedPeriodeId) {
            $periodeAktif = PeriodeSeleksi::where('is_active', true)->first();
            $selectedPeriodeId = $periodeAktif ? $periodeAktif->id : ($allPeriodes->first() ? $allPeriodes->first()->id : null);
        } else {
            $periodeAktif = PeriodeSeleksi::find($selectedPeriodeId);
        }
        
        // Pastikan $periodeAktif tidak null
        if (!$periodeAktif && $allPeriodes->isNotEmpty()) {
            $periodeAktif = $allPeriodes->first();
            $selectedPeriodeId = $periodeAktif->id;
        }
        
        // Query untuk mendapatkan mahasiswa dengan periode yang dipilih
        $query = Mahasiswa::with(['hasilSeleksi' => function($q) use ($selectedPeriodeId) {
            $q->where('periode_id', $selectedPeriodeId);
        }, 'periode']);
        
        // Filter berdasarkan periode yang dipilih
        if ($selectedPeriodeId) {
            $query->where('periode_id', $selectedPeriodeId);
        } else {
            // Jika tidak ada periode yang dipilih, tidak tampilkan data
            $query->whereRaw('1=0');
        }

        // Filter Program Studi
        if ($request->has('prodi') && $request->prodi != '') {
            $query->where('prodi', $request->prodi);
        }

        // Filter Status Perhitungan
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'sudah') {
                $query->whereHas('hasilSeleksi', function($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            } elseif ($request->status == 'belum') {
                $query->whereDoesntHave('hasilSeleksi', function($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            }
        }

        // Search Nama atau NIM
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Pagination dengan query string untuk maintain filter
        $mahasiswas = $query->latest()->paginate(10)->withQueryString();
        
        // Hitung total untuk statistik (dengan periode yang sama)
        $totalQuery = Mahasiswa::query();
        if ($selectedPeriodeId) {
            $totalQuery->where('periode_id', $selectedPeriodeId);
        }
        
        // Apply filter yang sama untuk total
        if ($request->has('prodi') && $request->prodi != '') {
            $totalQuery->where('prodi', $request->prodi);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $totalQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        $totalMahasiswa = $totalQuery->count();
        
        // Hitung yang sudah dihitung dengan filter yang sama
        $sudahDihitungQuery = Mahasiswa::whereHas('hasilSeleksi', function($q) use ($selectedPeriodeId) {
            $q->where('periode_id', $selectedPeriodeId);
        });
            
        if ($selectedPeriodeId) {
            $sudahDihitungQuery->where('periode_id', $selectedPeriodeId);
        }
            
        if ($request->has('prodi') && $request->prodi != '') {
            $sudahDihitungQuery->where('prodi', $request->prodi);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $sudahDihitungQuery->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        $totalSudahDihitung = $sudahDihitungQuery->count();
        $totalBelumDihitung = $totalMahasiswa - $totalSudahDihitung;
        
        // Get unique prodi untuk filter dropdown (dalam periode yang dipilih)
        $prodisQuery = Mahasiswa::query();
        if ($selectedPeriodeId) {
            $prodisQuery->where('periode_id', $selectedPeriodeId);
        }
        
        $prodis = $prodisQuery->select('prodi')->distinct()->orderBy('prodi')->get()->pluck('prodi');

        // Data lainnya
        $kriterias = Kriteria::all();
        $hasData = HasilSeleksi::where('periode_id', $selectedPeriodeId)->count() > 0;

        return view('perhitungan.index', compact(
            'mahasiswas', 
            'kriterias', 
            'hasData',
            'totalMahasiswa',
            'totalSudahDihitung',
            'totalBelumDihitung',
            'periodeAktif',
            'allPeriodes',
            'prodis',
            'selectedPeriodeId'
        ));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|exists:periode_seleksis,id'
        ]);

        $periodeId = $request->periode_id;
        $periode = PeriodeSeleksi::find($periodeId);
        $hanyaBelum = $request->has('hanya_belum');
        $mahasiswaId = $request->mahasiswa_id;
        
        // Jika hanya satu mahasiswa yang diproses (dari tombol aksi)
        if ($mahasiswaId) {
            $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
            
            // Periksa apakah sudah ada hasil untuk mahasiswa ini di periode yang sama
            $existingResult = HasilSeleksi::where('mahasiswa_id', $mahasiswaId)
                ->where('periode_id', $periodeId)
                ->first();
                
            if ($existingResult) {
                return redirect()->route('perhitungan.index', ['periode_id' => $periodeId])
                    ->with('error', 'Data ' . $mahasiswa->nama . ' sudah dihitung sebelumnya di periode ini.');
            }
            
            $this->hitungMahasiswa($mahasiswa, $periode);
            
            // Update ranking setelah menghitung satu mahasiswa
            $this->updateRanking($periodeId);
            
            return redirect()->route('perhitungan.index', ['periode_id' => $periodeId])
                ->with('success', 'Data ' . $mahasiswa->nama . ' berhasil dihitung untuk periode ' . $periode->nama_periode);
        }
        
        // Hapus hasil sebelumnya untuk periode ini (jika bukan hanya yang belum)
        if (!$hanyaBelum) {
            HasilSeleksi::where('periode_id', $periodeId)->delete();
        }

        // Ambil mahasiswa dari periode yang dipilih
        $query = Mahasiswa::where('periode_id', $periodeId);
        
        // Jika hanya menghitung yang belum, filter yang belum punya hasil di periode ini
        if ($hanyaBelum) {
            $query->whereDoesntHave('hasilSeleksi', function($q) use ($periodeId) {
                $q->where('periode_id', $periodeId);
            });
        }
        
        $mahasiswas = $query->get();

        if ($mahasiswas->isEmpty()) {
            return redirect()->route('perhitungan.index', ['periode_id' => $periodeId])
                ->with('error', 'Tidak ada data mahasiswa yang perlu dihitung untuk periode ' . $periode->nama_periode);
        }

        // Proses perhitungan untuk setiap mahasiswa
        $processed = 0;
        foreach ($mahasiswas as $mahasiswa) {
            $this->hitungMahasiswa($mahasiswa, $periode);
            $processed++;
        }

        // Update ranking setelah semua dihitung
        $this->updateRanking($periodeId);

        $totalLolos = HasilSeleksi::where('periode_id', $periodeId)
            ->where('status', true)
            ->count();

        return redirect()->route('perhitungan.index', ['periode_id' => $periodeId])
            ->with('success', 
                'Perhitungan SMART berhasil diproses! ' . 
                $processed . ' mahasiswa telah dihitung. ' .
                $totalLolos . ' mahasiswa lolos (Kuota: ' . $periode->kuota_penerima . '). ' .
                'Periode: ' . $periode->nama_periode
            );
    }
    
    // Method untuk detail mahasiswa (AJAX)
    public function getDetailMahasiswa($id)
    {
        try {
            $mahasiswa = Mahasiswa::with(['hasilSeleksi', 'periode'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $mahasiswa->id,
                    'nama' => $mahasiswa->nama,
                    'nim' => $mahasiswa->nim,
                    'prodi' => $mahasiswa->prodi,
                    'semester' => $mahasiswa->semester,
                    'ipk' => $mahasiswa->ipk,
                    'penghasilan_ortu' => number_format($mahasiswa->penghasilan_ortu, 0, ',', '.'),
                    'jumlah_tanggungan' => $mahasiswa->jumlah_tanggungan,
                    'prestasi' => $mahasiswa->prestasi,
                    'status_dokumen' => $mahasiswa->status_dokumen,
                    'periode' => $mahasiswa->periode ? $mahasiswa->periode->nama_periode : 'Tidak ada',
                    'hasil_seleksi' => $mahasiswa->hasilSeleksi ? [
                        'total_skor' => number_format($mahasiswa->hasilSeleksi->total_skor, 2),
                        'ranking' => $mahasiswa->hasilSeleksi->ranking,
                        'status' => $mahasiswa->hasilSeleksi->status,
                        'created_at' => $mahasiswa->hasilSeleksi->created_at->format('d M Y H:i'),
                    ] : null,
                    'created_at' => $mahasiswa->created_at->format('d M Y'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    private function hitungMahasiswa($mahasiswa, $periode)
    {
        $kriterias = Kriteria::all()->keyBy('nama');

        // Hitung skor untuk setiap kriteria
        $skorIpk = $this->hitungSkorIpk($mahasiswa->ipk);
        $skorPenghasilan = $this->hitungSkorPenghasilan($mahasiswa->penghasilan_ortu);
        $skorTanggungan = $this->hitungSkorTanggungan($mahasiswa->jumlah_tanggungan);
        $skorPrestasi = $mahasiswa->prestasi;

        // Hitung nilai utility (normalisasi * bobot)
        $utilityIpk = $skorIpk * ($kriterias['IPK']->bobot / 100);
        $utilityPenghasilan = $skorPenghasilan * ($kriterias['Penghasilan Orang Tua']->bobot / 100);
        $utilityTanggungan = $skorTanggungan * ($kriterias['Jumlah Tanggungan']->bobot / 100);
        $utilityPrestasi = $skorPrestasi * ($kriterias['Sertifikasi/Prestasi Akademik']->bobot / 100);

        $totalSkor = $utilityIpk + $utilityPenghasilan + $utilityTanggungan + $utilityPrestasi;

        // Simpan ke database
        HasilSeleksi::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'periode_id' => $periode->id
            ],
            [
                'skor_ipk' => $utilityIpk,
                'skor_penghasilan' => $utilityPenghasilan,
                'skor_tanggungan' => $utilityTanggungan,
                'skor_prestasi' => $utilityPrestasi,
                'total_skor' => $totalSkor,
                'ranking' => 0, // Akan di-update nanti
                'status' => false, // Default false, akan di-update berdasarkan ranking
                'catatan' => 'Perhitungan menggunakan metode SMART',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    private function updateRanking($periodeId)
    {
        // Update ranking berdasarkan total skor
        $hasilSeleksi = HasilSeleksi::where('periode_id', $periodeId)
            ->orderBy('total_skor', 'desc')
            ->get();
            
        $periode = PeriodeSeleksi::find($periodeId);
        $kuota = $periode->kuota_penerima;
        $ranking = 1;
        
        foreach ($hasilSeleksi as $hasil) {
            $hasil->ranking = $ranking;
            $hasil->status = ($ranking <= $kuota); // True jika ranking <= kuota
            $hasil->save();
            $ranking++;
        }
    }

    private function hitungSkorIpk($ipk)
    {
        if ($ipk >= 3.80) return 5;
        if ($ipk >= 3.50) return 4;
        if ($ipk >= 3.00) return 3;
        if ($ipk >= 2.50) return 2;
        return 1;
    }

    private function hitungSkorPenghasilan($penghasilan)
    {
        if ($penghasilan <= 1000000) return 5;
        if ($penghasilan <= 2500000) return 4;
        if ($penghasilan <= 5000000) return 3;
        if ($penghasilan <= 7500000) return 2;
        return 1;
    }

    private function hitungSkorTanggungan($tanggungan)
    {
        if ($tanggungan >= 5) return 5;
        if ($tanggungan == 4) return 4;
        if ($tanggungan == 3) return 3;
        if ($tanggungan == 2) return 2;
        return 1;
    }
}