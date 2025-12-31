<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Beasiswa UMBY</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
        
        /* Modern Alert System - Top Right Corner */
        .alert-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            max-width: 420px;
            width: calc(100% - 48px);
        }
        
        @media (max-width: 640px) {
            .alert-container {
                top: 16px;
                right: 16px;
                width: calc(100% - 32px);
            }
        }
        
        .alert {
            display: flex;
            align-items: flex-start;
            padding: 18px 20px;
            margin-bottom: 12px;
            border-radius: 16px;
            box-shadow: 0 12px 28px -5px rgba(0, 0, 0, 0.25), 
                        0 8px 16px -8px rgba(0, 0, 0, 0.3);
            transform: translateX(450px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
        }
        
        .alert.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .alert.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
            color: white;
        }
        
        .alert.success::before {
            background: linear-gradient(180deg, #34d399 0%, #10b981 100%);
        }
        
        .alert.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%);
            color: white;
        }
        
        .alert.error::before {
            background: linear-gradient(180deg, #f87171 0%, #ef4444 100%);
        }
        
        .alert.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.95) 0%, rgba(217, 119, 6, 0.95) 100%);
            color: white;
        }
        
        .alert.warning::before {
            background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%);
        }
        
        .alert.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.95) 0%, rgba(29, 78, 216, 0.95) 100%);
            color: white;
        }
        
        .alert.info::before {
            background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
        }
        
        .alert-icon {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .alert-icon svg {
            width: 24px;
            height: 24px;
            stroke-width: 2.5;
        }
        
        .alert-content {
            flex: 1;
            min-width: 0;
            padding-top: 2px;
        }
        
        .alert-title {
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 16px;
            letter-spacing: -0.01em;
        }
        
        .alert-message {
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.95;
            font-weight: 400;
        }
        
        .alert-close {
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            cursor: pointer;
            margin-left: 12px;
            transition: all 0.2s ease;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .alert-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }
        
        .alert-close:active {
            transform: scale(0.95);
        }
        
        .alert-close svg {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
        }
        
        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 0 0 0 16px;
            transition: width linear;
        }

        /* Spinner Animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- Modern Alert Container -->
    <div class="alert-container" id="alertContainer"></div>
    
    <div class="max-w-6xl w-full flex rounded-2xl overflow-hidden shadow-soft">
        <!-- Left Panel - Illustration/Info -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100 p-10 flex-col justify-between">
            <div>
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-3">
                        <i data-lucide="graduation-cap" class="text-white text-xl"></i>
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
                            <i data-lucide="check-circle" class="text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Metode SMART</h3>
                            <p class="text-sm text-gray-600">Simple Multi-Attribute Rating Technique</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i data-lucide="bar-chart-3" class="text-blue-600"></i>
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
                    <i data-lucide="shield-check" class="mr-2"></i>
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
                
                <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="text-gray-400"></i>
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
                                    <i data-lucide="lock" class="text-gray-400"></i>
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
                                    <i data-lucide="eye" id="eyeIcon"></i>
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
                                id="submitBtn"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150"
                            >
                                <i data-lucide="log-in" class="mr-2"></i>
                                <span id="btnText">Masuk ke Sistem</span>
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
                            &copy; 2025 Universitas Mercu Buana Yogyakarta. Integrasi Metode SMART.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });

        // Modern Alert Function with Lucide Icons
        function showAlert(type, title, message, duration = 6000) {
            const alertContainer = document.getElementById('alertContainer');
            
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            
            // Get Lucide icon based on type
            let iconName = '';
            switch(type) {
                case 'success':
                    iconName = 'circle-check-big';
                    break;
                case 'error':
                    iconName = 'circle-x';
                    break;
                case 'warning':
                    iconName = 'triangle-alert';
                    break;
                case 'info':
                    iconName = 'info';
                    break;
                default:
                    iconName = 'bell';
            }
            
            alert.innerHTML = `
                <div class="alert-icon">
                    <i data-lucide="${iconName}"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">${title}</div>
                    <div class="alert-message">${message}</div>
                </div>
                <div class="progress-bar"></div>
            `;
            
            alertContainer.appendChild(alert);
            
            // Initialize Lucide icons for the new alert
            lucide.createIcons();
            
            // Show alert with animation
            setTimeout(() => {
                alert.classList.add('show');
            }, 10);
            
            // Progress bar animation
            const progressBar = alert.querySelector('.progress-bar');
            if (progressBar && duration > 0) {
                setTimeout(() => {
                    progressBar.style.transition = `width ${duration}ms linear`;
                    progressBar.style.width = '0%';
                }, 50);
            }
            
            // Auto close
            if (duration > 0) {
                setTimeout(() => {
                    if (alert.parentNode) {
                        closeAlert(alert);
                    }
                }, duration);
            }
            
            // Limit to 4 alerts
            const alerts = alertContainer.querySelectorAll('.alert');
            if (alerts.length > 4) {
                closeAlert(alerts[0]);
            }
        }
        
        function closeAlert(alert) {
            alert.classList.remove('show');
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }

        // Handle backend messages
        document.addEventListener('DOMContentLoaded', function() {
            // Laravel validation errors
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showAlert('error', 'Validasi Gagal', '{{ $error }}');
                @endforeach
            @endif
            
            // Success message
            @if(session('success'))
                showAlert('success', 'Berhasil', '{{ session('success') }}');
            @endif
            
            // Error message
            @if(session('error'))
                showAlert('error', 'Terjadi Kesalahan', '{{ session('error') }}');
            @endif
            
            // Warning message (jika ada)
            @if(session('warning'))
                showAlert('warning', 'Peringatan', '{{ session('warning') }}');
            @endif
            
            // Info message (jika ada)
            @if(session('info'))
                showAlert('info', 'Informasi', '{{ session('info') }}');
            @endif
        });

        // Form submission with loading state
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.innerHTML = '';
                const loaderIcon = document.createElement('i');
                loaderIcon.setAttribute('data-lucide', 'loader-2');
                loaderIcon.className = 'animate-spin mr-2';
                btnText.appendChild(loaderIcon);
                btnText.appendChild(document.createTextNode('Memproses...'));
                lucide.createIcons();
            });
        }
    </script>
</body>
</html>