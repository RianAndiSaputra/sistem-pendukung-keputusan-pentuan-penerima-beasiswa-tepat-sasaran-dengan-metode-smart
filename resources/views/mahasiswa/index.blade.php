@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Mahasiswa</h1>
            <p class="text-gray-600">Kelola data mahasiswa pendaftar beasiswa</p>
        </div>
        <button onclick="openModal('createMahasiswaModal')" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Tambah Mahasiswa
        </button>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form id="filterForm" method="GET" action="{{ route('mahasiswa.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari nama atau NIM..." 
                            value="{{ request('search') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-2.5 text-gray-400">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter mr-2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['prodi', 'periode_id', 'search']))
                    <a href="{{ route('mahasiswa.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw mr-2">
                            <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                            <path d="M16 16h5v5"/>
                        </svg>
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IPK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mahasiswas as $mahasiswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $mahasiswa->nim }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $mahasiswa->nama }}</div>
                            <div class="text-sm text-gray-500">Semester {{ $mahasiswa->semester }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mahasiswa->prodi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $mahasiswa->ipk >= 3.5 ? 'bg-green-100 text-green-800' : 
                                   ($mahasiswa->ipk >= 3.0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $mahasiswa->ipk }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $mahasiswa->periode->nama_periode }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="openEditModal({{ $mahasiswa->id }})" 
                                   class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDetailModal({{ $mahasiswa->id }})" 
                                   class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama }}')" 
                                   class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                        <path d="M3 6h18"/>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                        <line x1="10" x2="10" y1="11" y2="17"/>
                                        <line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($mahasiswas->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $mahasiswas->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div id="createMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data Mahasiswa</h3>
            <button onclick="closeModal('createMahasiswaModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('mahasiswa.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2">
                <!-- NIM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM *</label>
                    <input type="text" name="nim" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="nama" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Prodi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi *</label>
                    <select name="prodi" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Prodi</option>
                        <option value="Agroteknologi">Agroteknologi</option>
                        <option value="Industri Peternakan">Industri Peternakan</option>
                        <option value="Teknologi Hasil Pertanian">Teknologi Hasil Pertanian</option>
                        <option value="Magister Ilmu Pangan">Magister Ilmu Pangan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Ilmu Komunikasi dan Multimedia">Ilmu Komunikasi dan Multimedia</option>
                        <option value="Psikologi">Psikologi</option>
                        <option value="Magister Psikologi Sains">Magister Psikologi Sains</option>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester *</label>
                    <input type="number" name="semester" min="1" max="14" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- IPK -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IPK *</label>
                    <input type="text" name="ipk" id="ipk_input" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: 3.75">
                    <p class="text-xs text-gray-500 mt-1">Format: 0.00 - 4.00 (gunakan titik)</p>
                </div>

                <!-- Penghasilan Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Orang Tua (Rp) *</label>
                    <input type="text" name="penghasilan_ortu" id="penghasilan_input" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: 2.500.000">
                    <p class="text-xs text-gray-500 mt-1">Gunakan titik sebagai pemisah ribuan</p>
                </div>

                <!-- Jumlah Tanggungan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tanggungan *</label>
                    <input type="number" name="jumlah_tanggungan" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Prestasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Prestasi *</label>
                    <select name="prestasi" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tingkat Prestasi</option>
                        <option value="1">Tidak memiliki sertifikat/prestasi</option>
                        <option value="2">Sertifikat partisipasi (tanpa juara)</option>
                        <option value="3">Juara lomba tingkat kampus/kota</option>
                        <option value="4">Juara provinsi / memiliki ≥2 sertifikat prestasi</option>
                        <option value="5">Juara nasional atau internasional</option>
                    </select>
                </div>

                <!-- Periode -->
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
            </div>

            <!-- File Uploads -->
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Upload Dokumen</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- File KHS -->
                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File KHS</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="khs_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload text-gray-400 mx-auto mb-2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload KHS</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>

                    <!-- File Penghasilan -->
                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Penghasilan</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="penghasilan_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text text-gray-400 mx-auto mb-2">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                                <path d="M10 9H8"/>
                                <path d="M16 13H8"/>
                                <path d="M16 17H8"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload Penghasilan</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>

                    <!-- File Sertifikat -->
                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Sertifikat</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="sertifikat_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award text-gray-400 mx-auto mb-2">
                                <circle cx="12" cy="8" r="6"/>
                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload Sertifikat</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('createMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save mr-2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Edit Data Mahasiswa</h3>
            <button onclick="closeModal('editMahasiswaModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="editMahasiswaForm" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2">
                <!-- NIM -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIM *</label>
                    <input type="text" name="nim" id="edit_nim" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="nama" id="edit_nama" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Prodi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi *</label>
                    <select name="prodi" id="edit_prodi" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Prodi</option>
                        <option value="Agroteknologi">Agroteknologi</option>
                        <option value="Industri Peternakan">Industri Peternakan</option>
                        <option value="Teknologi Hasil Pertanian">Teknologi Hasil Pertanian</option>
                        <option value="Magister Ilmu Pangan">Magister Ilmu Pangan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Ilmu Komunikasi dan Multimedia">Ilmu Komunikasi dan Multimedia</option>
                        <option value="Psikologi">Psikologi</option>
                        <option value="Magister Psikologi Sains">Magister Psikologi Sains</option>
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester *</label>
                    <input type="number" name="semester" id="edit_semester" min="1" max="14" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- IPK -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IPK *</label>
                    <input type="text" name="ipk" id="edit_ipk" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Format: 0.00 - 4.00 (gunakan titik)</p>
                </div>

                <!-- Penghasilan Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Orang Tua (Rp) *</label>
                    <input type="text" name="penghasilan_ortu" id="edit_penghasilan_ortu" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Gunakan titik sebagai pemisah ribuan</p>
                </div>

                <!-- Jumlah Tanggungan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tanggungan *</label>
                    <input type="number" name="jumlah_tanggungan" id="edit_jumlah_tanggungan" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Prestasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Prestasi *</label>
                    <select name="prestasi" id="edit_prestasi" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tingkat Prestasi</option>
                        <option value="1">Tidak memiliki sertifikat/prestasi</option>
                        <option value="2">Sertifikat partisipasi (tanpa juara)</option>
                        <option value="3">Juara lomba tingkat kampus/kota</option>
                        <option value="4">Juara provinsi / memiliki ≥2 sertifikat prestasi</option>
                        <option value="5">Juara nasional atau internasional</option>
                    </select>
                </div>

                <!-- Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Seleksi *</label>
                    <select name="periode_id" id="edit_periode_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Periode</option>
                        @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- File Uploads untuk Edit -->
            <div class="mt-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Upload Dokumen</h4>
                <p class="text-sm text-gray-600 mb-3">File saat ini akan tetap digunakan jika tidak diubah</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- File upload components sama seperti create modal -->
                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File KHS</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="khs_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload text-gray-400 mx-auto mb-2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload KHS</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>

                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Penghasilan</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="penghasilan_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text text-gray-400 mx-auto mb-2">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                                <path d="M10 9H8"/>
                                <path d="M16 13H8"/>
                                <path d="M16 17H8"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload Penghasilan</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>

                    <div class="file-upload-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Sertifikat</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="sertifikat_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award text-gray-400 mx-auto mb-2">
                                <circle cx="12" cy="8" r="6"/>
                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk upload Sertifikat</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('editMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save mr-2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Detail Data Mahasiswa</h3>
            <button onclick="closeModal('detailMahasiswaModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Dasar</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">NIM</label>
                        <p id="detail_nim" class="mt-1 font-mono text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p id="detail_nama" class="mt-1 text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Program Studi</label>
                        <p id="detail_prodi" class="mt-1 text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Semester</label>
                        <p id="detail_semester" class="mt-1 text-gray-900"></p>
                    </div>
                </div>

                <!-- Informasi Akademik -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Akademik & Ekonomi</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">IPK</label>
                        <p id="detail_ipk" class="mt-1"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Penghasilan Orang Tua</label>
                        <p id="detail_penghasilan" class="mt-1 text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Tanggungan</label>
                        <p id="detail_tanggungan" class="mt-1 text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tingkat Prestasi</label>
                        <p id="detail_prestasi" class="mt-1 text-gray-900"></p>
                    </div>
                </div>

                <!-- Informasi Periode -->
                <div class="space-y-4 md:col-span-2">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Informasi Periode & Dokumen</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Periode Seleksi</label>
                            <p id="detail_periode" class="mt-1 text-gray-900"></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p id="detail_status" class="mt-1 text-gray-900"></p>
                        </div>
                    </div>

                    <!-- Dokumen -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-3">Dokumen Pendukung</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div id="detail_khs_file" class="border rounded-lg p-3 bg-gray-50">
                                <p class="text-sm font-medium text-gray-700 mb-2">File KHS</p>
                                <!-- File akan ditampilkan via JavaScript -->
                            </div>
                            
                            <div id="detail_penghasilan_file" class="border rounded-lg p-3 bg-gray-50">
                                <p class="text-sm font-medium text-gray-700 mb-2">File Penghasilan</p>
                                <!-- File akan ditampilkan via JavaScript -->
                            </div>
                            
                            <div id="detail_sertifikat_file" class="border rounded-lg p-3 bg-gray-50">
                                <p class="text-sm font-medium text-gray-700 mb-2">File Sertifikat</p>
                                <!-- File akan ditampilkan via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button onclick="closeModal('detailMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x mr-2">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Konfirmasi Hapus</h3>
            <button onclick="closeModal('deleteMahasiswaModal')" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="mt-4 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle text-red-600">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <path d="M12 9v4"/>
                    <path d="M12 17h.01"/>
                </svg>
            </div>
            
            <h3 class="text-lg font-medium text-gray-900 mb-2">Apakah Anda yakin?</h3>
            <p class="text-gray-600 mb-6">
                Anda akan menghapus data mahasiswa: 
                <span id="deleteMahasiswaName" class="font-semibold text-red-600"></span>
                <br>
                <span class="text-sm text-red-500">Aksi ini tidak dapat dibatalkan!</span>
            </p>
            
            <!-- FORM DELETE - PENTING: action dikosongkan, akan diisi JavaScript -->
            <form id="deleteMahasiswaForm" method="POST" action="" class="inline">
                <!-- CSRF Token akan otomatis ada dari Laravel -->
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeModal('deleteMahasiswaModal')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 mr-2">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            <line x1="10" x2="10" y1="11" y2="17"/>
                            <line x1="14" x2="14" y1="11" y2="17"/>
                        </svg>
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    });

    // Format IPK input
    document.addEventListener('DOMContentLoaded', function() {
        const ipkInput = document.getElementById('ipk_input');
        const editIpkInput = document.getElementById('edit_ipk');
        const penghasilanInput = document.getElementById('penghasilan_input');
        const editPenghasilanInput = document.getElementById('edit_penghasilan_ortu');

        // Format IPK
        if (ipkInput) {
            ipkInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9.]/g, '');
                let parts = value.split('.');
                
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                
                if (parts.length === 2 && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
                
                e.target.value = value;
            });
        }

        if (editIpkInput) {
            editIpkInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9.]/g, '');
                let parts = value.split('.');
                
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                
                if (parts.length === 2 && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
                
                e.target.value = value;
            });
        }

        // Format Penghasilan dengan titik pemisah ribuan
        function formatRupiahInput(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                
                if (value) {
                    value = parseInt(value, 10).toLocaleString('id-ID');
                }
                
                e.target.value = value;
            });
        }

        if (penghasilanInput) formatRupiahInput(penghasilanInput);
        if (editPenghasilanInput) formatRupiahInput(editPenghasilanInput);

        // File upload feedback
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const container = this.closest('.file-upload-container');
                const fileName = this.files[0] ? this.files[0].name : 'Belum ada file';
                
                let statusText = container.querySelector('.file-status') || document.createElement('p');
                statusText.className = 'file-status text-xs text-blue-600 mt-2 font-medium';
                statusText.textContent = `File: ${fileName}`;
                
                if (!container.querySelector('.file-status')) {
                    container.appendChild(statusText);
                }
            });
        });

        // Setup delete form handler
        setupDeleteForm();
    });

    // Edit modal function dengan AJAX
    function openEditModal(mahasiswaId) {
        // Fetch data mahasiswa via AJAX
        fetch(`/mahasiswa/${mahasiswaId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Data mahasiswa tidak ditemukan');
                }
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const mahasiswa = data.data;
                
                // Fill form data
                document.getElementById('edit_nim').value = mahasiswa.nim || '';
                document.getElementById('edit_nama').value = mahasiswa.nama || '';
                document.getElementById('edit_prodi').value = mahasiswa.prodi || '';
                document.getElementById('edit_semester').value = mahasiswa.semester || '';
                document.getElementById('edit_ipk').value = mahasiswa.ipk || '';
                
                // Format penghasilan untuk edit
                const penghasilan = mahasiswa.penghasilan_ortu ? 
                    parseInt(mahasiswa.penghasilan_ortu).toLocaleString('id-ID') : '';
                document.getElementById('edit_penghasilan_ortu').value = penghasilan;
                
                document.getElementById('edit_jumlah_tanggungan').value = mahasiswa.jumlah_tanggungan || '';
                document.getElementById('edit_prestasi').value = mahasiswa.prestasi || '';
                document.getElementById('edit_periode_id').value = mahasiswa.periode_id || '';
                
                // Update form action
                const form = document.getElementById('editMahasiswaForm');
                if (form) {
                    form.action = `/mahasiswa/${mahasiswaId}`;
                }
                
                openModal('editMahasiswaModal');
            } else {
                showAlert('error', 'Error', data.message || 'Gagal memuat data mahasiswa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error', 'Terjadi kesalahan saat memuat data: ' + error.message);
        });
    }

    // Detail modal function dengan AJAX
    function openDetailModal(mahasiswaId) {
        // Fetch data mahasiswa via AJAX
        fetch(`/mahasiswa/${mahasiswaId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Data mahasiswa tidak ditemukan');
                }
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const mahasiswa = data.data;
                
                // Fill detail data dengan null check
                document.getElementById('detail_nim').textContent = mahasiswa.nim || 'Tidak ada data';
                document.getElementById('detail_nama').textContent = mahasiswa.nama || 'Tidak ada data';
                document.getElementById('detail_prodi').textContent = mahasiswa.prodi || 'Tidak ada data';
                document.getElementById('detail_semester').textContent = mahasiswa.semester ? 
                    `Semester ${mahasiswa.semester}` : 'Tidak ada data';
                
                // IPK dengan warna
                const ipkElement = document.getElementById('detail_ipk');
                if (mahasiswa.ipk) {
                    ipkElement.textContent = mahasiswa.ipk;
                    ipkElement.className = 'mt-1 px-2 py-1 text-sm font-semibold rounded-full inline-block ' + 
                        (mahasiswa.ipk >= 3.5 ? 'bg-green-100 text-green-800' : 
                         (mahasiswa.ipk >= 3.0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'));
                } else {
                    ipkElement.textContent = 'Tidak ada data';
                    ipkElement.className = 'mt-1 px-2 py-1 text-sm font-semibold rounded-full inline-block bg-gray-100 text-gray-800';
                }
                
                // Format penghasilan
                const penghasilanElement = document.getElementById('detail_penghasilan');
                if (mahasiswa.penghasilan_ortu) {
                    const penghasilan = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(mahasiswa.penghasilan_ortu);
                    penghasilanElement.textContent = penghasilan;
                } else {
                    penghasilanElement.textContent = 'Tidak ada data';
                }
                
                document.getElementById('detail_tanggungan').textContent = mahasiswa.jumlah_tanggungan ? 
                    mahasiswa.jumlah_tanggungan + ' orang' : 'Tidak ada data';
                
                // Prestasi text
                const prestasiText = {
                    '1': 'Tidak memiliki sertifikat/prestasi',
                    '2': 'Sertifikat partisipasi (tanpa juara)',
                    '3': 'Juara lomba tingkat kampus/kota',
                    '4': 'Juara provinsi / memiliki ≥2 sertifikat prestasi',
                    '5': 'Juara nasional atau internasional'
                };
                const prestasiValue = mahasiswa.prestasi ? mahasiswa.prestasi.toString() : '';
                document.getElementById('detail_prestasi').textContent = prestasiText[prestasiValue] || 'Tidak ada data';
                
                // Periode
                const periodeText = mahasiswa.periode ? 
                    (mahasiswa.periode.nama_periode || 'Tidak ada data') : 'Tidak ada data';
                document.getElementById('detail_periode').textContent = periodeText;
                
                // Status (jika ada field status)
                const statusElement = document.getElementById('detail_status');
                if (mahasiswa.status) {
                    statusElement.textContent = mahasiswa.status;
                    statusElement.className = 'mt-1 px-2 py-1 text-sm font-semibold rounded-full inline-block ' +
                        (mahasiswa.status === 'diterima' ? 'bg-green-100 text-green-800' :
                         mahasiswa.status === 'ditolak' ? 'bg-red-100 text-red-800' :
                         'bg-yellow-100 text-yellow-800');
                } else {
                    statusElement.textContent = 'Menunggu seleksi';
                    statusElement.className = 'mt-1 px-2 py-1 text-sm font-semibold rounded-full inline-block bg-gray-100 text-gray-800';
                }
                
                // Dokumen files
                displayDocument('detail_khs_file', mahasiswa.khs_file, 'KHS');
                displayDocument('detail_penghasilan_file', mahasiswa.penghasilan_file, 'Penghasilan');
                displayDocument('detail_sertifikat_file', mahasiswa.sertifikat_file, 'Sertifikat');
                
                openModal('detailMahasiswaModal');
            } else {
                showAlert('error', 'Error', data.message || 'Gagal memuat detail mahasiswa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error', 'Terjadi kesalahan saat memuat detail: ' + error.message);
        });
    }

    // Function untuk menampilkan dokumen
    function displayDocument(elementId, fileName, docType) {
        const element = document.getElementById(elementId);
        
        if (!element) {
            console.error(`Element ${elementId} tidak ditemukan`);
            return;
        }
        
        if (fileName) {
            // Dapatkan ekstensi file
            const fileExt = fileName.split('.').pop().toLowerCase();
            const isPdf = fileExt === 'pdf';
            const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
            
            // Buat URL untuk file (asumsi file disimpan di storage public)
            const fileUrl = `/storage/${fileName}`;
            
            let content = '';
            if (isImage) {
                content = `
                    <div class="mb-2">
                        <img src="${fileUrl}" alt="${docType}" class="w-full h-32 object-cover rounded" 
                             onerror="this.onerror=null; this.src='/placeholder-image.jpg';">
                    </div>
                    <a href="${fileUrl}" target="_blank" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link mr-1">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15 3 21 3 21 9"/>
                            <line x1="10" x2="21" y1="14" y2="3"/>
                        </svg>
                        Lihat ${docType}
                    </a>
                `;
            } else if (isPdf) {
                content = `
                    <div class="mb-2 flex items-center justify-center h-24 bg-red-50 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text text-red-500">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                            <path d="M10 9H8"/>
                            <path d="M16 13H8"/>
                            <path d="M16 17H8"/>
                        </svg>
                    </div>
                    <a href="${fileUrl}" target="_blank" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download mr-1">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                        Download ${docType}
                    </a>
                `;
            } else {
                content = `
                    <p class="text-sm text-gray-500 mb-2">File tersedia</p>
                    <a href="${fileUrl}" target="_blank" 
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download mr-1">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                        Download ${docType}
                    </a>
                `;
            }
            
            element.innerHTML = content;
        } else {
            element.innerHTML = `
                <p class="text-sm text-gray-400 italic">Tidak ada file ${docType}</p>
            `;
        }
    }

    // Delete modal function - SOLUSI SIMPLE
    function openDeleteModal(id, name) {
        console.log('Opening delete modal for ID:', id, 'Name:', name);
        
        // Set nama mahasiswa
        const deleteNameElement = document.getElementById('deleteMahasiswaName');
        if (deleteNameElement) {
            deleteNameElement.textContent = name;
        }
        
        // Update form action
        const form = document.getElementById('deleteMahasiswaForm');
        if (form) {
            form.action = `/mahasiswa/${id}`;
            console.log('Form action set to:', form.action);
        }
        
        openModal('deleteMahasiswaModal');
    }

    // Setup delete form handler - SIMPLE SOLUTION
    function setupDeleteForm() {
        const deleteForm = document.getElementById('deleteMahasiswaForm');
        if (!deleteForm) {
            console.error('Delete form not found');
            return;
        }

        console.log('Delete form found, setting up handler');
        
        // Hapus semua event listener lama
        const newForm = deleteForm.cloneNode(true);
        deleteForm.parentNode.replaceChild(newForm, deleteForm);
        
        // Tambahkan event listener untuk form submission
        newForm.addEventListener('submit', function(e) {
            // Biarkan form submit normal, Laravel akan handle redirect
            console.log('Delete form will submit normally');
            // Tidak perlu e.preventDefault() karena kita mau form submit normal
        });
    }

    // File upload feedback
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const container = this.closest('.file-upload-container');
            if (!container) return;
            
            const fileName = this.files[0] ? this.files[0].name : 'Belum ada file';
            
            let statusText = container.querySelector('.file-status') || document.createElement('p');
            statusText.className = 'file-status text-xs text-blue-600 mt-2 font-medium';
            statusText.textContent = `File: ${fileName}`;
            
            if (!container.querySelector('.file-status')) {
                container.appendChild(statusText);
            }
        });
    });

    // Helper function untuk showAlert
    window.showAlert = function(type, title, message, duration = 3000) {
        // Coba gunakan alert system dari app.blade.js jika ada
        if (typeof window.showAlertOriginal !== 'undefined') {
            window.showAlertOriginal(type, title, message, duration);
            return;
        }
        
        // Fallback alert system
        const alertContainer = document.getElementById('alertContainer') || document.body;
        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'warning' ? 'bg-yellow-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        alert.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide ${
                    type === 'success' ? 'lucide-check-circle' : 
                    type === 'error' ? 'lucide-alert-circle' : 
                    type === 'warning' ? 'lucide-alert-triangle' : 
                    'lucide-info'
                } mr-2">
                    ${type === 'success' ? 
                        '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>' :
                    type === 'error' ?
                        '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>' :
                    type === 'warning' ?
                        '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>' :
                        '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="16"/><line x1="12" x2="12" y1="12" y2="8"/>'
                    }
                </svg>
                <span>${message}</span>
            </div>
        `;
        alertContainer.appendChild(alert);

        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, duration);
    };
</script>

@endpush

<style>
.file-upload-container:hover .lucide {
    color: #3b82f6;
}
</style>
@endsection