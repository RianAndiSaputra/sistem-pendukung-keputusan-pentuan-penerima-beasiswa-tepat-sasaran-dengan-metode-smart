<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 ml-64">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button id="toggleSidebar" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-blue-600">
                    SPK Beasiswa
                </span>
            </div>
            <div class="flex items-center">
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-medium text-gray-700">{{ auth('admin')->user()->name }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ auth('admin')->user()->role }}</div>
                    </div>
                    
                    <div class="relative">
                        <button type="button" 
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" 
                                id="user-menu-button">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr(auth('admin')->user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200" 
                             id="user-dropdown" 
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth('admin')->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth('admin')->user()->email }}</p>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full mt-1
                                    {{ auth('admin')->user()->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                       (auth('admin')->user()->role == 'operator' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ auth('admin')->user()->role }}
                                </span>
                            </div>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2 ml-1"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.getElementById('user-menu-button').addEventListener('click', function() {
    const dropdown = document.getElementById('user-dropdown');
    if (dropdown.style.display === 'none') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('user-dropdown');
    const button = document.getElementById('user-menu-button');
    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});
</script>