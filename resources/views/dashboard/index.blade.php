@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard SPK Beasiswa</h1>
        <p class="text-gray-600">Sistem Pendukung Keputusan Seleksi Penerima Beasiswa - UMBY</p>
    </div>

    <!-- Period Selector -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Periode Seleksi</h3>
                <p class="text-gray-600">Pilih periode untuk melihat data statistik</p>
            </div>
            <div class="flex items-center space-x-3">
                <select id="periodeSelect" 
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-64">
                    <option value="">Semua Periode</option>
                    @foreach($allPeriodes as $periode)
                    <option value="{{ $periode->id }}" 
                            {{ $periodeAktif && $periode->id == $periodeAktif->id ? 'selected' : '' }}>
                        {{ $periode->nama_periode }}
                        @if($periode->is_active) (Aktif) @endif
                    </option>
                    @endforeach
                </select>
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                    </svg>
                    <span>
                        @if($periodeAktif)
                        {{ \Carbon\Carbon::parse($periodeAktif->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($periodeAktif->tanggal_berakhir)->format('d M Y') }}
                        @else
                        Tidak ada periode aktif
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users text-blue-600">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPendaftar }}</p>
                    @if($periodeAktif)
                    <p class="text-xs text-gray-500 mt-1">{{ $periodeAktif->nama_periode }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lolos Seleksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalLolos }}</p>
                    @if($periodeAktif && $periodeAktif->kuota_penerima)
                    <p class="text-xs text-gray-500 mt-1">Kuota: {{ $periodeAktif->kuota_penerima }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle text-red-600">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m15 9-6 6"/>
                        <path d="m9 9 6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tidak Lolos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTidakLolos }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $persentaseTidakLolos }}% dari total</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up text-purple-600">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rata-rata IPK</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($rataIpk, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Skala 4.00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Periode Aktif</p>
                    <p class="text-lg font-bold text-gray-800">
                        @if($periodeAktif)
                        {{ $periodeAktif->nama_periode }}
                        @else
                        Tidak ada
                        @endif
                    </p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award text-yellow-500">
                    <circle cx="12" cy="8" r="6"/>
                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Periode</p>
                    <p class="text-lg font-bold text-gray-800">{{ $totalPeriodes }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days text-blue-500">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                    <path d="M8 14h.01"/>
                    <path d="M12 14h.01"/>
                    <path d="M16 14h.01"/>
                    <path d="M8 18h.01"/>
                    <path d="M12 18h.01"/>
                    <path d="M16 18h.01"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rata-rata Skor</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format($rataSkor, 2) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart text-green-500">
                    <line x1="12" x2="12" y1="20" y2="10"/>
                    <line x1="18" x2="18" y1="20" y2="4"/>
                    <line x1="6" x2="6" y1="20" y2="16"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts and Top 3 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Grafik Nilai Rata-rata Kriteria</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bar-chart-3 text-gray-400">
                    <path d="M3 3v18h18"/>
                    <path d="M18 17V9"/>
                    <path d="M13 17V5"/>
                    <path d="M8 17v-3"/>
                </svg>
            </div>
            <div class="h-64">
                <canvas id="kriteriaChart"></canvas>
            </div>
        </div>

        <!-- Top 3 Mahasiswa -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Top 3 Mahasiswa</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-yellow-500">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                    <path d="M4 22h16"/>
                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                </svg>
            </div>
            <div class="space-y-4">
                @foreach($topMahasiswa as $index => $mahasiswa)
                <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-100 hover:shadow-md transition-shadow duration-200">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                        {{ $index + 1 }}
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $mahasiswa->mahasiswa->nama }}</h4>
                        <p class="text-sm text-gray-600">{{ $mahasiswa->mahasiswa->nim }} - {{ $mahasiswa->mahasiswa->prodi }}</p>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                IPK: {{ $mahasiswa->mahasiswa->ipk }}
                            </span>
                            @if($mahasiswa->status)
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                Lolos
                            </span>
                            @else
                            <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">
                                Tidak Lolos
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600 text-lg">{{ number_format($mahasiswa->total_skor, 2) }}</p>
                        <p class="text-sm text-gray-600">Total Skor</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Proses Perhitungan SMART</h3>
                    <p class="text-gray-600">Lakukan perhitungan untuk menentukan penerima beasiswa</p>
                </div>
                <a href="{{ route('perhitungan.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calculator mr-2">
                        <rect width="16" height="20" x="4" y="2" rx="2"/>
                        <line x1="8" x2="16" y1="6" y2="6"/>
                        <line x1="16" x2="16" y1="14" y2="18"/>
                        <path d="M16 10h.01"/>
                        <path d="M12 10h.01"/>
                        <path d="M8 10h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M8 14h.01"/>
                        <path d="M12 18h.01"/>
                        <path d="M8 18h.01"/>
                    </svg>
                    Proses Perhitungan
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Lihat Hasil Seleksi</h3>
                    <p class="text-gray-600">Lihat daftar lengkap hasil seleksi beasiswa</p>
                </div>
                <a href="{{ route('hasil.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list mr-2">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="M12 11h4"/>
                        <path d="M12 16h4"/>
                        <path d="M8 11h.01"/>
                        <path d="M8 16h.01"/>
                    </svg>
                    Lihat Hasil
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js untuk grafik kriteria
    const ctx = document.getElementById('kriteriaChart').getContext('2d');
    const kriteriaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['IPK', 'Penghasilan', 'Tanggungan', 'Prestasi'],
            datasets: [{
                label: 'Nilai Rata-rata',
                data: [
                    {{ $rataKriteria['ipk'] }},
                    {{ $rataKriteria['penghasilan'] }},
                    {{ $rataKriteria['tanggungan'] }},
                    {{ $rataKriteria['prestasi'] }}
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(139, 92, 246)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Periode selector functionality
    document.getElementById('periodeSelect').addEventListener('change', function() {
        const periodeId = this.value;
        if (periodeId) {
            window.location.href = `/dashboard?periode_id=${periodeId}`;
        } else {
            window.location.href = '/dashboard';
        }
    });

    // Toggle sidebar untuk mobile
    document.getElementById('toggleSidebar')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });
</script>
@endpush
@endsection