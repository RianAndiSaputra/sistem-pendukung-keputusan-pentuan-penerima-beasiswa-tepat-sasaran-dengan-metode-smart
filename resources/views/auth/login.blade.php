<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Beasiswa UMBY</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fafafa;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .shadow-soft {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        /* Alert Styles */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }
        .alert {
            display: flex;
            align-items: center;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s ease;
            max-width: 100%;
        }
        .alert.show {
            transform: translateX(0);
            opacity: 1;
        }
        .alert.success {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        .alert.error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #7f1d1d;
        }
        .alert i {
            font-size: 20px;
            margin-right: 12px;
        }
        .alert-content {
            flex: 1;
        }
        .alert-title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .alert-message {
            font-size: 14px;
        }
        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            margin-left: 12px;
            transition: opacity 0.2s;
        }
        .alert-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Alert Container -->
    <div class="alert-container" id="alertContainer"></div>
    
    <div class="max-w-6xl w-full flex rounded-2xl overflow-hidden shadow-soft">
        <!-- Left Panel - Illustration/Info -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100 p-10 flex-col justify-between">
            <div>
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">SPK Beasiswa</h1>
                        <p class="text-sm text-gray-600">Universitas Mercu Buana Yogyakarta</p>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Sistem Pendukung Keputusan</h2>
                <p class="text-gray-600 mb-6">Seleksi penerimaan beasiswa dengan integrasi metode SMART untuk penilaian yang lebih objektif dan transparan.</p>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Metode SMART</h3>
                            <p class="text-sm text-gray-600">Simple Multi-Attribute Rating Technique</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Analisis Terstruktur</h3>
                            <p class="text-sm text-gray-600">Penilaian berdasarkan kriteria yang telah ditetapkan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-shield-alt mr-2"></i>
                    <span>Sistem terjamin keamanannya</span>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Login Form -->
        <div class="w-full md:w-1/2 glass-effect p-10">
            <div class="max-w-md mx-auto">
                <div class="text-center mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h1>
                    <p class="text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
                </div>
                
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    @foreach($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showAlert('success', 'Berhasil', '{{ session('success') }}');
                        });
                    </script>
                @endif
                
                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showAlert('error', 'Gagal', '{{ session('error') }}');
                        });
                    </script>
                @endif
                
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    placeholder="admin@mercubuana-yogya.ac.id"
                                    required
                                >
                            </div>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    placeholder="Masukkan kata sandi"
                                    required
                                >
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-150">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember_me" 
                                    name="remember_me" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya selama 30 hari
                                </label>
                            </div>
                            
                            <div class="text-sm">
                                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150">
                                    Lupa kata sandi?
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <button 
                                type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                            >
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk ke Sistem
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Sistem Pendukung Keputusan</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            &copy; 2023 Universitas Mercu Buana Yogyakarta. Integrasi Metode SMART.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Alert Function
        function showAlert(type, title, message, duration = 5000) {
            const alertContainer = document.getElementById('alertContainer');
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            
            // Set icon based on type
            let icon = '';
            if (type === 'success') {
                icon = '<i class="fas fa-check-circle"></i>';
            } else if (type === 'error') {
                icon = '<i class="fas fa-exclamation-circle"></i>';
            }
            
            // Set alert content
            alert.innerHTML = `
                ${icon}
                <div class="alert-content">
                    <div class="alert-title">${title}</div>
                    <div class="alert-message">${message}</div>
                </div>
                <button class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to container
            alertContainer.appendChild(alert);
            
            // Show alert with animation
            setTimeout(() => {
                alert.classList.add('show');
            }, 10);
            
            // Close button functionality
            const closeBtn = alert.querySelector('.alert-close');
            closeBtn.addEventListener('click', () => {
                closeAlert(alert);
            });
            
            // Auto close after duration
            if (duration > 0) {
                setTimeout(() => {
                    if (alert.parentNode) {
                        closeAlert(alert);
                    }
                }, duration);
            }
        }
        
        function closeAlert(alert) {
            alert.classList.remove('show');
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 400);
        }

        // Show alerts from backend if any
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there are any validation errors
            const errorElements = document.querySelector('.bg-red-50');
            if (errorElements) {
                const errorText = errorElements.querySelector('.text-red-700').textContent;
                showAlert('error', 'Login Gagal', errorText);
            }
        });
    </script>
</body>
</html>