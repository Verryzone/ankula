<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pesanan Saya</h1>
                <p class="text-gray-600">Kelola dan pantau status pesanan Anda</p>
            </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600">Total Pesanan</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-600">Menunggu Pembayaran</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
            <div class="text-sm text-gray-600">Diproses</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
            <div class="text-sm text-gray-600">Selesai</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <div class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-sm text-gray-600">Dibatalkan</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pesanan</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Nomor pesanan atau nama produk..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                    <!-- Order Header -->
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-2 lg:mb-0">
                            <h3 class="font-semibold text-gray-900">
                                Order #{{ $order->order_number }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Order Status -->
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'processing' => 'Diproses',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                            
                            <!-- Payment Status -->
                            @if($order->payment)
                                @php
                                    $paymentClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'success' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $paymentLabels = [
                                        'pending' => 'Belum Bayar',
                                        'success' => 'Lunas',
                                        'failed' => 'Gagal',
                                        'expired' => 'Kadaluarsa'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $paymentClasses[$order->payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentLabels[$order->payment->status] ?? ucfirst($order->payment->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="p-4">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <!-- Items -->
                            <div class="lg:col-span-2">
                                <h4 class="font-medium text-gray-900 mb-3">Produk ({{ $order->orderItems->count() }} item)</h4>
                                <div class="space-y-2">
                                    @foreach($order->orderItems->take(2) as $item)
                                        <div class="flex items-center gap-3">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-12 h-12 object-cover rounded">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <p class="font-medium text-sm">{{ $item->product->name }}</p>
                                                <p class="text-xs text-gray-600">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($order->orderItems->count() > 2)
                                        <p class="text-sm text-gray-600">
                                            +{{ $order->orderItems->count() - 2 }} produk lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Total & Actions -->
                            <div class="text-right">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600">Total Pesanan</p>
                                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="space-y-2">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                       class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700 transition duration-300">
                                        Lihat Detail
                                    </a>
                                    
                                    @if($order->status === 'pending' && $order->payment && $order->payment->status === 'pending')
                                        <a href="{{ route('payment.retry', $order->id) }}" 
                                           class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-md hover:bg-green-700 transition duration-300">
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                    
                                    @if($order->status === 'pending')
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300">
                                                Batalkan Pesanan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki pesanan apapun. Mulai berbelanja sekarang!</p>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Mulai Belanja
            </a>
        </div>
    @endif
        </div>
    </div>
</x-app-layout>
