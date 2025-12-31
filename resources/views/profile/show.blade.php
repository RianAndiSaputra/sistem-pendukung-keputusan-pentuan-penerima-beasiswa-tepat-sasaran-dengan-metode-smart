@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi profil akun Anda</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                {{ auth('admin')->user()->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                   (auth('admin')->user()->role == 'operator' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                {{ auth('admin')->user()->role }}
            </span>
            <span class="text-sm text-gray-500">
                Bergabung: {{ auth('admin')->user()->created_at->format('d M Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Profil -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card: Informasi Dasar -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Dasar</h2>
                    <button onclick="openEditModal()" 
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Nama Lengkap</div>
                        <div class="w-2/3 text-gray-800">{{ $user->name }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Email</div>
                        <div class="w-2/3 text-gray-800">{{ $user->email }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Role</div>
                        <div class="w-2/3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $user->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role == 'operator' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">ID Akun</div>
                        <div class="w-2/3 text-gray-800 font-mono text-sm">{{ $user->id }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Bergabung Sejak</div>
                        <div class="w-2/3 text-gray-800">{{ $user->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div class="flex">
                        <div class="w-1/3 text-sm font-medium text-gray-500">Terakhir Diperbarui</div>
                        <div class="w-2/3 text-gray-800">{{ $user->updated_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Card: Keamanan Akun -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Keamanan Akun</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-800">Password</h3>
                            <p class="text-sm text-gray-600">Terakhir diubah: 
                                {{ $user->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <button onclick="openPasswordModal()" 
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-key mr-1"></i> Ganti Password
                        </button>
                    </div>
                    
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-shield-alt text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Status Akun Aman</h3>
                                <p class="text-sm text-green-600 mt-1">
                                    Akun Anda menggunakan autentikasi yang aman. Pastikan untuk selalu menjaga kerahasiaan password.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Avatar & Quick Info -->
        <div class="space-y-6">
            <!-- Card: Foto Profil -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Foto Profil</h2>
                
                <div class="text-center">
                    <div class="inline-block relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 mt-4">
                        Initial dari nama Anda akan ditampilkan sebagai avatar.
                    </p>
                </div>
            </div>

            <!-- Card: Aktivitas Terbaru -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Sistem</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Login terakhir berhasil</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Akun aktif dan terverifikasi</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-gray-700">Hak akses: {{ $user->role }}</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        Sistem SPK Beasiswa UMBY
                        <br>
                        {{ now()->format('d F Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div id="editProfileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Edit Profil</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ganti Password -->
<div id="changePasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Ganti Password</h3>
            <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('profile.password') }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini *</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan password saat ini">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru *</label>
                    <input type="password" name="new_password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Minimal 6 karakter">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                    <input type="password" name="new_password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-xs text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Pastikan password baru Anda kuat dan mudah diingat.
                </p>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closePasswordModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Modal functions
function openEditModal() {
    document.getElementById('editProfileModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editProfileModal').classList.add('hidden');
}

function openPasswordModal() {
    document.getElementById('changePasswordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
    }
});

// Password strength indicator
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.querySelector('input[name="new_password"]');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            const strengthText = document.getElementById('passwordStrength');
            if (strengthText) {
                let feedback = '';
                switch(strength) {
                    case 0:
                    case 1:
                        feedback = '<span class="text-red-600">Lemah</span>';
                        break;
                    case 2:
                        feedback = '<span class="text-yellow-600">Sedang</span>';
                        break;
                    case 3:
                    case 4:
                        feedback = '<span class="text-green-600">Kuat</span>';
                        break;
                }
                strengthText.innerHTML = `Kekuatan: ${feedback}`;
            }
        });
    }
});
</script>
@endpush
@endsection