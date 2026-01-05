@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Periode Seleksi</h1>
            <p class="text-gray-600">Kelola periode seleksi beasiswa</p>
        </div>
        <button onclick="openModal('createPeriodeModal')" 
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Periode
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($periodes as $periode)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $periode->nama_periode }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($periode->tanggal_berakhir < now())
                                <span class="text-red-500">(Periode Telah Berakhir)</span>
                                @elseif($periode->tanggal_mulai > now())
                                <span class="text-blue-500">(Periode Belum Dimulai)</span>
                                @else
                                <span class="text-green-500">(Periode Berjalan)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($periode->tanggal_berakhir)->format('d M Y') }}
                            <div class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($periode->tanggal_berakhir)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                {{ $periode->kuota_penerima }} Penerima
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($periode->is_active)
                            <span class="flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Aktif
                            </span>
                            @else
                            <span class="flex items-center gap-1 px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                {{ $periode->mahasiswas_count ?? 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="openEditModal({{ $periode->id }})" 
                                   class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal({{ $periode->id }}, '{{ $periode->nama_periode }}', {{ $periode->mahasiswas_count ?? 0 }})" 
                                   class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                   title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada periode seleksi</p>
                            <p class="text-sm mt-1">Mulai dengan menambahkan periode seleksi baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createPeriodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Periode Seleksi
            </h3>
            <button onclick="closeModal('createPeriodeModal')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('periode.store') }}" method="POST" class="mt-4">
            @csrf
            <div class="space-y-4">
                <!-- Nama Periode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Nama Periode *
                    </label>
                    <input type="text" name="nama_periode" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Beasiswa 2024 Genap"
                           value="{{ old('nama_periode') }}">
                    @error('nama_periode')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Tanggal Mulai *
                        </label>
                        <input type="date" name="tanggal_mulai" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('tanggal_mulai') }}">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Tanggal Berakhir *
                        </label>
                        <input type="date" name="tanggal_berakhir" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('tanggal_berakhir') }}">
                        @error('tanggal_berakhir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kuota Penerima -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                        Kuota Penerima Beasiswa *
                    </label>
                    <input type="number" name="kuota_penerima" 
                           value="{{ old('kuota_penerima', 10) }}"
                           min="1" max="1000" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Jumlah mahasiswa yang akan diterima">
                    @error('kuota_penerima')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                           {{ old('is_active') ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Jadikan periode aktif
                    </label>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Informasi Penting</p>
                            <p class="text-sm text-blue-700 mt-1">
                                <strong>Status Aktif:</strong> Mengaktifkan periode ini akan menonaktifkan periode aktif lainnya secara otomatis.<br>
                                <strong>Tanggal Berakhir:</strong> Periode akan otomatis nonaktif setelah melewati tanggal berakhir.<br>
                                <strong>Pendaftaran:</strong> Mahasiswa hanya dapat mendaftar pada periode yang aktif.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('createPeriodeModal')"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Batal
                </button>
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
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
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Periode Seleksi
            </h3>
            <button onclick="closeModal('editPeriodeModal')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
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

                <div class="grid grid-cols-2 gap-4">
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
                </div>

                <!-- Kuota Penerima -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Penerima Beasiswa *</label>
                    <input type="number" name="kuota_penerima" id="edit_kuota_penerima"
                           min="1" max="1000" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="edit_is_active" class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Jadikan periode aktif
                    </label>
                </div>

                <!-- Status Info -->
                <div id="editStatusInfo" class="hidden p-3 border rounded-lg">
                    <div class="flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <span id="editStatusText"></span>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Perhatian</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Mengaktifkan periode ini akan menonaktifkan periode aktif lainnya secara otomatis.<br>
                                Periode yang sudah lewat tanggal berakhir tidak dapat diaktifkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeModal('editPeriodeModal')"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Batal
                </button>
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
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
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-3">Hapus Periode Seleksi</h3>
            <div class="mt-2 px-4 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus periode <span id="deleteItemName" class="font-semibold"></span>?
                </p>
                <p class="text-xs mt-2" id="deleteWarningText"></p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeModal('deletePeriodeModal')"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="deleteSubmitButton"
                            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
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
    document.body.style.overflow = 'hidden';
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

// Date validation for create form
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalBerakhirInput = document.querySelector('input[name="tanggal_berakhir"]');
    
    if (tanggalMulaiInput && tanggalBerakhirInput) {
        // Set min date for tanggal mulai (today)
        const today = new Date();
        const todayFormatted = today.toISOString().split('T')[0];
        tanggalMulaiInput.min = todayFormatted;
        
        tanggalMulaiInput.addEventListener('change', function() {
            tanggalBerakhirInput.min = this.value;
            // Validasi jika tanggal berakhir sebelum tanggal mulai
            if (tanggalBerakhirInput.value && tanggalBerakhirInput.value < this.value) {
                tanggalBerakhirInput.value = this.value;
            }
        });
        
        tanggalBerakhirInput.addEventListener('change', function() {
            // Check if end date is in the past
            if (new Date(this.value) < new Date()) {
                alert('Tanggal berakhir tidak boleh di masa lalu. Periode akan otomatis nonaktif.');
            }
        });
    }
});

// Edit modal function
async function openEditModal(periodeId) {
    try {
        // Fetch periode data via AJAX - gunakan route yang benar
        const response = await fetch(`/periode/${periodeId}/ajax`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Gagal mengambil data');
        }
        
        const periode = result.data;
        
        // Fill form data
        document.getElementById('edit_nama_periode').value = periode.nama_periode;
        document.getElementById('edit_tanggal_mulai').value = periode.tanggal_mulai.split(' ')[0]; // Ambil hanya tanggal
        document.getElementById('edit_tanggal_berakhir').value = periode.tanggal_berakhir.split(' ')[0]; // Ambil hanya tanggal
        document.getElementById('edit_kuota_penerima').value = periode.kuota_penerima;
        
        // Handle checkbox - PERBAIKAN PENTING
        const isActiveCheckbox = document.getElementById('edit_is_active');
        isActiveCheckbox.checked = periode.is_active;
        
        // Update form action
        const form = document.getElementById('editPeriodeForm');
        form.action = `/periode/${periodeId}`;
        
        // Check if periode has ended
        const endDate = new Date(periode.tanggal_berakhir);
        const startDate = new Date(periode.tanggal_mulai);
        const today = new Date();
        
        const statusInfo = document.getElementById('editStatusInfo');
        const statusText = document.getElementById('editStatusText');
        
        if (endDate < today) {
            statusInfo.classList.remove('hidden');
            statusInfo.className = 'p-3 border border-yellow-300 rounded-lg bg-yellow-50';
            statusText.innerHTML = '<span class="text-yellow-700">Periode ini telah berakhir. Tidak dapat diaktifkan kembali.</span>';
            isActiveCheckbox.disabled = true;
            isActiveCheckbox.checked = false;
        } else if (startDate > today) {
            statusInfo.classList.remove('hidden');
            statusInfo.className = 'p-3 border border-blue-300 rounded-lg bg-blue-50';
            statusText.innerHTML = '<span class="text-blue-700">Periode ini belum dimulai. Dapat diaktifkan sekarang.</span>';
            isActiveCheckbox.disabled = false;
        } else {
            statusInfo.classList.remove('hidden');
            statusInfo.className = 'p-3 border border-green-300 rounded-lg bg-green-50';
            statusText.innerHTML = '<span class="text-green-700">Periode ini sedang berjalan. Dapat diaktifkan atau dinonaktifkan.</span>';
            isActiveCheckbox.disabled = false;
        }
        
        // Set date validation
        const editTanggalMulaiInput = document.getElementById('edit_tanggal_mulai');
        const editTanggalBerakhirInput = document.getElementById('edit_tanggal_berakhir');
        
        // Set min date for edit form
        const todayFormatted = today.toISOString().split('T')[0];
        
        editTanggalMulaiInput.addEventListener('change', function() {
            editTanggalBerakhirInput.min = this.value;
            // Validasi jika tanggal berakhir sebelum tanggal mulai
            if (editTanggalBerakhirInput.value && editTanggalBerakhirInput.value < this.value) {
                editTanggalBerakhirInput.value = this.value;
            }
        });
        
        if (editTanggalMulaiInput.value) {
            editTanggalBerakhirInput.min = editTanggalMulaiInput.value;
        }
        
        openModal('editPeriodeModal');
        
    } catch (error) {
        console.error('Error fetching periode data:', error);
        alert('Gagal memuat data periode. Silakan coba lagi.');
    }
}

// Delete modal function
function openDeleteModal(id, name, mahasiswaCount) {
    document.getElementById('deleteItemName').textContent = name;
    const form = document.getElementById('deleteForm');
    form.action = `/periode/${id}`;
    
    const warningText = document.getElementById('deleteWarningText');
    const deleteButton = document.getElementById('deleteSubmitButton');
    
    if (mahasiswaCount > 0) {
        warningText.innerHTML = `
            <div class="flex items-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Periode ini memiliki ${mahasiswaCount} data mahasiswa dan tidak dapat dihapus!
            </div>
        `;
        deleteButton.disabled = true;
        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        warningText.innerHTML = `
            <div class="flex items-center text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Tindakan ini tidak dapat dibatalkan!
            </div>
        `;
        deleteButton.disabled = false;
        deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    openModal('deletePeriodeModal');
}

// Auto-close success/error messages
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });
});

