@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Periode Seleksi</h1>
            <p class="text-gray-600">Kelola periode seleksi beasiswa</p>
        </div>
        <button onclick="openModal('createPeriodeModal')" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-plus mr-2"></i>Tambah Periode
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($periodes as $periode)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
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
                            <div class="flex space-x-2">
                                <button onclick="openEditModal({{ $periode }})" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $periode->id }}, '{{ $periode->nama_periode }}', {{ $periode->mahasiswas_count ?? 0 }})" 
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
<div id="createPeriodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Periode Seleksi</h3>
            <button onclick="closeModal('createPeriodeModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('periode.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="space-y-4">
                <!-- Nama Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Periode *</label>
                    <input type="text" name="nama_periode" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Beasiswa 2024 Genap">
                    @error('nama_periode')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Berakhir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir *</label>
                    <input type="date" name="tanggal_berakhir" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_berakhir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">
                        Jadikan periode aktif
                    </label>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            <strong>Perhatian:</strong> Mengaktifkan periode ini akan menonaktifkan periode aktif lainnya secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('createPeriodeModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Periode
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editPeriodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Edit Periode Seleksi</h3>
            <button onclick="closeModal('editPeriodeModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editPeriodeForm" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <!-- Nama Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Periode *</label>
                    <input type="text" name="nama_periode" id="edit_nama_periode" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Berakhir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir *</label>
                    <input type="date" name="tanggal_berakhir" id="edit_tanggal_berakhir" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="edit_is_active" class="ml-2 text-sm text-gray-700">
                        Jadikan periode aktif
                    </label>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            <strong>Perhatian:</strong> Mengaktifkan periode ini akan menonaktifkan periode aktif lainnya secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('editPeriodeModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Update Periode
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deletePeriodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3">Hapus Periode Seleksi</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus periode <span id="deleteItemName" class="font-semibold"></span>?
                </p>
                <p class="text-xs text-red-600 mt-2" id="deleteWarningText"></p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeModal('deletePeriodeModal')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="deleteSubmitButton"
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
function openEditModal(periode) {
    // Fill form data
    document.getElementById('edit_nama_periode').value = periode.nama_periode;
    document.getElementById('edit_tanggal_mulai').value = periode.tanggal_mulai;
    document.getElementById('edit_tanggal_berakhir').value = periode.tanggal_berakhir;
    document.getElementById('edit_is_active').checked = periode.is_active;
    
    // Update form action
    const form = document.getElementById('editPeriodeForm');
    form.action = `/periode/${periode.id}`;
    
    openModal('editPeriodeModal');
}

// Delete modal function - PERBAIKAN DI SINI
function openDeleteModal(id, name, mahasiswaCount) {
    document.getElementById('deleteItemName').textContent = name;
    const form = document.getElementById('deleteForm');
    form.action = `/periode/${id}`;
    
    const warningText = document.getElementById('deleteWarningText');
    const deleteButton = document.getElementById('deleteSubmitButton');
    
    if (mahasiswaCount > 0) {
        warningText.textContent = `Periode ini memiliki ${mahasiswaCount} data mahasiswa dan tidak dapat dihapus!`;
        warningText.className = 'text-xs text-red-600 mt-2';
        deleteButton.disabled = true;
        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        warningText.textContent = 'Tindakan ini tidak dapat dibatalkan!';
        warningText.className = 'text-xs text-red-600 mt-2';
        deleteButton.disabled = false;
        deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    openModal('deletePeriodeModal');
}

// Success message handling
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('error') }}', 'error');
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
    }, 5000);
}

// Date validation
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalBerakhirInput = document.querySelector('input[name="tanggal_berakhir"]');
    
    if (tanggalMulaiInput && tanggalBerakhirInput) {
        tanggalMulaiInput.addEventListener('change', function() {
            tanggalBerakhirInput.min = this.value;
        });
        
        // Set min date for tanggal berakhir based on tanggal mulai
        if (tanggalMulaiInput.value) {
            tanggalBerakhirInput.min = tanggalMulaiInput.value;
        }
    }
    
    const editTanggalMulaiInput = document.getElementById('edit_tanggal_mulai');
    const editTanggalBerakhirInput = document.getElementById('edit_tanggal_berakhir');
    
    if (editTanggalMulaiInput && editTanggalBerakhirInput) {
        editTanggalMulaiInput.addEventListener('change', function() {
            editTanggalBerakhirInput.min = this.value;
        });
    }
});
</script>
@endpush

<style>
/* Custom scrollbar for modal */
.modal-content {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>
@endsection