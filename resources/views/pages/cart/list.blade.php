<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-2 lg:px-4 space-y-6">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Cart Items Section -->
                <div class="lg:col-span-3">
                    <!-- Select All Header -->
                    {{-- <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-5 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="select-all"
                                        class="w-5 h-5 text-blue-700 bg-white border-2 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-3 font-semibold text-gray-900">Pilih Semua</span>
                                </label>
                                <span class="text-gray-500 text-sm">(1)</span>
                            </div>
                            <button class="text-red-500 hover:text-red-600 font-medium text-sm transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div> --}}

                    <!-- Store Section -->
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100 overflow-hidden">
                        <!-- Store Header -->
                        {{-- <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="select-store"
                                        class="w-5 h-5 text-primary bg-white border-2 border-gray-300 rounded-sm focus:focus-ring-primary focus:ring-2 ">
                                    <div class="ml-3 flex items-center gap-3">
                                        <div
                                            class="w-7 h-7 bg-linear-to-br from-purple-600 to-purple-700 rounded-lg flex items-center justify-center shadow-xs">
                                            <i class="fas fa-store text-white text-xs"></i>
                                        </div>
                                        <span class="font-semibold text-gray-900">PUMA Indonesia</span>
                                    </div>
                                </label>
                            </div>
                        </div> --}}

                        <!-- Product Item -->
                        <div class="p-5 my-2">
                            <div class="flex items-center gap-4">
                                <!-- Checkbox -->
                                <div class="shrink-0 pt-1">
                                    <input type="checkbox" id="select-item"
                                        class="w-5 h-5 text-blue-700 bg-white border-2 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                </div>

                                <!-- Product Image -->
                                <div class="shrink-0 relative">
                                    <div class="w-30 h-30 rounded-lg overflow-hidden border border-gray-200 shadow-xs">
                                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=150&h=150&fit=crop"
                                            alt="PUMA Sneakers" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-bl-lg">
                                          Sisa : 12
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-medium text-gray-900 text-sm leading-relaxed pr-2">
                                            [HOT PRODUCT] PUMA PUMA Shuffle Trainers Warm White-White-Gold
                                        </h3>
                                    </div>

                                    <p class="text-sm text-gray-500 mb-3">Size: 36</p>

                                    <!-- Price -->
                                    <div class="flex items-baseline gap-2 mb-4">
                                        <span class="text-2xl font-bold text-red-600">Rp569.430</span>
                                        <span class="text-sm text-gray-400 line-through">Rp949.050</span>
                                    </div>

                                    <!-- Actions Row -->
                                    <div class="flex items-center justify-between">
                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-2">
                                            <button class="p-2.5 hover:bg-gray-100 rounded-lg transition-colors group">
                                                <svg class="w-6 h-6 text-red-500 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                                                </svg>
                                            </button>
                                            <button class="p-2.5 hover:bg-red-500 rounded-lg transition-colors group">
                                                <svg class="w-6 h-6 text-gray-400 hover:text-white dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd"
                                                        d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                                        clip-rule="evenodd" />
                                                </svg>

                                            </button>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button
                                                class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                                -
                                            </button>
                                            <input type="text" id="quantity" value="1" min="1" class="w-16 rounded-lg">
                                            <button
                                                class="py-2.5 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                                +
                                            </button>
                                        </div>
                                        <!-- Quantity Controls -->
                                        {{-- <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-xs">
                                            <button class="p-2.5 hover:bg-gray-50 transition-colors rounded-l-lg" 
                                                    onclick="decreaseQuantity()">
                                                <i class="fas fa-minus text-gray-400 text-xs"></i>
                                            </button>
                                            <input type="number" id="quantity" value="1" min="1" 
                                                   class="w-10 text-center border-0 focus:ring-0 py-2.5 text-sm font-semibold bg-transparent" 
                                                   readonly>
                                            <button class="p-2.5 hover:bg-gray-50 transition-colors rounded-r-lg" 
                                                    onclick="increaseQuantity()">
                                                <i class="fas fa-plus text-gray-400 text-xs"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-6 sticky top-4">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan belanja</h3>

                        <!-- Total -->
                        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Total</span>
                            <span class="text-3xl font-bold text-gray-900">Rp569.430.000</span>
                        </div>

                        <!-- Promo Section -->
                        {{-- <div class="bg-linear-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-lg p-4 mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-100 rounded-full -mr-10 -mt-10 opacity-50"></div>
                            <div class="relative flex items-start gap-3">
                                <div class="w-8 h-8 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-xs shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm mb-1">2 kupon promo berhasil dipakai</p>
                                    <p class="text-emerald-700 text-sm leading-relaxed">
                                        Dapat diskon <span class="font-bold">Rp379.620</span> & cashback 
                                        <span class="font-bold">25.000</span> 🎉
                                    </p>
                                </div>
                                <i class="fas fa-chevron-right text-emerald-400 text-sm mt-1"></i>
                            </div>
                        </div> --}}

                        <!-- Buy Button -->
                        <button
                            class="w-full bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg shadow-lg focus:outline-hidden focus:ring-2 focus:ring-primary-500 transition-all ease-in-out hover:scale-105"
                            onclick="handlePurchase()">
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart text-sm"></i>
                                Beli
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
