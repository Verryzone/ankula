<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8 text-center">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <!-- Success Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-lg text-gray-600 mb-6">Terima kasih! Pesanan Anda telah berhasil diproses.</p>

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
                            <span class="text-gray-600">Status Pembayaran:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ isset($transactionStatus) ? ucfirst($transactionStatus) : 'Success' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pesanan:</span>
                            <span class="font-medium text-gray-900">{{ $order->created_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- What's Next -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h4 class="text-base font-semibold text-blue-900 mb-2">Apa Selanjutnya?</h4>
                    <ul class="text-sm text-blue-800 space-y-1 text-left">
                        <li>• Kami akan memproses pesanan Anda dalam 1-2 hari kerja</li>
                        <li>• Anda akan menerima email konfirmasi dan nomor resi</li>
                        <li>• Estimasi pengiriman: 3-5 hari kerja</li>
                        <li>• Silakan pantau status pesanan di halaman profil Anda</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                    
                    <a href="{{ route('orders.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Pesanan Saya
                    </a>
                </div>

                <!-- Support Contact -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Butuh bantuan? Hubungi customer service kami di 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">support@ankulaa.com</a>
                        atau WhatsApp 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">0812-3456-7890</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Success Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add a subtle animation to the success icon
            const successIcon = document.querySelector('.w-20.h-20');
            if (successIcon) {
                successIcon.style.transform = 'scale(0)';
                successIcon.style.transition = 'transform 0.5s ease-out';
                
                setTimeout(() => {
                    successIcon.style.transform = 'scale(1)';
                }, 100);
            }
        });
    </script>
</x-app-layout>
