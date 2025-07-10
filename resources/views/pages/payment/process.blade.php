<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Payment Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pembayaran</h1>
                        <p class="text-gray-600 mt-1">Selesaikan pembayaran untuk order #{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ formatCurrency($order->total_amount + $order->shipping_cost) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Container -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Silakan Lakukan Pembayaran</h3>
                    <p class="text-gray-600 mb-6">Pilih metode pembayaran yang tersedia di bawah ini</p>
                    
                    <!-- Midtrans Snap Payment -->
                    <div id="snap-container" class="mb-6">
                        <button id="pay-button" 
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-300 shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Pesanan</h4>
                        <div class="space-y-2 text-sm">
                            @foreach($order->orderItems as $item)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $item->product->name }} ({{ $item->quantity }}x)</span>
                                <span class="text-gray-900">{{ formatCurrency($item->price * $item->quantity) }}</span>
                            </div>
                            @endforeach
                            
                            @if($order->shipping_cost > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="text-gray-900">{{ formatCurrency($order->shipping_cost) }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between font-semibold text-base border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span>{{ formatCurrency($order->total_amount + $order->shipping_cost) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Pembayaran aman dan terenkripsi dengan Midtrans</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Midtrans Snap JavaScript -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey', env('MIDTRANS_CLIENT_KEY')) }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    console.log('Payment success:', result);
                    // Redirect to success page
                    window.location.href = '{{ route("payment.success") }}?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
                },
                onPending: function(result){
                    console.log('Payment pending:', result);
                    // You can redirect to pending page or show message
                    alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                },
                onError: function(result){
                    console.log('Payment error:', result);
                    // Redirect to failure page
                    window.location.href = '{{ route("payment.failed") }}?order_id={{ $order->order_number }}';
                },
                onClose: function(){
                    console.log('Payment popup closed');
                    alert('Pembayaran dibatalkan. Silakan lakukan pembayaran untuk menyelesaikan pesanan Anda.');
                }
            });
        };
    </script>
</x-app-layout>
