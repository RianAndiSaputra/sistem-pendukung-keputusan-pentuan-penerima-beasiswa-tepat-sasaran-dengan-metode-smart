@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kriteria & Bobot</h1>
        <p class="text-gray-600">Kelola kriteria dan bobot untuk perhitungan SMART</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
            <h2 class="text-lg font-semibold text-blue-800">Total Bobot: {{ $totalBobot }}%</h2>
            @if($totalBobot != 100)
            <p class="text-red-600 text-sm mt-1">Total bobot harus 100%. Silakan sesuaikan bobot.</p>
            @endif
        </div>
        
        <form action="{{ route('kriteria.update') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (%)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter Skor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($kriterias as $kriteria)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $kriteria->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $kriteria->tipe == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $kriteria->tipe }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="bobot[{{ $kriteria->id }}]" 
                                       value="{{ $kriteria->bobot }}" 
                                       min="0" max="100" step="0.01"
                                       class="w-24 border border-gray-300 rounded-lg px-3 py-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($kriteria->nama == 'IPK')
                                    1 (<2.50), 2 (2.50-2.99), 3 (3.00-3.49), 4 (3.50-3.79), 5 (3.80-4.00)
                                @elseif($kriteria->nama == 'Penghasilan Orang Tua')
                                    1 (>7.5jt), 2 (5-7.5jt), 3 (2.5-5jt), 4 (1-2.5jt), 5 (≤1jt)
                                @elseif($kriteria->nama == 'Jumlah Tanggungan')
                                    1 (0-1), 2 (2), 3 (3), 4 (4), 5 (≥5)
                                @else
                                    1 (Tidak ada), 2 (Sertifikat partisipasi), 3 (Juara kampus/kota), 4 (Juara provinsi/≥2 sertifikat), 5 (Juara nasional/internasional)
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Parameter Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($kriterias as $kriteria)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $kriteria->nama }}</h3>
            <div class="space-y-2">
                @if($kriteria->nama == 'IPK')
                <div class="flex justify-between text-sm">
                    <span>Skor 1:</span>
                    <span class="font-medium">IPK < 2.50</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 2:</span>
                    <span class="font-medium">IPK 2.50 – 2.99</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 3:</span>
                    <span class="font-medium">IPK 3.00 – 3.49</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 4:</span>
                    <span class="font-medium">IPK 3.50 – 3.79</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 5:</span>
                    <span class="font-medium">IPK 3.80 – 4.00</span>
                </div>
                @elseif($kriteria->nama == 'Penghasilan Orang Tua')
                <div class="flex justify-between text-sm">
                    <span>Skor 1:</span>
                    <span class="font-medium">> 7.500.000</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 2:</span>
                    <span class="font-medium">5.000.001 – 7.500.000</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 3:</span>
                    <span class="font-medium">2.500.001 – 5.000.000</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 4:</span>
                    <span class="font-medium">1.000.001 – 2.500.000</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 5:</span>
                    <span class="font-medium">≤ 1.000.000</span>
                </div>
                @elseif($kriteria->nama == 'Jumlah Tanggungan')
                <div class="flex justify-between text-sm">
                    <span>Skor 1:</span>
                    <span class="font-medium">0 – 1 orang</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 2:</span>
                    <span class="font-medium">2 orang</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 3:</span>
                    <span class="font-medium">3 orang</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 4:</span>
                    <span class="font-medium">4 orang</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 5:</span>
                    <span class="font-medium">≥ 5 orang</span>
                </div>
                @else
                <div class="flex justify-between text-sm">
                    <span>Skor 1:</span>
                    <span class="font-medium">Tidak memiliki sertifikat/prestasi</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 2:</span>
                    <span class="font-medium">Sertifikat partisipasi (tanpa juara)</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 3:</span>
                    <span class="font-medium">Juara lomba tingkat kampus/kota</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 4:</span>
                    <span class="font-medium">Juara provinsi / ≥2 sertifikat</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Skor 5:</span>
                    <span class="font-medium">Juara nasional/internasional</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection