<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-home text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('mahasiswa.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-users text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Data Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kriteria.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('kriteria.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-cog text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Kriteria & Bobot</span>
                </a>
            </li>
            <li>
                <a href="{{ route('perhitungan.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('perhitungan.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-calculator text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Perhitungan SMART</span>
                </a>
            </li>
            <li>
                <a href="{{ route('hasil.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('hasil.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-trophy text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Hasil Seleksi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('periode.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('periode.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-calendar text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Periode Seleksi</span>
                </a>
            </li>
            @if(auth('admin')->user()->role === 'super_admin')
            <li>
                <a href="{{ route('admin.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-user-shield text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Manajemen Admin</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('laporan.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-file-pdf text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Laporan</span>
                </a>
            </li>
            <li>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="flex items-center w-full p-2 text-gray-900 rounded-lg hover:bg-red-50 group">
                    <i class="fas fa-sign-out-alt text-gray-500 group-hover:text-red-600"></i>
                    <span class="ml-3 text-red-600">Logout</span>
                </button>
            </li>
        </ul>
    </div>
</aside>