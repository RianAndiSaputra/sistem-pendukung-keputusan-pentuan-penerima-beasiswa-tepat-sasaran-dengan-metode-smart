@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Hasil Seleksi</h1>
            <p class="text-gray-600">Generate dan cetak laporan hasil seleksi beasiswa</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h2>
        <form action="{{ route('laporan.cetak') }}" method="GET" target="_blank">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Periode Seleksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Seleksi *</label>
                    <select name="periode_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Periode</option>
                        @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Export -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Export *</label>
                    <select name="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pdf">PDF Document</option>
                        <option value="excel">Excel Spreadsheet</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="previewLaporan()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    <i class="fas fa-eye mr-2"></i>Preview
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-download mr-2"></i>Download Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-blue-600 mb-2">{{ $periodes->count() }}</div>
            <div class="text-sm text-gray-600">Total Periode</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-green-600 mb-2" id="totalPeserta">0</div>
            <div class="text-sm text-gray-600">Total Peserta</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-purple-600 mb-2" id="totalLolos">0</div>
            <div class="text-sm text-gray-600">Peserta Lolos</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-orange-600 mb-2" id="rataSkor">0</div>
            <div class="text-sm text-gray-600">Rata-rata Skor</div>
        </div>
    </div>

    <!-- Preview Section -->
    <div id="previewSection" class="bg-white rounded-lg shadow p-6 hidden">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Preview Laporan</h2>
        <div id="previewContent" class="border border-gray-200 rounded-lg p-4">
            <p class="text-gray-500 text-center">Pilih periode untuk melihat preview laporan</p>
        </div>
    </div>

    <!-- Recent Periodes -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Daftar Periode Tersedia</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodes as $periode)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $periode->nama_periode }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($periode->tanggal_berakhir)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($periode->is_active)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Aktif
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $periode->mahasiswas_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="quickExport({{ $periode->id }})" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                <i class="fas fa-download mr-1"></i>Export PDF
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quick Export Modal -->
<div id="quickExportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-download text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3">Export Laporan</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500">
                    Anda akan mengexport laporan untuk periode: <span id="exportPeriodeName" class="font-semibold"></span>
                </p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeExportModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <form id="quickExportForm" method="GET" target="_blank" class="inline">
                    <input type="hidden" name="periode_id" id="exportPeriodeId">
                    <input type="hidden" name="type" value="pdf">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview laporan function
function previewLaporan() {
    const periodeId = document.querySelector('select[name="periode_id"]').value;
    
    if (!periodeId) {
        showNotification('Pilih periode terlebih dahulu', 'error');
        return;
    }

    // Show loading state
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600">Memuat preview...</span>
        </div>
    `;

    // Fetch data untuk preview
    fetch(`/laporan/preview?periode_id=${periodeId}`)
        .then(response => response.json())
        .then(data => {
            updateStatistics(data.statistics);
            renderPreview(data.hasilSeleksi, data.periode);
            document.getElementById('previewSection').classList.remove('hidden');
        })
        .catch(error => {
            previewContent.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl mb-2"></i>
                    <p>Gagal memuat preview laporan</p>
                </div>
            `;
        });
}

// Update statistics cards
function updateStatistics(stats) {
    document.getElementById('totalPeserta').textContent = stats.totalPeserta;
    document.getElementById('totalLolos').textContent = stats.totalLolos;
    document.getElementById('rataSkor').textContent = stats.rataSkor;
}

// Render preview table
function renderPreview(data, periode) {
    const previewContent = document.getElementById('previewContent');
    
    let html = `
        <div class="mb-4 border-b pb-4">
            <h3 class="text-lg font-bold text-center text-gray-800">LAPORAN HASIL SELEKSI BEASISWA</h3>
            <p class="text-center text-gray-600">${periode.nama_periode}</p>
            <p class="text-center text-sm text-gray-500">
                ${formatDate(periode.tanggal_mulai)} - ${formatDate(periode.tanggal_berakhir)}
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left border">Rank</th>
                        <th class="px-4 py-2 text-left border">NIM</th>
                        <th class="px-4 py-2 text-left border">Nama Mahasiswa</th>
                        <th class="px-4 py-2 text-left border">Prodi</th>
                        <th class="px-4 py-2 text-center border">IPK</th>
                        <th class="px-4 py-2 text-center border">Total Skor</th>
                        <th class="px-4 py-2 text-center border">Status</th>
                    </tr>
                </thead>
                <tbody>
    `;

    data.forEach(item => {
        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border text-center">${item.ranking}</td>
                <td class="px-4 py-2 border font-mono">${item.mahasiswa.nim}</td>
                <td class="px-4 py-2 border">${item.mahasiswa.nama}</td>
                <td class="px-4 py-2 border">${item.mahasiswa.prodi}</td>
                <td class="px-4 py-2 border text-center">${item.mahasiswa.ipk}</td>
                <td class="px-4 py-2 border text-center font-semibold">${item.total_skor.toFixed(2)}</td>
                <td class="px-4 py-2 border text-center">
                    ${item.status ? 
                        '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">LOLOS</span>' : 
                        '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">TIDAK LOLOS</span>'}
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-xs text-gray-500">
            <p>Total Data: ${data.length} mahasiswa | Generated: ${new Date().toLocaleDateString('id-ID')}</p>
        </div>
    `;

    previewContent.innerHTML = html;
}

// Quick export function
function quickExport(periodeId) {
    const periode = {!! $periodes->toJson() !!}.find(p => p.id === periodeId);
    document.getElementById('exportPeriodeName').textContent = periode.nama_periode;
    document.getElementById('exportPeriodeId').value = periodeId;
    document.getElementById('quickExportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('quickExportModal').classList.add('hidden');
}

// Utility functions
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-preview when periode is selected
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.querySelector('select[name="periode_id"]');
    periodeSelect.addEventListener('change', function() {
        if (this.value) {
            previewLaporan();
        }
    });
});
</script>
@endpush
@endsection