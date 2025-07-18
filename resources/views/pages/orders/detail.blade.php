<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('orders.index') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-300">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Pesanan
                    </a>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pesanan</h1>
                <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Status Pesanan</h2>
                
                <div class="flex items-center justify-between mb-6">
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
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                    
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Tanggal Pesanan</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    @php
                        $statusOrder = ['pending', 'processing', 'completed'];
                        if ($order->status === 'cancelled') {
                            $statusOrder = ['pending', 'cancelled'];
                        }
                    @endphp
                    
                    @foreach($statusOrder as $index => $status)
                        @php
                            $isActive = array_search($order->status, $statusOrder) >= $index;
                            $isCurrent = $order->status === $status;
                        @endphp
                        <div class="relative flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isActive ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                @if($isCurrent && $order->status !== 'cancelled')
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                @elseif($isActive)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                @endif
                            </div>
            <div class="ml-4">
                <p class="font-medium {{ $isActive ? 'text-gray-900' : 'text-gray-500' }}">
                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                </p>
                @if($isCurrent)
                    <p class="text-sm text-gray-600">Status saat ini</p>
                @endif
            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Produk yang Dipesan</h2>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                            @if($item->product->image)
                                <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                @if($item->product->category)
                                    <p class="text-sm text-gray-600 mb-2">{{ $item->product->category->name }}</p>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        <p class="font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->payment)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Metode Pembayaran</p>
                            <p class="font-medium">{{ ucfirst($order->payment->payment_method) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Status Pembayaran</p>
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
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $paymentClasses[$order->payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $paymentLabels[$order->payment->status] ?? ucfirst($order->payment->status) }}
                            </span>
                        </div>
                        
                        @if($order->payment->paid_at)
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pembayaran</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($order->payment->paid_at)->format('d M Y, H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($order->payment->payment_reference)
                            <div>
                                <p class="text-sm text-gray-600">Referensi Pembayaran</p>
                                <p class="font-medium font-mono text-sm">{{ $order->payment->payment_reference }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal ({{ $order->orderItems->sum('quantity') }} item)</span>
                        <span class="font-medium">Rp {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Diskon</span>
                            <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shippingAddress || $order->shipping_address_snapshot)
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Alamat Pengiriman</h2>
                    
                    <div class="text-gray-700">
                        @if($order->shippingAddress)
                            <p class="font-medium">{{ $order->shippingAddress->name }}</p>
                            <p class="text-sm text-gray-600 mb-2">{{ $order->shippingAddress->phone }}</p>
                            <p>{{ $order->shippingAddress->address }}</p>
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}</p>
                            <p>{{ $order->shippingAddress->province }}</p>
                        @elseif($order->shipping_address_snapshot)
                            @php 
                                $snapshot = $order->shipping_address_snapshot; 
                            @endphp
                            <p class="font-medium">{{ $snapshot['name'] ?? 'Nama tidak tersedia' }}</p>
                            <p class="text-sm text-gray-600 mb-2">{{ $snapshot['phone'] ?? '' }}</p>
                            <p>{{ $snapshot['address'] ?? 'Alamat tidak tersedia' }}</p>
                            <p>{{ $snapshot['city'] ?? '' }}, {{ $snapshot['postal_code'] ?? '' }}</p>
                            @if(isset($snapshot['province']))
                                <p>{{ $snapshot['province'] }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Aksi</h2>
                
                <div class="space-y-3">
                    @if($order->status === 'pending' && $order->payment && $order->payment->status === 'pending')
                        <a href="{{ route('payment.retry', $order->id) }}" 
                           class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-md hover:bg-green-700 transition duration-300">
                            Bayar Sekarang
                        </a>
                        
                        <button onclick="checkPaymentStatus({{ $order->id }})" 
                                id="check-status-btn"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                            <span id="check-status-text">Refresh Status Pembayaran</span>
                            <span id="check-status-loading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Checking...
                            </span>
                        </button>
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
                    
                    <button onclick="window.print()" 
                            class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-300">
                        Cetak Invoice
                    </button>
                    
                    <a href="{{ route('orders.index') }}" 
                       class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-md hover:bg-blue-700 transition duration-300">
                        Lihat Semua Pesanan
                    </a>
                </div>
            </div>

            <!-- Help -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="font-semibold text-blue-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-blue-700 text-sm mb-3">
                    Jika Anda mengalami kendala dengan pesanan ini, silakan hubungi customer service kami.
                </p>
                <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Hubungi Customer Service
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal for Status Check -->
    <div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div id="status-modal-icon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                    <!-- Icon will be inserted here -->
                </div>
                <h3 id="status-modal-title" class="text-lg font-medium text-gray-900 mb-2"></h3>
                <div id="status-modal-message" class="mt-2 px-7 py-3">
                    <!-- Message will be inserted here -->
                </div>
                <div class="items-center px-4 py-3">
                    <button id="status-modal-close" 
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        async function checkPaymentStatus(orderId) {
            const btn = document.getElementById('check-status-btn');
            const textSpan = document.getElementById('check-status-text');
            const loadingSpan = document.getElementById('check-status-loading');
            
            // Show loading state
            btn.disabled = true;
            textSpan.classList.add('hidden');
            loadingSpan.classList.remove('hidden');
            
            try {
                const response = await fetch(`/payment/check-status/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    let message = '';
                    if (data.status_changed) {
                        message = `Status berhasil diperbarui!\n`;
                        message += `Payment: "${data.old_status}" → "${data.new_status}"\n`;
                        if (data.old_order_status !== data.new_order_status) {
                            message += `Order: "${data.old_order_status}" → "${data.new_order_status}"\n`;
                        }
                        message += `Halaman akan dimuat ulang dalam 2 detik.`;
                    } else {
                        message = `Status saat ini:\nPayment: ${data.new_status}\nOrder: ${data.new_order_status}\nTidak ada perubahan.`;
                    }
                    
                    showStatusModal(
                        'success',
                        'Status Berhasil Diperiksa!',
                        message
                    );
                    
                    if (data.status_changed) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    showStatusModal(
                        'error',
                        'Gagal Memeriksa Status',
                        data.error || 'Terjadi kesalahan saat memeriksa status pembayaran.'
                    );
                }
            } catch (error) {
                showStatusModal(
                    'error',
                    'Kesalahan Jaringan',
                    'Tidak dapat terhubung ke server. Silakan coba lagi.'
                );
            } finally {
                // Reset button state
                btn.disabled = false;
                textSpan.classList.remove('hidden');
                loadingSpan.classList.add('hidden');
            }
        }
        
        function showStatusModal(type, title, message) {
            const modal = document.getElementById('status-modal');
            const icon = document.getElementById('status-modal-icon');
            const titleEl = document.getElementById('status-modal-title');
            const messageEl = document.getElementById('status-modal-message');
            
            // Set icon and colors based on type
            if (type === 'success') {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4';
                icon.innerHTML = '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else {
                icon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4';
                icon.innerHTML = '<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            }
            
            titleEl.textContent = title;
            messageEl.innerHTML = message.replace(/\n/g, '<br>');
            modal.classList.remove('hidden');
        }
        
        // Close modal
        document.getElementById('status-modal-close').addEventListener('click', function() {
            document.getElementById('status-modal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('status-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
            }
            
            .container {
                max-width: none !important;
                padding: 0 !important;
            }
            
            .shadow-sm, .shadow {
                box-shadow: none !important;
            }
            
            .border {
                border: 1px solid #ccc !important;
            }
        }
    </style>
</x-app-layout>
