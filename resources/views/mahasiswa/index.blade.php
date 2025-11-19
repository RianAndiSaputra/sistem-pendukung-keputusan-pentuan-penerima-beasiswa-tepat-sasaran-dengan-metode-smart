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
                                <button onclick="openEditModal({{ $mahasiswa }})" 
                                   class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDetailModal({{ $mahasiswa }})" 
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
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Hukum">Hukum</option>
                        <option value="Psikologi">Psikologi</option>
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
                <!-- Form fields sama seperti create modal -->
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
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Akuntansi">Akuntansi</option>
                        <option value="Hukum">Hukum</option>
                        <option value="Psikologi">Psikologi</option>
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

<!-- Detail Modal & Delete Modal tetap sama seperti sebelumnya, hanya ganti icon dengan Lucide -->
<!-- ... (kode untuk detail dan delete modal tetap sama, hanya ganti icon) ... -->

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
});

// Edit modal function
function openEditModal(mahasiswa) {
    // Fill form data
    document.getElementById('edit_nim').value = mahasiswa.nim;
    document.getElementById('edit_nama').value = mahasiswa.nama;
    document.getElementById('edit_prodi').value = mahasiswa.prodi;
    document.getElementById('edit_semester').value = mahasiswa.semester;
    document.getElementById('edit_ipk').value = mahasiswa.ipk;
    
    // Format penghasilan untuk edit
    const penghasilan = parseInt(mahasiswa.penghasilan_ortu).toLocaleString('id-ID');
    document.getElementById('edit_penghasilan_ortu').value = penghasilan;
    
    document.getElementById('edit_jumlah_tanggungan').value = mahasiswa.jumlah_tanggungan;
    document.getElementById('edit_prestasi').value = mahasiswa.prestasi;
    document.getElementById('edit_periode_id').value = mahasiswa.periode_id;
    
    // Update form action
    const form = document.getElementById('editMahasiswaForm');
    form.action = `/mahasiswa/${mahasiswa.id}`;
    
    openModal('editMahasiswaModal');
}

// Detail modal function (tetap sama seperti sebelumnya)
function openDetailModal(mahasiswa) {
    // ... kode detail modal tetap sama ...
}

// Delete modal function (tetap sama seperti sebelumnya)
function openDeleteModal(id, name) {
    // ... kode delete modal tetap sama ...
}

// Success message handling
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
@endif

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide ${type === 'success' ? 'lucide-check-circle' : 'lucide-alert-circle'} mr-2">
                ${type === 'success' ? 
                    '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>' :
                    '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>'
                }
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

<style>
.file-upload-container:hover .lucide {
    color: #3b82f6;
}
</style>
@endsection