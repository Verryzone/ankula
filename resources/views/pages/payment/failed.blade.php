<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Failed Message -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 text-center">
                <!-- Failed Icon -->
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <!-- Failed Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Gagal</h1>
                <p class="text-lg text-gray-600 mb-6">Maaf, terjadi kesalahan saat memproses pembayaran Anda.</p>

                <!-- Order Details -->
                @if(isset($order) && $order)
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Pesanan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor Pesanan:</span>
                            <span class="font-medium text-gray-900">#{{ $order->order_number }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Pembayaran:</span>
                            <span class="font-medium text-gray-900">{{ formatCurrency($order->total_amount + $order->shipping_cost) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Pembayaran Gagal
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pesanan:</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Possible Reasons -->
                <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                    <h4 class="text-base font-semibold text-yellow-900 mb-2">Kemungkinan Penyebab:</h4>
                    <ul class="text-sm text-yellow-800 space-y-1 text-left">
                        <li>• Saldo atau limit kartu kredit tidak mencukupi</li>
                        <li>• Informasi kartu yang dimasukkan tidak valid</li>
                        <li>• Koneksi internet terputus saat proses pembayaran</li>
                        <li>• Pembayaran dibatalkan atau waktu habis</li>
                        <li>• Gangguan sementara pada sistem pembayaran</li>
                    </ul>
                </div>

                <!-- What to Do Next -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h4 class="text-base font-semibold text-blue-900 mb-2">Apa yang Harus Dilakukan?</h4>
                    <ul class="text-sm text-blue-800 space-y-1 text-left">
                        <li>• Periksa kembali informasi pembayaran Anda</li>
                        <li>• Pastikan saldo atau limit kartu mencukupi</li>
                        <li>• Coba gunakan metode pembayaran lain</li>
                        <li>• Hubungi bank atau penerbit kartu jika perlu</li>
                        <li>• Ulangi proses pembayaran</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(isset($order) && $order)
                    <a href="{{ route('checkout', ['items' => $order->orderItems->pluck('id')->toArray()]) }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Coba Bayar Lagi
                    </a>
                    @endif
                    
                    <a href="{{ route('cart.list') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5-5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        Lihat Keranjang
                    </a>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Support Contact -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Masih mengalami masalah? Hubungi customer service kami di 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">support@ankulaa.com</a>
                        atau WhatsApp 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">0812-3456-7890</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
