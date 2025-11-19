@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard SPK Beasiswa</h1>
        <p class="text-gray-600">Sistem Pendukung Keputusan Seleksi Penerima Beasiswa - UMBY</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPendaftar }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lolos Seleksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalLolos }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tidak Lolos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTidakLolos }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rata-rata IPK</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($rataIpk, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Top 3 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Nilai Rata-rata Kriteria</h3>
            <div class="h-64">
                <canvas id="kriteriaChart"></canvas>
            </div>
        </div>

        <!-- Top 3 Mahasiswa -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 3 Mahasiswa</h3>
            <div class="space-y-4">
                @foreach($topMahasiswa as $index => $mahasiswa)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $mahasiswa->nama }}</h4>
                        <p class="text-sm text-gray-600">{{ $mahasiswa->nim }} - {{ $mahasiswa->prodi }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">{{ number_format($mahasiswa->total_skor, 2) }}</p>
                        <p class="text-sm text-gray-600">Skor</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Proses Perhitungan SMART</h3>
                <p class="text-gray-600">Lakukan perhitungan untuk menentukan penerima beasiswa</p>
            </div>
            <a href="{{ route('perhitungan.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                <i class="fas fa-calculator mr-2"></i>Proses Perhitungan
            </a>
        </div>
    </div>
</div>

@push('scripts')
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
                    'rgmasa(245, 158, 11, 0.8)',
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
                    max: 5
                }
            }
        }
    });

    // Toggle sidebar untuk mobile
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    });
</script>
@endpush
@endsection