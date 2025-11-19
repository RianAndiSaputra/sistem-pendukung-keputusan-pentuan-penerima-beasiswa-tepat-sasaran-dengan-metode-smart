@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Hasil Seleksi</h1>
        <p class="text-gray-600">Daftar penerima beasiswa berdasarkan perhitungan SMART</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('hasil.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Seleksi</label>
                <select name="periode_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                <select name="prodi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                    <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                        {{ $prodi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 w-full">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($hasilSeleksi as $hasil)
                    <tr class="{{ $hasil->status ? 'bg-green-50 hover:bg-green-100' : 'hover:bg-gray-50' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                {{ $hasil->ranking <= 10 ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-700' }} text-sm font-bold">
                                {{ $hasil->ranking }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $hasil->mahasiswa->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $hasil->mahasiswa->nim }}</div>
                            <div class="text-xs text-gray-400">IPK: {{ $hasil->mahasiswa->ipk }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $hasil->mahasiswa->prodi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="font-bold text-lg 
                                {{ $hasil->ranking <= 10 ? 'text-green-600' : 'text-gray-600' }}">
                                {{ number_format($hasil->total_skor, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($hasil->status)
                            <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-check mr-1"></i>LOLOS
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                <i class="fas fa-times mr-1"></i>TIDAK LOLOS
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button onclick="showDetail({{ $hasil }})" 
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                <i class="fas fa-chart-bar mr-1"></i>Detail Skor
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $hasilSeleksi->where('status', true)->count() }}</div>
            <div class="text-green-800 font-semibold">Mahasiswa Lolos</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-red-600 mb-2">{{ $hasilSeleksi->where('status', false)->count() }}</div>
            <div class="text-red-800 font-semibold">Mahasiswa Tidak Lolos</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $hasilSeleksi->count() }}</div>
            <div class="text-blue-800 font-semibold">Total Peserta</div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Skor Mahasiswa</h3>
            <div id="modalContent">
                <!-- Content will be filled by JavaScript -->
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeModal()" 
                        class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(hasil) {
    const content = `
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="font-medium">Nama:</span>
                <span>${hasil.mahasiswa.nama}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">NIM:</span>
                <span>${hasil.mahasiswa.nim}</span>
            </div>
            <div class="border-t pt-3">
                <div class="flex justify-between text-sm">
                    <span>Skor IPK:</span>
                    <span class="font-bold">${parseFloat(hasil.skor_ipk).toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor Penghasilan:</span>
                    <span class="font-bold">${parseFloat(hasil.skor_penghasilan).toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor Tanggungan:</span>
                    <span class="font-bold">${parseFloat(hasil.skor_tanggungan).toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor Prestasi:</span>
                    <span class="font-bold">${parseFloat(hasil.skor_prestasi).toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t mt-2 pt-2">
                    <span>Total Skor:</span>
                    <span class="text-blue-600">${parseFloat(hasil.total_skor).toFixed(2)}</span>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
@endpush
@endsection