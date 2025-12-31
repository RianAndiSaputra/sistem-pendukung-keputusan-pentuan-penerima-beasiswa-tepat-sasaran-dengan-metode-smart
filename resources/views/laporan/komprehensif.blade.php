@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Komprehensif</h1>
            <p class="text-gray-600">Laporan semua periode seleksi beasiswa</p>
        </div>
        
        <a href="{{ route('laporan.index') }}" 
           class="flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Filter Rentang Waktu -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Rentang Waktu</h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                    Filter
                </button>
                <a href="{{ route('laporan.komprehensif') }}"
                   class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 text-center transition duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistik Komprehensif -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['totalPeriode'] }}</div>
                    <div class="text-blue-100">Total Periode</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['totalPeserta'] }}</div>
                    <div class="text-green-100">Total Peserta</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['totalLolos'] }}</div>
                    <div class="text-purple-100">Total Lolos</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold">{{ $stats['avgSkorPerPeriode'] }}</div>
                    <div class="text-orange-100">Rata-rata Skor</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Daftar Periode Komprehensif -->
    @if($periodes->count() > 0)
    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Detail Semua Periode ({{ $periodes->count() }})</h2>
            <button onclick="exportKomprehensif()"
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export Semua Data
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lolos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Lolos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Tertinggi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Terendah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($periodes as $periode)
                    @php
                        // Ambil data hasil seleksi untuk periode ini
                        $hasil = \App\Models\HasilSeleksi::where('periode_id', $periode->id)->get();
                        $totalPeserta = $hasil->count();
                        $totalLolos = $hasil->where('status', true)->count();
                        $persentaseLolos = $totalPeserta > 0 ? round(($totalLolos / $totalPeserta) * 100, 2) : 0;
                        $skorTertinggi = $totalPeserta > 0 ? number_format($hasil->max('total_skor'), 2) : '0.00';
                        $skorTerendah = $totalPeserta > 0 ? number_format($hasil->min('total_skor'), 2) : '0.00';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $periode->nama_periode }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                    {{ $totalPeserta }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">
                                    {{ $totalLolos }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $periode->kuota_penerima }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <span class="px-3 py-1 text-sm {{ $persentaseLolos >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                                    {{ $persentaseLolos }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-900">
                            {{ $skorTertinggi }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-900">
                            {{ $skorTerendah }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('laporan.index', ['periode_id' => $periode->id]) }}"
                               class="text-blue-600 hover:text-blue-900 text-sm flex items-center gap-1 transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow p-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data periode</h3>
        <p class="text-gray-600">Tidak ada data periode dalam rentang waktu yang dipilih</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
function exportKomprehensif() {
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    window.open(`/laporan/komprehensif/export?start_date=${startDate}&end_date=${endDate}`, '_blank');
}
</script>
@endpush
@endsection