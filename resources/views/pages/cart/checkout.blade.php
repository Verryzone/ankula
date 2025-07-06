<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">

                <!-- Product Preview (Left) -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg border shadow-sm p-6 flex items-start gap-6">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=150&h=150&fit=crop"
                            alt="PUMA Phase Backpack No. 2 Warm White" class="w-40 h-40 object-cover rounded-lg border">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">PUMA Phase Backpack No. 2</h2>
                            <p class="text-sm text-gray-600 mb-1">Color: <span class="font-medium">Warm White</span></p>
                            <p class="text-sm text-gray-600 mb-1">Size: <span class="font-medium">OSFA (One Size Fits
                                    All)</span></p>
                            <p class="text-sm text-gray-600 mt-2">Est. Delivery: <span class="text-green-600">3–5
                                    hari</span></p>
                        </div>
                    </div>
                </div>

                <!-- Checkout Details (Right) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-6 sticky top-4">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pembayaran</h3>

                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Harga Asli</span>
                            <span class="line-through text-gray-400">Rp949.050</span>
                        </div>

                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Diskon 30%</span>
                            <span class="text-green-600 font-medium">-Rp379.620</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold text-gray-900 py-4 border-t mt-4">
                            <span>Total</span>
                            <span>Rp569.430</span>
                        </div>

                        <button
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 mt-6 rounded-lg transition duration-300">
                            <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                        </button>

                        <a href="{{ route('cart.list') }}"
                            class="block text-center text-sm text-indigo-600 hover:underline mt-4">
                            ← Kembali ke Keranjang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
