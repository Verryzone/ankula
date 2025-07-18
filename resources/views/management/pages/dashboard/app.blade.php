<x-management-app-layout>
    <div class="pt-4 px-3 mt-2 lg:py-11">
        <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-6 bg-white rounded-lg shadow mb-56">
            <div class="w-full mb-5">
                <div class="mb-2 py-4">
                    <nav class="flex mb-5" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                            <li class="inline-flex items-center">
                                <a href="{{ route('management.index') }}"
                                    class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">Home</span>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500"
                                        aria-current="page">Dashboard Analytics</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Analytics</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Selamat datang di panel analitik toko Anda</p>
                </div>

                <!-- Statistics Cards - Responsive grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
                    <!-- Total Revenue -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Pendapatan</p>
                                <p class="text-xl lg:text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                                @php
                                    $revenueGrowth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                                @endphp
                                <p class="text-blue-100 text-xs mt-1">
                                    @if($revenueGrowth > 0)
                                        <span class="text-green-200">↗ +{{ number_format($revenueGrowth, 1) }}%</span>
                                    @else
                                        <span class="text-red-200">↘ {{ number_format($revenueGrowth, 1) }}%</span>
                                    @endif
                                    dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-blue-400 bg-opacity-30 p-2 lg:p-3 rounded-full">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Pesanan</p>
                                <p class="text-xl lg:text-2xl font-bold">{{ number_format($totalOrders) }}</p>
                                @php
                                    $orderGrowth = $lastMonthOrders > 0 ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;
                                @endphp
                                <p class="text-green-100 text-xs mt-1">
                                    @if($orderGrowth > 0)
                                        <span class="text-green-200">↗ +{{ number_format($orderGrowth, 1) }}%</span>
                                    @else
                                        <span class="text-red-200">↘ {{ number_format($orderGrowth, 1) }}%</span>
                                    @endif
                                    dari bulan lalu
                                </p>
                            </div>
                            <div class="bg-green-400 bg-opacity-30 p-2 lg:p-3 rounded-full">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM6 9.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM17 9.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Produk</p>
                                <p class="text-xl lg:text-2xl font-bold">{{ number_format($totalProducts) }}</p>
                                <p class="text-purple-100 text-xs mt-1">Produk aktif</p>
                            </div>
                            <div class="bg-purple-400 bg-opacity-30 p-2 lg:p-3 rounded-full">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-sm font-medium">Total Pelanggan</p>
                                <p class="text-xl lg:text-2xl font-bold">{{ number_format($totalUsers) }}</p>
                                <p class="text-orange-100 text-xs mt-1">Pengguna terdaftar</p>
                            </div>
                            <div class="bg-orange-400 bg-opacity-30 p-2 lg:p-3 rounded-full">
                                <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
                    <!-- Revenue Chart -->
                    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-lg shadow">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-4">Pendapatan 6 Bulan Terakhir</h3>
                        <div class="space-y-3 lg:space-y-4">
                            @foreach($monthlyRevenue as $data)
                                @php
                                    $maxRevenue = collect($monthlyRevenue)->max('revenue');
                                    $percentage = $maxRevenue > 0 ? ($data['revenue'] / $maxRevenue) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 w-12 lg:w-16">{{ $data['month'] }}</span>
                                    <div class="flex-1 mx-2 lg:mx-4">
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2 lg:h-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 lg:h-3 rounded-full transition-all duration-300" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-white w-16 lg:w-20 text-right">
                                        Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Status Distribution -->
                    <div class="bg-white dark:bg-gray-800 p-4 lg:p-6 rounded-lg shadow">
                        <h3 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan</h3>
                        <div class="space-y-3 lg:space-y-4">
                            @php
                                $statusColors = [
                                    'pending' => 'from-yellow-400 to-yellow-500',
                                    'completed' => 'from-green-400 to-green-500',
                                    'cancelled' => 'from-red-400 to-red-500',
                                    'processing' => 'from-blue-400 to-blue-500'
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    'processing' => 'Diproses'
                                ];
                            @endphp
                            @foreach($orderStatuses as $status)
                                @php
                                    $percentage = $totalOrders > 0 ? ($status->count / $totalOrders) * 100 : 0;
                                    $colorClass = $statusColors[$status->status] ?? 'from-gray-400 to-gray-500';
                                    $label = $statusLabels[$status->status] ?? ucfirst($status->status);
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 lg:space-x-3">
                                        <div class="w-3 h-3 lg:w-4 lg:h-4 bg-gradient-to-r {{ $colorClass }} rounded-full"></div>
                                        <span class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1 lg:space-x-2">
                                        <span class="text-xs lg:text-sm text-gray-500">{{ number_format($percentage, 1) }}%</span>
                                        <span class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-white">{{ $status->count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Orders and Top Products -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 pb-8">
                    <!-- Recent Orders -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentOrders as $order)
                                <div class="p-4 lg:p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-xs lg:text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $order->order_number }}
                                            </p>
                                            <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                                                {{ $order->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $order->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-white">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </p>
                                            @php
                                                $statusBadgeClass = match($order->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'processing' => 'bg-blue-100 text-blue-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex px-1 lg:px-2 py-1 text-xs font-medium rounded-full {{ $statusBadgeClass }}">
                                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 lg:p-6 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada pesanan
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-4 lg:p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white">Produk Terlaris</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($topProducts as $index => $item)
                                <div class="p-4 lg:p-6">
                                    <div class="flex items-center space-x-3 lg:space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs lg:text-sm">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs lg:text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                            </p>
                                            <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                                                Terjual: {{ $item->total_sold }} unit
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($item->product)
                                                <p class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 lg:p-6 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data penjualan
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-management-app-layout>
