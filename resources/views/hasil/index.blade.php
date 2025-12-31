@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header dengan Info Periode -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hasil Seleksi Beasiswa</h1>
            <p class="text-gray-600">Lihat hasil perhitungan dan ranking mahasiswa</p>
            
            @if($selectedPeriode)
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Periode: {{ $selectedPeriode->nama_periode }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                    <div class="text-sm">
                        <span class="text-gray-600">Tanggal:</span>
                        <div class="font-medium">{{ \Carbon\Carbon::parse($selectedPeriode->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($selectedPeriode->tanggal_berakhir)->format('d M Y') }}</div>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-600">Kuota:</span>
                        <div class="font-medium">{{ $selectedPeriode->kuota_penerima }} penerima</div>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-600">Total Peserta:</span>
                        <div class="font-medium">{{ $totalPeserta }} mahasiswa</div>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-600">Yang Lolos:</span>
                        <div class="font-medium text-green-600">{{ $totalLolos }} mahasiswa</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($selectedPeriode)
        <a href="{{ route('hasil.export', ['periode_id' => $selectedPeriode->id]) }}" 
           target="_blank"
           class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Cetak Laporan
        </a>
        @endif
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filter Periode -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Periode</label>
                <select name="periode_id" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}" 
                            {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }} 
                        ({{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('M Y') }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Prodi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <select name="prodi" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Prodi</option>
                    @foreach($prodis as $prodi)
                    <option value="{{ $prodi }}" {{ request('prodi') == $prodi ? 'selected' : '' }}>
                        {{ $prodi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="lolos" {{ request('status') == 'lolos' ? 'selected' : '' }}>Lolos Seleksi</option>
                    <option value="tidak" {{ request('status') == 'tidak' ? 'selected' : '' }}>Tidak Lolos</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    Filter
                </button>
                <a href="{{ route('hasil.index') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-200 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($hasilSeleksi->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Skor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($hasilSeleksi as $hasil)
                    <tr class="hover:bg-gray-50 {{ $hasil->status ? 'bg-green-50' : '' }}">
                        <!-- Ranking -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($hasil->ranking <= ($hasil->periode ? $hasil->periode->kuota_penerima : 0))
                            <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 rounded-full font-bold">
                                #{{ $hasil->ranking }}
                            </span>
                            @else
                            <span class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-800 rounded-full">
                                #{{ $hasil->ranking }}
                            </span>
                            @endif
                        </td>
                        
                        <!-- Nama Mahasiswa -->
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $hasil->mahasiswa->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $hasil->mahasiswa->prodi }}</div>
                        </td>
                        
                        <!-- NIM -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $hasil->mahasiswa->nim }}
                        </td>
                        
                        <!-- Periode -->
                        <td class="px-6 py-4">
                            @if($hasil->periode)
                            <div class="text-sm font-medium text-gray-900">
                                {{ $hasil->periode->nama_periode }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($hasil->periode->tanggal_mulai)->format('d M Y') }}
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        
                        <!-- Total Skor -->
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                            {{ number_format($hasil->total_skor, 2) }}
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($hasil->status)
                            <span class="flex items-center gap-1 px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Lolos
                            </span>
                            @else
                            <span class="flex items-center gap-1 px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                                Tidak Lolos
                            </span>
                            @endif
                        </td>
                        
                        <!-- Detail -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="showDetail({{ $hasil->id }})"
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                    title="Detail Skor">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada hasil seleksi</h3>
            <p class="text-gray-600">
                @if(request('periode_id'))
                    Tidak ada hasil seleksi untuk periode yang dipilih.
                @else
                    Silakan pilih periode untuk melihat hasil seleksi.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Skor -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div id="modalContent">
            <!-- Content akan diisi via JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDetail(hasilId) {
    // Fetch detail skor via AJAX
    fetch(`/hasil/${hasilId}/detail`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Gagal mengambil data');
            }
            
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');
            
            content.innerHTML = `
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Skor Mahasiswa</h3>
                    <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium">${data.data.mahasiswa.nama}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">NIM:</span>
                        <span>${data.data.mahasiswa.nim}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Program Studi:</span>
                        <span>${data.data.mahasiswa.prodi}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Periode:</span>
                        <span>${data.data.periode ? data.data.periode.nama_periode : '-'}</span>
                    </div>
                    
                    <div class="border-t pt-3 mt-3">
                        <h4 class="font-medium text-gray-800 mb-2">Detail Skor:</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skor IPK:</span>
                                <span class="font-medium">${data.data.skor_ipk}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skor Penghasilan:</span>
                                <span class="font-medium">${data.data.skor_penghasilan}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skor Tanggungan:</span>
                                <span class="font-medium">${data.data.skor_tanggungan}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skor Prestasi:</span>
                                <span class="font-medium">${data.data.skor_prestasi}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-gray-800 font-bold">Total Skor:</span>
                                <span class="font-bold text-lg text-blue-600">${data.data.total_skor}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ranking:</span>
                            <span class="font-bold ${data.data.status ? 'text-green-600' : 'text-gray-600'}">
                                #${data.data.ranking}
                            </span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-gray-600">Status:</span>
                            ${data.data.status ? 
                                '<span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">🎯 Lolos Seleksi</span>' : 
                                '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">❌ Tidak Lolos</span>'
                            }
                        </div>
                        <div class="text-xs text-gray-500 mt-2">
                            <div>Waktu Perhitungan: ${data.data.updated_at}</div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button onclick="closeModal('detailModal')"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tutup
                    </button>
                </div>
            `;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail skor: ' + error.message);
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});
</script>
@endpush
@endsection