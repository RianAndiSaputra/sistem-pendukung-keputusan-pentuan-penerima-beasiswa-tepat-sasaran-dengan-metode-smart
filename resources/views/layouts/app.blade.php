<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Beasiswa - Universitas Mercu Buana Yogyakarta</title>
    
    <!-- CSRF Token - INI PENTING! -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .main-transition {
            transition: margin-left 0.3s ease-in-out;
        }
        @media (max-width: 1024px) {
            .sidebar-hidden {
                transform: translateX(-100%);
            }
            .sidebar-visible {
                transform: translateX(0);
            }
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
                top: 80px;
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
            padding-right: 8px;
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
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Modern Alert Container -->
    <div class="alert-container" id="alertContainer"></div>

    <div id="app">
        @include('layouts.navbar')
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <main class="main-transition ml-0 lg:ml-64 mt-16 p-4 lg:p-6 min-h-screen">
            @yield('content')
        </main>

        <!-- Overlay untuk mobile -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Modern Alert Function with Lucide Icons
        function showAlert(type, title, message, duration = 3000) {
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

        // Handle backend flash messages
        document.addEventListener('DOMContentLoaded', function() {
            // Success message
            @if(session('success'))
                showAlert('success', 'Berhasil', '{{ session('success') }}');
            @endif
            
            // Error message
            @if(session('error'))
                showAlert('error', 'Terjadi Kesalahan', '{{ session('error') }}');
            @endif
            
            // Warning message
            @if(session('warning'))
                showAlert('warning', 'Peringatan', '{{ session('warning') }}');
            @endif
            
            // Info message
            @if(session('info'))
                showAlert('info', 'Informasi', '{{ session('info') }}');
            @endif

            // Validation errors
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showAlert('error', 'Validasi Gagal', '{{ $error }}');
                @endforeach
            @endif
        });

        // Toggle Sidebar untuk Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.querySelector('main');

            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-hidden');
                sidebar.classList.toggle('sidebar-visible');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }

            if (toggleButton) {
                toggleButton.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close sidebar ketika klik menu item di mobile
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        toggleSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('sidebar-hidden', 'sidebar-visible');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.add('sidebar-hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>