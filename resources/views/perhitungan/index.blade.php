@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Perhitungan SMART</h1>
            <p class="text-gray-600">Proses perhitungan menggunakan metode SMART</p>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-blue-600">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sudah Dihitung</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalSudahDihitung }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock text-yellow-600">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Belum Dihitung</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalBelumDihitung }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar text-purple-600">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                </div>
                <div class="ml-4 min-w-0"> <!-- Tambah min-w-0 untuk truncate -->
                    <p class="text-sm font-medium text-gray-600 truncate">Periode Dipilih</p>
                    <p class="text-lg font-bold text-gray-800 truncate" title="{{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada' }}">
                        {{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada' }}
                    </p>
                    @if($periodeAktif)
                    <p class="text-xs text-gray-500 mt-1">
                        Kuota: {{ $periodeAktif->kuota_penerima }} | 
                        {{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($periodeAktif->tanggal_berakhir)->format('d M Y') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Data</h3>
        <form method="GET" action="{{ route('perhitungan.index') }}">
            <!-- Tambah input hidden untuk periode_id -->
            <input type="hidden" name="periode_id" value="{{ $selectedPeriodeId }}">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Periode Seleksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                    <select name="periode_select" id="periodeSelect" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Pilih Periode...</option>
                        @foreach($allPeriodes as $periode)
                        <option value="{{ $periode->id }}" 
                                {{ $selectedPeriodeId == $periode->id ? 'selected' : '' }}
                                data-active="{{ $periode->is_active }}">
                            {{ $periode->nama_periode }} 
                            @if($periode->is_active) (Aktif) @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Program Studi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <select name="prodi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                        <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                            {{ $prodi }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Perhitungan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Perhitungan</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Dihitung</option>
                        <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dihitung</option>
                    </select>
                </div>
                
                <!-- Cari Nama/NIM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama/NIM</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari nama atau NIM..." 
                               value="{{ request('search') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-2.5 text-gray-400">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="flex items-end space-x-2 mt-4">
                <button type="submit" 
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    Terapkan Filter
                </button>
                @if(request()->anyFilled(['periode_id', 'prodi', 'status', 'search']))
                <a href="{{ route('perhitungan.index') }}" 
                   class="w-full md:w-auto bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw mr-2">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                        <path d="M16 16h5v5"/>
                    </svg>
                    Reset Filter
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Action Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Proses Perhitungan SMART</h3>
                <p class="text-gray-600">
                    @if($totalMahasiswa > 0)
                    <span class="font-bold text-blue-600">{{ $totalMahasiswa }} mahasiswa</span> ditemukan di periode 
                    <span class="font-bold text-purple-600">"{{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada' }}"</span>.
                    @if($totalSudahDihitung > 0)
                    <span class="text-green-600"> {{ $totalSudahDihitung }} sudah dihitung,</span>
                    @endif
                    @if($totalBelumDihitung > 0)
                    <span class="text-yellow-600"> {{ $totalBelumDihitung }} belum dihitung.</span>
                    @endif
                    @else
                    <span class="text-red-600">Tidak ada data mahasiswa untuk periode yang dipilih.</span>
                    @endif
                </p>
            </div>
            
            @if($totalMahasiswa > 0 && $periodeAktif)
            <form action="{{ route('perhitungan.proses') }}" method="POST" class="flex flex-col md:flex-row items-start md:items-center gap-4">
                @csrf
                <!-- Hidden input untuk periode -->
                <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">
                
                <div class="flex flex-col">
                    <p class="text-sm font-medium text-gray-700 mb-1">Periode yang akan diproses:</p>
                    <p class="text-lg font-bold text-purple-600">{{ $periodeAktif->nama_periode }}</p>
                    <p class="text-xs text-gray-500">Kuota: {{ $periodeAktif->kuota_penerima }} penerima</p>
                </div>
                
                <div class="flex flex-col md:flex-row gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calculator mr-2">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M8 7h8"/>
                            <path d="M8 11h8"/>
                            <path d="M8 15h2"/>
                            <path d="M14 15h2"/>
                        </svg>
                        Proses Semua Data
                    </button>
                    
                    @if($totalBelumDihitung > 0)
                    <button type="submit" name="hanya_belum" value="1"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play mr-2">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        Proses Belum Saja
                    </button>
                    @endif
                </div>
            </form>
            @else
            <div class="flex gap-2">
                @if($periodeAktif)
                <a href="{{ route('mahasiswa.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                        <path d="M5 12h14"/>
                        <path d="M12 5v14"/>
                    </svg>
                    Tambah Mahasiswa
                </a>
                @endif
                <a href="{{ route('perhitungan.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw mr-2">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                        <path d="M16 16h5v5"/>
                    </svg>
                    Reset Filter
                </a>
            </div>
            @endif
        </div>

        @if($totalBelumDihitung > 0 && $periodeAktif)
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle text-yellow-600 mr-3">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                </svg>
                <div>
                    <p class="text-yellow-800 font-medium">Ada {{ $totalBelumDihitung }} data baru yang belum dihitung</p>
                    <p class="text-yellow-700 text-sm">Klik "Proses Belum Saja" untuk menghitung hanya data yang belum terhitung di periode ini.</p>
                </div>
            </div>
        </div>
        @endif

        @if($totalSudahDihitung > 0 && $totalSudahDihitung == $totalMahasiswa && $periodeAktif)
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600 mr-3">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div>
                    <p class="text-green-800 font-medium">Semua data di periode ini sudah dihitung!</p>
                    <p class="text-green-700 text-sm">Lihat hasil seleksi di halaman <a href="{{ route('hasil.index') }}" class="font-bold underline">Hasil Seleksi</a>.</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600 mr-3">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle text-red-600 mr-3">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" x2="12" y1="8" y2="12"/>
                    <line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <div>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Data Mahasiswa Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold text-gray-800 truncate">Data Mahasiswa</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Periode: 
                        <span class="font-semibold text-purple-600">
                            {{ $periodeAktif ? $periodeAktif->nama_periode : 'Tidak ada periode dipilih' }}
                        </span>
                        | Total: <span class="font-bold">{{ $totalMahasiswa }}</span> data
                        @if($periodeAktif)
                        | Kuota: <span class="font-bold">{{ $periodeAktif->kuota_penerima }}</span> penerima
                        @endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full flex items-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-1">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        {{ $totalSudahDihitung }} Terhitung
                    </span>
                    <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full flex items-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $totalBelumDihitung }} Belum
                    </span>
                </div>
            </div>
        </div>
        
        @if($mahasiswas->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghasilan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mahasiswas as $index => $mahasiswa)
                    <tr class="hover:bg-gray-50 {{ is_null($mahasiswa->hasilSeleksi) ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-900">{{ $mahasiswa->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 truncate max-w-xs">{{ $mahasiswa->nama }}</div>
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
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                {{ $mahasiswa->jumlah_tanggungan }} org
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                Level {{ $mahasiswa->prestasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($mahasiswa->hasilSeleksi)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full flex items-center justify-center w-24 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check mr-1">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Terhitung
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full flex items-center justify-center w-24 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                Belum
                            </span>
                            @endif
                        </td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 justify-center">
                                @if(is_null($mahasiswa->hasilSeleksi) && $periodeAktif)
                                <form action="{{ route('perhitungan.proses') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                                    <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-200"
                                            title="Hitung data ini">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play">
                                            <polygon points="5 3 19 12 5 21 5 3"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                
                                <a href="{{ route('mahasiswa.show', $mahasiswa->id) }}" 
                                   class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-200"
                                   title="Lihat detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                
                                @if($mahasiswa->hasilSeleksi)
                                <a href="{{ route('hasil.index') }}?periode_id={{ $selectedPeriodeId }}&search={{ $mahasiswa->nim }}" 
                                   class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition duration-200"
                                   title="Lihat hasil">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart">
                                        <line x1="12" x2="12" y1="20" y2="10"/>
                                        <line x1="18" x2="18" y1="20" y2="4"/>
                                        <line x1="6" x2="6" y1="20" y2="16"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($mahasiswas->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $mahasiswas->withQueryString()->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-gray-300 mx-auto mb-4">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data mahasiswa</h3>
            <p class="text-gray-500 mb-6">
                @if(request()->anyFilled(['prodi', 'status', 'search']) || $selectedPeriodeId)
                Tidak ada data yang sesuai dengan filter yang dipilih untuk periode ini.
                @else
                Belum ada data mahasiswa atau belum memilih periode.
                @endif
            </p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('mahasiswa.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                        <path d="M5 12h14"/>
                        <path d="M12 5v14"/>
                    </svg>
                    Tambah Mahasiswa
                </a>
                @if(request()->anyFilled(['prodi', 'status', 'search']))
                <a href="{{ route('perhitungan.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw mr-2">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                        <path d="M16 16h5v5"/>
                    </svg>
                    Reset Filter
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // JavaScript untuk mengubah periode secara otomatis
    document.addEventListener('DOMContentLoaded', function() {
        const periodeSelect = document.getElementById('periodeSelect');
        const hiddenPeriodeInput = document.querySelector('input[name="periode_id"]');
        
        if (periodeSelect) {
            periodeSelect.addEventListener('change', function() {
                const selectedPeriodeId = this.value;
                
                if (selectedPeriodeId) {
                    // Update hidden input
                    if (hiddenPeriodeInput) {
                        hiddenPeriodeInput.value = selectedPeriodeId;
                    }
                    
                    // Submit form secara otomatis untuk memuat data baru
                    const form = this.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            });
        }
        
        @if(session('processing') || request()->has('processing'))
        // Tampilkan loading indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingDiv.innerHTML = `
            <div class="bg-white rounded-lg p-6 shadow-xl text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Memproses Data</h3>
                <p class="text-gray-600">Mohon tunggu, sedang menghitung data mahasiswa...</p>
            </div>
        `;
        document.body.appendChild(loadingDiv);
        
        // Auto-refresh setelah 5 detik
        setTimeout(() => {
            window.location.href = '{{ route("perhitungan.index", ["periode_id" => $selectedPeriodeId]) }}';
        }, 5000);
        @endif
    });
</script>
@endpush

@endsection