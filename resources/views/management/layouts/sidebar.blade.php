<div class="mt-16 h-full">
    <button data-drawer-target="separator-sidebar" data-drawer-toggle="separator-sidebar" aria-controls="separator-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-all duration-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>
    <aside id="separator-sidebar"
        class="w-64 flex-shrink-0 h-full mt-0 transform transition-transform duration-300 ease-in-out"
        aria-label="Sidebar">
        <div class="h-full px-4 py-6 overflow-y-auto bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('management.index') }}" 
                       class="flex {{ Route::is('management.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} items-center p-3 rounded-lg transition-all duration-200 dark:text-white dark:hover:bg-gray-700">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 22 21">
                                <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                                <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Master Data -->
                <li>
                    <div>
                        <button type="button" onclick="toggleDropdown()"
                            class="flex items-center w-full p-3 text-gray-600 hover:bg-gray-50 rounded-lg transition-all duration-200 dark:text-gray-200 dark:hover:bg-gray-700">
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-100 mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                </svg>
                            </div>
                            <span class="flex-1 text-left">Master Data</span>
                            <svg id="dropdown-arrow" class="w-4 h-4 transition-transform duration-200 {{ Route::is('management.product.list', 'management.category.list', 'management.users.*') ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div id="dropdown-menu" class="overflow-hidden transition-all duration-300 ease-in-out {{ Route::is('management.product.list', 'management.category.list', 'management.users.*') ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">
                            <div class="ml-6 mt-2 space-y-1">
                                <a href="{{ route('management.product.list') }}" 
                                   class="flex {{ Route::is('management.product.list') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }} items-center p-2 rounded-lg transition-all duration-200">
                                    <div class="w-6 h-6 flex items-center justify-center rounded bg-green-100 mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span>Products</span>
                                </a>
                                <a href="{{ route('management.category.list') }}" 
                                   class="flex {{ Route::is('management.category.list') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }} items-center p-2 rounded-lg transition-all duration-200">
                                    <div class="w-6 h-6 flex items-center justify-center rounded bg-orange-100 mr-3">
                                        <svg class="w-3 h-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                        </svg>
                                    </div>
                                    <span>Categories</span>
                                </a>
                                <a href="{{ route('management.users.index') }}" 
                                   class="flex {{ Route::is('management.users.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }} items-center p-2 rounded-lg transition-all duration-200">
                                    <div class="w-6 h-6 flex items-center justify-center rounded bg-gray-100 mr-3">
                                        <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                    </div>
                                    <span>Users</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Orders -->
                <li>
                    <a href="{{ route('management.orders.index') }}" 
                       class="flex {{ Route::is('management.orders.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} items-center p-3 rounded-lg transition-all duration-200 dark:text-white dark:hover:bg-gray-700">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-100 mr-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 18 20">
                                <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V8a1 1 0 1 1 2 0v1Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V8a1 1 0 1 1 2 0v1Z"/>
                            </svg>
                        </div>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Dashboard Highlights -->
                <li>
                    <a href="{{ route('management.highlight.index') }}" 
                       class="flex {{ Route::is('management.highlight.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50' }} items-center p-3 rounded-lg transition-all duration-200 dark:text-white dark:hover:bg-gray-700">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-100 mr-3">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <span>Dashboard Highlights</span>
                    </a>
                </li>
            </ul>

            <!-- Admin Info -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center p-3 text-gray-500 dark:text-gray-400">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-100">
                        <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Admin Panel</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Management System</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>

<script>
let isDropdownOpen = {{ Route::is('management.product.list', 'management.category.list', 'management.users.*') ? 'true' : 'false' }};

function toggleDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    const arrow = document.getElementById('dropdown-arrow');
    
    if (isDropdownOpen) {
        // Close dropdown
        dropdown.style.maxHeight = '0px';
        dropdown.style.opacity = '0';
        arrow.style.transform = 'rotate(0deg)';
        isDropdownOpen = false;
    } else {
        // Open dropdown
        dropdown.style.maxHeight = '200px';
        dropdown.style.opacity = '1';
        arrow.style.transform = 'rotate(180deg)';
        isDropdownOpen = true;
    }
}

// Initialize dropdown state based on current route
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('dropdown-menu');
    const arrow = document.getElementById('dropdown-arrow');
    
    // Set initial state without animation for page load
    if (isDropdownOpen) {
        dropdown.style.maxHeight = '200px';
        dropdown.style.opacity = '1';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.style.maxHeight = '0px';
        dropdown.style.opacity = '0';
        arrow.style.transform = 'rotate(0deg)';
    }
});
</script>
