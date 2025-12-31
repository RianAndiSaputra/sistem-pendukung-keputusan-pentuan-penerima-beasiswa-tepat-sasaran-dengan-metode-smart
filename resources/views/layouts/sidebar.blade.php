<aside id="sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-gray-200 sidebar-transition lg:translate-x-0 sidebar-hidden lg:sidebar-visible">
    <div class="px-4 py-3 border-b border-gray-200 bg-white">
        <div class="flex items-center">
            <!-- Logo -->
            <div class="w-16 h-16 rounded-xl overflow-hidden flex items-center justify-center">
                <img src="{{ asset('image/logo.png') }}" alt="Logo UMBY" class="object-contain w-full h-full ">
            </div>

            <!-- Text -->
            <div class="ml-3 leading-tight">
                <h1 class="text-sm font-bold text-gray-900">UNIVERSITAS</h1>
                <h2 class="text-sm font-bold text-gray-900">MERCU BUANA</h2>
                <h3 class="text-xs font-semibold text-gray-900">YOGYAKARTA</h3>
            </div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="h-full px-3 pb-20 overflow-y-auto bg-white">
        <ul class="space-y-1 font-medium mt-4">

            <!-- Dashboard (Semua role bisa akses) -->
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center p-3 rounded-xl transition duration-150
                   {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i data-lucide="home" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            @php
                $userRole = auth('admin')->user()->role;
            @endphp

            <!-- Untuk SUPER ADMIN dan OPERATOR -->
            @if(in_array($userRole, ['super_admin', 'operator']))
                <!-- Data Mahasiswa -->
                <li>
                    <a href="{{ route('mahasiswa.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('mahasiswa.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('mahasiswa.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Data Mahasiswa</span>
                    </a>
                </li>

                <!-- Kriteria & Bobot -->
                <li>
                    <a href="{{ route('kriteria.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('kriteria.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="settings" class="w-5 h-5 {{ request()->routeIs('kriteria.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Kriteria & Bobot</span>
                    </a>
                </li>

                <!-- Perhitungan SMART -->
                <li>
                    <a href="{{ route('perhitungan.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('perhitungan.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="calculator" class="w-5 h-5 {{ request()->routeIs('perhitungan.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Perhitungan SMART</span>
                    </a>
                </li>
            @endif

            <!-- Hasil Seleksi (Semua role bisa akses) -->
            <li>
                <a href="{{ route('hasil.index') }}" 
                   class="flex items-center p-3 rounded-xl transition duration-150
                   {{ request()->routeIs('hasil.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i data-lucide="award" class="w-5 h-5 {{ request()->routeIs('hasil.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                    <span class="ml-3">Hasil Seleksi</span>
                </a>
            </li>

            <!-- Untuk SUPER ADMIN dan OPERATOR -->
            @if(in_array($userRole, ['super_admin', 'operator']))
                <!-- Periode Seleksi -->
                <li>
                    <a href="{{ route('periode.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('periode.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="calendar" class="w-5 h-5 {{ request()->routeIs('periode.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Periode Seleksi</span>
                    </a>
                </li>
            @endif

            <!-- Hanya SUPER ADMIN -->
            @if($userRole === 'super_admin')
                <li>
                    <a href="{{ route('admin.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="shield-check" class="w-5 h-5 {{ request()->routeIs('admin.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Manajemen Admin</span>
                    </a>
                </li>
            @endif

            <!-- Untuk SUPER ADMIN dan OPERATOR -->
            @if(in_array($userRole, ['super_admin', 'operator']))
                <!-- Laporan -->
                <li>
                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center p-3 rounded-xl transition duration-150
                       {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="file-text" class="w-5 h-5 {{ request()->routeIs('laporan.*') ? 'stroke-blue-600' : 'stroke-gray-400 group-hover:stroke-blue-600' }}"></i>
                        <span class="ml-3">Laporan</span>
                    </a>
                </li>
            @endif

        </ul>

        <!-- Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
            <div class="text-center">
                <p class="text-xs text-gray-500">Integrasi Metode SMART</p>
                <p class="text-xs text-gray-400 mt-1">&copy; 2025 UMBY</p>
            </div>
        </div>
    </div>
</aside>

<!-- Tambahkan Script Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>