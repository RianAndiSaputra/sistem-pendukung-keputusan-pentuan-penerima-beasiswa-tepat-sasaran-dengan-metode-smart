@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Hasil Seleksi</h1>
            <p class="text-gray-600">Laporan lengkap hasil seleksi beasiswa per periode</p>
        </div>
        
        {{-- <a href="{{ route('laporan.komprehensif') }}" 
           class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Laporan Komprehensif</span>
        </a> --}}
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <button id="tab-laporan" class="tab-button active px-3 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
                Laporan Per Periode
            </button>
            <button id="tab-statistik" class="tab-button px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                Statistik Detail
            </button>
        </nav>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h2>
        <form id="filterForm" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Periode Seleksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Seleksi *</label>
                    <select name="periode_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="previewLaporan()">
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

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="button" onclick="previewLaporan()"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Preview
                    </button>
                    <button type="submit" formaction="{{ route('laporan.cetak') }}" target="_blank"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tab Content: Laporan -->
    <div id="content-laporan" class="tab-content">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <!-- Total Peserta -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800" id="totalPeserta">0</div>
                        <div class="text-sm text-gray-600">Total Peserta</div>
                    </div>
                </div>
            </div>
            
            <!-- Peserta Lolos -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800" id="totalLolos">0</div>
                        <div class="text-sm text-gray-600">Peserta Lolos</div>
                    </div>
                </div>
            </div>
            
            <!-- Rata-rata Skor -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800" id="rataSkor">0.00</div>
                        <div class="text-sm text-gray-600">Rata-rata Skor</div>
                    </div>
                </div>
            </div>
            
            <!-- Kuota Penerima -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800" id="kuota">0</div>
                        <div class="text-sm text-gray-600">Kuota Penerima</div>
                    </div>
                </div>
            </div>
            
            <!-- Tidak Lolos -->
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800" id="tidakLolos">0</div>
                        <div class="text-sm text-gray-600">Tidak Lolos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div id="previewSection" class="bg-white rounded-xl shadow p-6 mb-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Detail Hasil Seleksi</h2>
                <div class="flex gap-2">
                    <button onclick="exportPDF()" 
                            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        PDF
                    </button>
                    <button onclick="exportExcel()"
                            class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="12" y1="13" x2="12" y2="22"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Excel
                    </button>
                </div>
            </div>
            
            <div id="previewContent">
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data</h3>
                    <p class="text-gray-600">Pilih periode untuk melihat preview laporan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Statistik -->
    <div id="content-statistik" class="tab-content hidden">
        <div id="statistikContent" class="bg-white rounded-xl shadow p-6">
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Statistik Detail</h3>
                <p class="text-gray-600">Pilih periode untuk melihat statistik lengkap</p>
            </div>
        </div>
    </div>

    <!-- Daftar Periode -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Daftar Periode Seleksi
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($periodes as $periode)
            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-shadow duration-200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $periode->nama_periode }}</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($periode->tanggal_berakhir)->format('d M Y') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $periode->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $periode->is_active ? 'Aktif' : 'Selesai' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between mt-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-blue-600">{{ $periode->hasil_lolos_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Lolos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $periode->kuota_penerima }}</div>
                        <div class="text-xs text-gray-500">Kuota</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-800">{{ $periode->mahasiswas_count ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Peserta</div>
                    </div>
                </div>
                
                <div class="mt-4 flex gap-2">
                    <button onclick="quickExport({{ $periode->id }})" 
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Export
                    </button>
                    <button onclick="selectPeriode({{ $periode->id }})" 
                            class="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Lihat
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Export Modal -->
<div id="quickExportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
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
                <form id="quickExportForm" method="GET" action="{{ route('laporan.cetak') }}" target="_blank" class="inline">
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
// Tab functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
            btn.classList.add('text-gray-500');
        });
        
        // Add active class to clicked tab
        this.classList.add('active', 'text-blue-600', 'border-blue-600');
        this.classList.remove('text-gray-500');
        
        // Show corresponding content
        const tabId = this.id.replace('tab-', 'content-');
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById(tabId).classList.remove('hidden');
    });
});

// Select periode dari daftar
function selectPeriode(periodeId) {
    document.querySelector('select[name="periode_id"]').value = periodeId;
    previewLaporan();
}

// Quick export function
function quickExport(periodeId) {
    const periode = {!! $periodes->toJson() !!}.find(p => p.id === periodeId);
    if (periode) {
        document.getElementById('exportPeriodeName').textContent = periode.nama_periode;
        document.getElementById('exportPeriodeId').value = periodeId;
        document.getElementById('quickExportModal').classList.remove('hidden');
    }
}