// Prevent form submission if dates are invalid
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.querySelector('form[action="{{ route("periode.store") }}"]');
    const editForm = document.getElementById('editPeriodeForm');
    
    function validateForm(form) {
        const tanggalMulai = form.querySelector('input[name="tanggal_mulai"]');
        const tanggalBerakhir = form.querySelector('input[name="tanggal_berakhir"]');
        
        if (tanggalMulai && tanggalBerakhir) {
            const startDate = new Date(tanggalMulai.value);
            const endDate = new Date(tanggalBerakhir.value);
            
            if (endDate < startDate) {
                alert('Tanggal berakhir tidak boleh sebelum tanggal mulai!');
                return false;
            }
            
            if (endDate < new Date()) {
                const confirmEndDate = confirm('Tanggal berakhir sudah lewat. Periode akan otomatis nonaktif. Lanjutkan?');
                if (!confirmEndDate) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    }
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush

<style>
/* Modal overlay animation */
.fixed {
    transition: opacity 0.3s ease;
}

/* Smooth transitions */
button, input, select, textarea {
    transition: all 0.2s ease;
}

/* Better focus styles */
input:focus, select:focus, textarea:focus {
    outline: none;
    ring: 2px;
}

/* Scrollbar styling for modal */
.modal-content {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.modal-content::-webkit-scrollbar {
    width: 8px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Checkbox styling */
input[type="checkbox"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

input[type="checkbox"]:checked:after {
    content: '';
    position: absolute;
    display: block;
}

/* Disabled state */
input:disabled, button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
@endsection