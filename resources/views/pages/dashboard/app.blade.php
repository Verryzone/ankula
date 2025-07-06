<x-app-layout>
    <div class="max-w-screen-xl mx-auto flex min-h-screen">
        <div class="px-4 py-2">
            <div class="w-full">
    <div class="space-y-12 py-8">
        
        <section class="bg-blue-800 text-white rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center">
                <div class="p-8 md:p-16 w-full md:w-1/2 space-y-4">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">Bring Happiness From Shopping Everyday</h1>
                    <p class="text-lg text-blue-200">Find the perfect product for your needs.</p>
                    <p class="text-3xl font-bold">$449.99</p>
                    <button class="bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
                        Shop Now
                    </button>
                </div>
                <div class="relative w-full md:w-1/2 min-h-[30rem] flex items-center justify-center">
                    <img src="{{ asset('img/kemeja.jpg') }}" alt="Kemeja Hitam" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform rotate-6 z-10 transition-transform duration-300 hover:rotate-0">
                    <img src="{{ asset('img/1.jpg') }}" alt="Kemeja Putih" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform -rotate-6 transition-transform duration-300 hover:rotate-0">
                </div>
            </div>
        </section>

        {{--   --}}
        
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Today's Featured Deals</h2>
                <a href="#" class="text-blue-600 font-semibold hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">30% OFF</div>
                        <img src="{{ asset('img/1.jpg') }}" alt="Sweater" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Knitwear</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Maroon Solid Knit Sweater</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★☆ <span class="text-gray-400">(18)</span></div>
                        <div class="font-bold text-lg text-gray-900">$65.00</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <img src="{{ asset('img/sunglasses.png') }}" alt="Sunglasses" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Accessories</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Classic Aviator Sunglasses</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★★ <span class="text-gray-400">(55)</span></div>
                        <div class="font-bold text-lg text-gray-900">$120.00</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <img src="{{ asset('img/dress.png') }}" alt="Dress" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Dresses</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Green Floral Print A-Line Dress</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★☆ <span class="text-gray-400">(21)</span></div>
                        <div class="font-bold text-lg text-gray-900">$88.00</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">50% OFF</div>
                        <img src="{{ asset('img/handbag.png') }}" alt="Handbag" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Bags & Purses</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Elegant Leather Handbag</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★★ <span class="text-gray-400">(43)</span></div>
                        <div class="font-bold text-lg text-gray-900">$150.00</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <img src="{{ asset('img/hat.png') }}" alt="Hat" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Hats & Caps</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Classic Fedora Hat</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★☆ <span class="text-gray-400">(30)</span></div>
                        <div class="font-bold text-lg text-gray-900">$35.00</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <img src="{{ asset('img/jeans.png') }}" alt="Jeans" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Denim</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Slim-Fit Washed Jeans</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★★ <span class="text-gray-400">(89)</span></div>
                        <div class="font-bold text-lg text-gray-900">$98.00</div>
                    </div>
                </div>

            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6 flex items-center justify-between col-span-1 lg:col-span-2">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Clear Choice Price</h3>
                    <p class="text-gray-600">Earn 20% Back in Rewards</p>
                    <button class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg mt-4 hover:bg-blue-700">Shop Now</button>
                </div>
                <img src="{{ asset('img/2.jpg') }}" alt="Speaker" class="w-48 h-auto">
            </div>
            <div class="bg-white rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Charge Your Devices</h3>
                    <p class="text-gray-600">Starting from <span class="font-bold">$29.99</span></p>
                </div>
                <img src="{{ asset('img/13.jpg') }}" alt="Powerbank" class="w-24 h-auto">
            </div>
        </section>
        
        <section>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Fashion Products</h2>
                <div class="flex space-x-4 text-sm font-semibold">
                    <a href="#" class="text-green-600 border-b-2 border-green-600 pb-1">New Arrival</a>
                    <a href="#" class="text-gray-500 hover:text-green-600">Best Seller</a>
                    <a href="#" class="text-gray-500 hover:text-green-600">Top Rated</a>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                    <div class="relative bg-gray-100 aspect-square flex items-center justify-center">
                        <div class="absolute top-3 left-3 bg-orange-400 text-white text-xs font-bold px-2 py-1 rounded-md z-10">SALE</div>
                        <img src="{{ asset('img/backpack.png') }}" alt="Backpack" class="h-4/5 object-contain transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-500">Backpacks</p>
                        <h3 class="font-semibold text-gray-800 text-sm mt-1">Tan Solid Laptop Backpack</h3>
                        <div class="text-yellow-400 my-2 text-xs">★★★★★ <span class="text-gray-400">(25)</span></div>
                        <div class="font-bold text-lg text-gray-900">$48.00 - $95.00</div>
                    </div>
                </div>
                </div>
        </section>

    </div>
    </div>
        </div>
    </div>
</x-app-layout>