function closeExportModal() {
    document.getElementById('quickExportModal').classList.add('hidden');
}

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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="2" x2="12" y2="6"/>
                <line x1="12" y1="18" x2="12" y2="22"/>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/>
                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/>
                <line x1="2" y1="12" x2="6" y2="12"/>
                <line x1="18" y1="12" x2="22" y2="12"/>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/>
                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/>
            </svg>
            <span class="ml-2 text-gray-600">Memuat data laporan...</span>
        </div>
    `;

    // Show preview section
    document.getElementById('previewSection').classList.remove('hidden');

    // Fetch data
    fetch(`/laporan/preview?periode_id=${periodeId}`)
        .then(response => response.json())
        .then(data => {
            updateStatistics(data);
            renderPreview(data);
            renderStatistik(data);
        })
        .catch(error => {
            console.error('Error:', error);
            previewContent.innerHTML = `
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-500 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Gagal memuat data</h3>
                    <p class="text-gray-600">Terjadi kesalahan saat mengambil data</p>
                </div>
            `;
        });
}

// Update statistics cards
function updateStatistics(data) {
    const stats = data.statistics;
    document.getElementById('totalPeserta').textContent = stats.totalPeserta;
    document.getElementById('totalLolos').textContent = stats.totalLolos;
    document.getElementById('rataSkor').textContent = stats.rataSkor;
    document.getElementById('kuota').textContent = stats.kuota;
    document.getElementById('tidakLolos').textContent = stats.totalPeserta - stats.totalLolos;
}

// Render preview table
function renderPreview(data) {
    const previewContent = document.getElementById('previewContent');
    const { hasilSeleksi, periode, statistics, avgScores } = data;
    
    let html = `
        <!-- Header Laporan -->
        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">LAPORAN HASIL SELEKSI BEASISWA</h2>
                <h3 class="text-xl text-blue-600 font-semibold">${periode.nama_periode}</h3>
                <p class="text-gray-600 mt-1">
                    Periode: ${formatDate(periode.tanggal_mulai)} - ${formatDate(periode.tanggal_berakhir)}
                </p>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm text-gray-600">Kuota Penerima</div>
                    <div class="text-xl font-bold text-blue-600">${statistics.kuota}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm text-gray-600">Skor Tertinggi</div>
                    <div class="text-xl font-bold text-green-600">${statistics.skorTertinggi}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm text-gray-600">Skor Terendah</div>
                    <div class="text-xl font-bold text-red-600">${statistics.skorTerendah}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                    <div class="text-sm text-gray-600">Persentase Lolos</div>
                    <div class="text-xl font-bold ${(statistics.totalLolos/statistics.totalPeserta*100) >= 50 ? 'text-green-600' : 'text-yellow-600'}">
                        ${((statistics.totalLolos/statistics.totalPeserta)*100).toFixed(2)}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Skor Rata-rata -->
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Rata-rata Skor Per Komponen
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-white rounded-lg">
                    <div class="text-xs text-gray-600 uppercase">Skor IPK</div>
                    <div class="text-xl font-bold text-purple-600">${avgScores.ipk}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <div class="text-xs text-gray-600 uppercase">Skor Penghasilan</div>
                    <div class="text-xl font-bold text-purple-600">${avgScores.penghasilan}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <div class="text-xs text-gray-600 uppercase">Skor Tanggungan</div>
                    <div class="text-xl font-bold text-purple-600">${avgScores.tanggungan}</div>
                </div>
                <div class="text-center p-3 bg-white rounded-lg">
                    <div class="text-xs text-gray-600 uppercase">Skor Prestasi</div>
                    <div class="text-xl font-bold text-purple-600">${avgScores.prestasi}</div>
                </div>
            </div>
        </div>

        <!-- Table Hasil Seleksi -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3 border text-center">RANK</th>
                        <th class="p-3 border text-left">NIM</th>
                        <th class="p-3 border text-left">NAMA MAHASISWA</th>
                        <th class="p-3 border text-left">PRODI</th>
                        <th class="p-3 border text-center">IPK</th>
                        <th class="p-3 border text-center">SKOR IPK</th>
                        <th class="p-3 border text-center">SKOR PENGHASILAN</th>
                        <th class="p-3 border text-center">SKOR TANGGUNGAN</th>
                        <th class="p-3 border text-center">SKOR PRESTASI</th>
                        <th class="p-3 border text-center">TOTAL SKOR</th>
                        <th class="p-3 border text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
    `;

    hasilSeleksi.forEach(item => {
        const isLolos = item.status;
        html += `
            <tr class="${isLolos ? 'bg-green-50 hover:bg-green-100' : 'hover:bg-gray-50'}">
                <td class="p-3 border text-center font-bold ${item.ranking <= statistics.kuota ? 'text-green-700' : 'text-gray-700'}">
                    #${item.ranking}
                </td>
                <td class="p-3 border font-mono">${item.mahasiswa.nim}</td>
                <td class="p-3 border">${item.mahasiswa.nama}</td>
                <td class="p-3 border">${item.mahasiswa.prodi}</td>
                <td class="p-3 border text-center">${item.mahasiswa.ipk}</td>
                <td class="p-3 border text-center">${parseFloat(item.skor_ipk).toFixed(2)}</td>
                <td class="p-3 border text-center">${parseFloat(item.skor_penghasilan).toFixed(2)}</td>
                <td class="p-3 border text-center">${parseFloat(item.skor_tanggungan).toFixed(2)}</td>
                <td class="p-3 border text-center">${parseFloat(item.skor_prestasi).toFixed(2)}</td>
                <td class="p-3 border text-center font-bold ${isLolos ? 'text-green-700' : 'text-gray-700'}">
                    ${parseFloat(item.total_skor).toFixed(2)}
                </td>
                <td class="p-3 border text-center">
                    ${isLolos ? 
                        '<span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">LOLOS</span>' : 
                        '<span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-full">TIDAK LOLOS</span>'}
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-6 p-4 border-t border-gray-200">
            <div class="flex justify-between text-sm text-gray-600">
                <div>
                    <p>Generated: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="text-right">
                    <p>Total Data: ${statistics.totalPeserta} mahasiswa</p>
                    <p>Yang Lolos: ${statistics.totalLolos} mahasiswa</p>
                </div>
            </div>
        </div>
    `;

    previewContent.innerHTML = html;
}

// Render statistik untuk tab statistik
function renderStatistik(data) {
    const statistikContent = document.getElementById('statistikContent');
    const { statistics, prodiStats } = data;
    
    let html = `
        <h3 class="text-xl font-bold text-gray-800 mb-6">Analisis Statistik Detail</h3>
        
        <!-- Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl">
                <h4 class="font-semibold text-blue-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 21H3"/>
                        <path d="M3 21V3"/>
                        <path d="M21 3v18"/>
                        <path d="M9 15l4-8 4 8"/>
                    </svg>
                    Distribusi Skor
                </h4>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>Skor Tertinggi</span>
                            <span class="font-bold">${statistics.skorTertinggi}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>Skor Terendah</span>
                            <span class="font-bold">${statistics.skorTerendah}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: ${(parseFloat(statistics.skorTerendah)/parseFloat(statistics.skorTertinggi)*100)}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>Rata-rata Skor</span>
                            <span class="font-bold">${statistics.rataSkor}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${(parseFloat(statistics.rataSkor)/parseFloat(statistics.skorTertinggi)*100)}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl">
                <h4 class="font-semibold text-green-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v8"/>
                        <path d="M8 12h8"/>
                    </svg>
                    Persentase Kelulusan
                </h4>
                <div class="text-center">
                    <div class="inline-block relative w-48 h-48">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="#E5E7EB"
                                  stroke-width="3"/>
                            <path d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none"
                                  stroke="#10B981"
                                  stroke-width="3"
                                  stroke-dasharray="${(statistics.totalLolos/statistics.totalPeserta*100)}, 100"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div>
                                <div class="text-3xl font-bold text-green-700">
                                    ${((statistics.totalLolos/statistics.totalPeserta)*100).toFixed(1)}%
                                </div>
                                <div class="text-sm text-green-600">Lolos Seleksi</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-700">
                        ${statistics.totalLolos} dari ${statistics.totalPeserta} peserta
                    </div>
                </div>
            </div>
        </div>
    `;

    // Statistik per prodi jika ada
    if (prodiStats && prodiStats.length > 0) {
        html += `
            <div class="mb-8">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                    Statistik Per Program Studi
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="p-3 border text-left">Program Studi</th>
                                <th class="p-3 border text-center">Total Peserta</th>
                                <th class="p-3 border text-center">Lolos</th>
                                <th class="p-3 border text-center">% Lolos</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        prodiStats.forEach(prodi => {
            html += `
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">${prodi.prodi}</td>
                    <td class="p-3 border text-center">${prodi.total}</td>
                    <td class="p-3 border text-center">
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                            ${prodi.lolos}
                        </span>
                    </td>
                    <td class="p-3 border text-center">
                        <span class="px-2 py-1 text-xs ${prodi.persentase >= 50 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'} rounded-full">
                            ${prodi.persentase}%
                        </span>
                    </td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    html += `
        <div class="text-xs text-gray-500 text-center mt-8">
            <p>Statistik dihitung berdasarkan data periode ${formatDate(data.periode.tanggal_mulai)} - ${formatDate(data.periode.tanggal_berakhir)}</p>
        </div>
    `;

    statistikContent.innerHTML = html;
}

// Export functions
function exportPDF() {
    const periodeId = document.querySelector('select[name="periode_id"]').value;
    if (periodeId) {
        window.open(`/laporan/cetak?periode_id=${periodeId}&type=pdf`, '_blank');
    } else {
        showNotification('Pilih periode terlebih dahulu', 'error');
    }
}

function exportExcel() {
    const periodeId = document.querySelector('select[name="periode_id"]').value;
    if (periodeId) {
        window.open(`/laporan/cetak?periode_id=${periodeId}&type=excel`, '_blank');
    } else {
        showNotification('Pilih periode terlebih dahulu', 'error');
    }
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                ${type === 'success' ? '<polyline points="20 6 9 17 4 12"/>' : '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'}
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection