@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Mahasiswa</h1>
            <p class="text-gray-600">Kelola data mahasiswa pendaftar beasiswa</p>
        </div>
        <button onclick="openModal('createMahasiswaModal')" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Tambah Mahasiswa
        </button>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Prodi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Manajemen">Manajemen</option>
                    <option value="Akuntansi">Akuntansi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" placeholder="Cari nama atau NIM..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
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
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDetailModal({{ $mahasiswa }})" 
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama }}')" 
                                   class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data Mahasiswa</h3>
            <button onclick="closeModal('createMahasiswaModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
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
                    <input type="number" name="ipk" step="0.01" min="0" max="4" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Penghasilan Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Orang Tua (Rp) *</label>
                    <input type="number" name="penghasilan_ortu" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File KHS</label>
                    <input type="file" name="khs_file" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           accept=".pdf,.jpg,.png">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Penghasilan</label>
                    <input type="file" name="penghasilan_file" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           accept=".pdf,.jpg,.png">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Sertifikat</label>
                    <input type="file" name="sertifikat_file" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           accept=".pdf,.jpg,.png">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('createMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
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
            <button onclick="closeModal('editMahasiswaModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
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
                    <input type="number" name="ipk" id="edit_ipk" step="0.01" min="0" max="4" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Penghasilan Orang Tua -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penghasilan Orang Tua (Rp) *</label>
                    <input type="number" name="penghasilan_ortu" id="edit_penghasilan_ortu" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

            <!-- File Uploads -->
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-3">File saat ini akan tetap digunakan jika tidak diubah</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File KHS</label>
                        <input type="file" name="khs_file" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               accept=".pdf,.jpg,.png">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Penghasilan</label>
                        <input type="file" name="penghasilan_file" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               accept=".pdf,.jpg,.png">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Sertifikat</label>
                        <input type="file" name="sertifikat_file" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               accept=".pdf,.jpg,.png">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('editMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Detail Mahasiswa</h3>
            <button onclick="closeModal('detailMahasiswaModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="mt-4 space-y-4" id="detailContent">
            <!-- Detail content will be filled by JavaScript -->
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
            <button onclick="closeModal('detailMahasiswaModal')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteMahasiswaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3">Hapus Data Mahasiswa</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus data mahasiswa <span id="deleteItemName" class="font-semibold"></span>?
                </p>
                <p class="text-xs text-red-600 mt-2">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeModal('deleteMahasiswaModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        Hapus
                    </button>
                </form>
            </div>
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

// Edit modal function
function openEditModal(mahasiswa) {
    // Fill form data
    document.getElementById('edit_nim').value = mahasiswa.nim;
    document.getElementById('edit_nama').value = mahasiswa.nama;
    document.getElementById('edit_prodi').value = mahasiswa.prodi;
    document.getElementById('edit_semester').value = mahasiswa.semester;
    document.getElementById('edit_ipk').value = mahasiswa.ipk;
    document.getElementById('edit_penghasilan_ortu').value = mahasiswa.penghasilan_ortu;
    document.getElementById('edit_jumlah_tanggungan').value = mahasiswa.jumlah_tanggungan;
    document.getElementById('edit_prestasi').value = mahasiswa.prestasi;
    document.getElementById('edit_periode_id').value = mahasiswa.periode_id;
    
    // Update form action
    const form = document.getElementById('editMahasiswaForm');
    form.action = `/mahasiswa/${mahasiswa.id}`;
    
    openModal('editMahasiswaModal');
}

// Detail modal function
function openDetailModal(mahasiswa) {
    const detailContent = document.getElementById('detailContent');
    
    // Format penghasilan
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    // Get prestasi text
    const getPrestasiText = (value) => {
        const prestasiMap = {
            1: 'Tidak memiliki sertifikat/prestasi',
            2: 'Sertifikat partisipasi (tanpa juara)',
            3: 'Juara lomba tingkat kampus/kota',
            4: 'Juara provinsi / memiliki ≥2 sertifikat prestasi',
            5: 'Juara nasional atau internasional'
        };
        return prestasiMap[value] || 'Tidak diketahui';
    };

    detailContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3">Data Pribadi</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">NIM:</span>
                        <span class="text-sm font-medium">${mahasiswa.nim}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nama:</span>
                        <span class="text-sm font-medium">${mahasiswa.nama}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Program Studi:</span>
                        <span class="text-sm font-medium">${mahasiswa.prodi}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Semester:</span>
                        <span class="text-sm font-medium">${mahasiswa.semester}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-3">Data Akademik & Ekonomi</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IPK:</span>
                        <span class="text-sm font-medium">${mahasiswa.ipk}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Penghasilan Orang Tua:</span>
                        <span class="text-sm font-medium">${formatRupiah(mahasiswa.penghasilan_ortu)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Tanggungan:</span>
                        <span class="text-sm font-medium">${mahasiswa.jumlah_tanggungan} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tingkat Prestasi:</span>
                        <span class="text-sm font-medium">${getPrestasiText(mahasiswa.prestasi)}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-3">Informasi Lainnya</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Periode Seleksi:</span>
                    <span class="text-sm font-medium">${mahasiswa.periode.nama_periode}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tanggal Daftar:</span>
                    <span class="text-sm font-medium">${new Date(mahasiswa.created_at).toLocaleDateString('id-ID')}</span>
                </div>
            </div>
        </div>

        ${mahasiswa.khs_file || mahasiswa.penghasilan_file || mahasiswa.sertifikat_file ? `
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-3">File Terlampir</h4>
            <div class="space-y-2">
                ${mahasiswa.khs_file ? `
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">File KHS:</span>
                    <a href="/storage/${mahasiswa.khs_file}" target="_blank" 
                       class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <i class="fas fa-file-pdf mr-1"></i> Lihat File
                    </a>
                </div>
                ` : ''}
                ${mahasiswa.penghasilan_file ? `
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">File Penghasilan:</span>
                    <a href="/storage/${mahasiswa.penghasilan_file}" target="_blank" 
                       class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <i class="fas fa-file-pdf mr-1"></i> Lihat File
                    </a>
                </div>
                ` : ''}
                ${mahasiswa.sertifikat_file ? `
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">File Sertifikat:</span>
                    <a href="/storage/${mahasiswa.sertifikat_file}" target="_blank" 
                       class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <i class="fas fa-file-pdf mr-1"></i> Lihat File
                    </a>
                </div>
                ` : ''}
            </div>
        </div>
        ` : ''}
    `;
    
    openModal('detailMahasiswaModal');
}

// Delete modal function
function openDeleteModal(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    const form = document.getElementById('deleteForm');
    form.action = `/mahasiswa/${id}`;
    openModal('deleteMahasiswaModal');
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
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
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