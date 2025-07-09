<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                        <p class="text-gray-600 mt-1">Review pesanan Anda sebelum melakukan pembayaran</p>
                    </div>
                    <a href="{{ route('cart.list') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Selected Products (Left) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Produk yang Dibeli ({{ $totalItems }} item)
                        </h3>
                        
                        @foreach($selectedItems as $item)
                        <div class="flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <!-- Product Image -->
                            <div class="shrink-0">
                                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-500 mb-2">
                                    Kategori: {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-bold text-red-600">{{ formatCurrency($item->product->price) }}</span>
                                        <span class="text-sm text-gray-500">x {{ $item->quantity }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            {{ formatCurrency($item->product->price * $item->quantity) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengiriman</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Estimasi Pengiriman</p>
                                    <p class="text-sm text-gray-600">3-5 hari kerja</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Ongkos Kirim</p>
                                    <p class="text-sm text-green-600 font-medium">Gratis Ongkir</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary (Right) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Pembayaran</h3>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $totalItems }} item)</span>
                                <span class="font-medium text-gray-900">{{ formatCurrency($subtotal) }}</span>
                            </div>
                            
                            @if($totalDiscount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="text-green-600 font-medium">-{{ formatCurrency($totalDiscount) }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="text-green-600 font-medium">
                                    {{ $shippingCost > 0 ? formatCurrency($shippingCost) : 'Gratis' }}
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between text-lg font-bold text-gray-900 py-4 border-t border-gray-200 mb-6">
                            <span>Total Pembayaran</span>
                            <span>{{ formatCurrency($total) }}</span>
                        </div>

                        <!-- Payment Button -->
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                            @csrf
                            @foreach($selectedItems as $item)
                            <input type="hidden" name="items[]" value="{{ $item->id }}">
                            @endforeach
                            
                            <button type="submit" id="payButton"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-sm">
                                <i class="fas fa-credit-card mr-2"></i> 
                                <span id="payButtonText">Bayar Sekarang</span>
                            </button>
                        </form>

                        <!-- Security Note -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-gray-600">Pembayaran aman dan terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkoutForm');
            const payButton = document.getElementById('payButton');
            const payButtonText = document.getElementById('payButtonText');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show confirmation
                const confirmed = confirm('Konfirmasi pembayaran sebesar {{ formatCurrency($total) }}?');
                
                if (confirmed) {
                    // Show loading state
                    payButton.disabled = true;
                    payButtonText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                    
                    // Submit form
                    form.submit();
                } else {
                    // Reset button state if cancelled
                    payButton.disabled = false;
                    payButtonText.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
                }
            });
        });
    </script>
</x-app-layout>
