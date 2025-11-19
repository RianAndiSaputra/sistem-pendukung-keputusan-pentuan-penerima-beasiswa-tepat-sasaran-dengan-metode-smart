@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Perhitungan SMART</h1>
            <p class="text-gray-600">Proses perhitungan menggunakan metode SMART</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Dihitung</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSudahDihitung }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Belum Dihitung</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalBelumDihitung }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Periode Aktif</p>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Proses Perhitungan</h3>
                <p class="text-gray-600">
                    @if($totalMahasiswa > 0)
                    <span class="font-bold text-blue-600">{{ $totalMahasiswa }} mahasiswa</span> siap dihitung.
                    @if($totalSudahDihitung > 0)
                    <span class="text-green-600">{{ $totalSudahDihitung }} sudah dihitung, </span>
                    @endif
                    @if($totalBelumDihitung > 0)
                    <span class="text-yellow-600">{{ $totalBelumDihitung }} belum dihitung.</span>
                    @endif
                    @else
                    <span class="text-red-600">Tidak ada data mahasiswa untuk periode aktif.</span>
                    @endif
                </p>
            </div>
            
            @if($totalMahasiswa > 0)
            <form action="{{ route('perhitungan.proses') }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="periode_id" required class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="{{ $periodeAktif->id }}" selected>{{ $periodeAktif->nama_periode }} (Aktif)</option>
                    @foreach(\App\Models\PeriodeSeleksi::where('id', '!=', $periodeAktif->id)->get() as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                    @endforeach
                </select>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                    <i class="fas fa-calculator mr-2"></i>Proses Semua Data
                </button>
            </form>
            @else
            <a href="{{ route('mahasiswa.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Data Mahasiswa
            </a>
            @endif
        </div>

        @if($totalBelumDihitung > 0)
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                <div>
                    <p class="text-yellow-800 font-medium">Ada {{ $totalBelumDihitung }} data baru yang belum dihitung</p>
                    <p class="text-yellow-700 text-sm">Klik "Proses Semua Data" untuk menghitung semua mahasiswa.</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <div>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Data Mahasiswa -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Data Mahasiswa</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Periode: {{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada periode aktif' }}
                        | Total: {{ $totalMahasiswa }} data
                    </p>
                </div>
                <div class="flex space-x-2">
                    <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                        ✓ {{ $totalSudahDihitung }} Terhitung
                    </span>
                    <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                        ⏳ {{ $totalBelumDihitung }} Belum
                    </span>
                </div>
            </div>
        </div>
        
        @if($mahasiswas->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghasilan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mahasiswas as $index => $mahasiswa)
                    <tr class="hover:bg-gray-50 {{ is_null($mahasiswa->hasilSeleksi) ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $mahasiswa->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $mahasiswa->nama }}</div>
                            <div class="text-sm text-gray-500">Sem {{ $mahasiswa->semester }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->prodi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $mahasiswa->ipk >= 3.5 ? 'bg-green-100 text-green-800' : 
                                   ($mahasiswa->ipk >= 3.0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $mahasiswa->ipk }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($mahasiswa->penghasilan_ortu, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $mahasiswa->jumlah_tanggungan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                Level {{ $mahasiswa->prestasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($mahasiswa->hasilSeleksi)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-check mr-1"></i>Terhitung
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                <i class="fas fa-clock mr-1"></i>Belum
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data mahasiswa</h3>
            <p class="text-gray-500 mb-4">Belum ada data mahasiswa untuk periode aktif.</p>
            <a href="{{ route('mahasiswa.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa
            </a>
        </div>
        @endif
    </div>
</div>
@endsection