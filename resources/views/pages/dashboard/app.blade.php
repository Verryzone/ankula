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

        <section class="flex justify-around items-start text-center text-sm font-medium text-gray-700">
            <a href="#" class="flex flex-col items-center space-y-2">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2zM7 13h10M7 17h5"></path></svg>
                </div>
                <span>Deals & Outlet</span>
            </a>
            <a href="#" class="flex flex-col items-center space-y-2">
                 <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"></path></svg>
                </div>
                <span class="text-blue-600 font-bold">Holiday Deals</span>
            </a>
            <a href="#" class="flex flex-col items-center space-y-2">
                 <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <span>Gift Ideas</span>
            </a>
            <a href="#" class="flex flex-col items-center space-y-2">
                 <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <span>Weekly Deals</span>
            </a>
            <a href="#" class="flex flex-col items-center space-y-2">
                 <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span>Membership</span>
            </a>
        </section>
        
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Today's Featured Deals</h2>
                <a href="#" class="text-blue-600 font-semibold hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/10.jpg') }}" alt="Smartwatch" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Smart Watch Series 7</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$299.00</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/2.jpg') }}" alt="Headphone" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Noise Cancelling Headphone</h3>
                    <div class="text-yellow-400 my-1">★★★★★</div>
                    <div class="font-bold text-lg text-gray-900">$189.99</div>
                </div>
                 <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/6.jpg') }}" alt="Phone" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Iphone</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$799.00</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/12.jpg') }}" alt="Smartwatch" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Sport Band Smart Watch</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$249.00</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/1.jpg') }}" alt="Display" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">BTS T-shirt</h3>
                    <div class="text-yellow-400 my-1">★★★★★</div>
                    <div class="font-bold text-lg text-gray-900">$350.99</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                    <img src="{{ asset('img/7.jpg') }}" alt="Phone" class="h-32 mx-auto mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Latest Smartphone 128GB</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$999.00</div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gray-100 rounded-lg p-6 flex items-center justify-between col-span-1 lg:col-span-2">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Clear Choice Price</h3>
                    <p class="text-gray-600">Earn 20% Back in Rewards</p>
                    <button class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg mt-4 hover:bg-blue-700">Shop Now</button>
                </div>
                <img src="{{ asset('img/2.jpg') }}" alt="Speaker" class="w-48 h-auto">
            </div>
            <div class="bg-gray-100 rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Charge Your Devices</h3>
                    <p class="text-gray-600">Starting from <span class="font-bold">$29.99</span></p>
                </div>
                <img src="{{ asset('img/13.jpg') }}" alt="Powerbank" class="w-24 h-auto">
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Trending This Week</h2>
                <div class="flex space-x-4 text-sm font-semibold">
                    <a href="#" class="text-blue-600 border-b-2 border-blue-600 pb-1">New</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600">Featured</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600">Best Rated</a>
                    <a href="#" class="text-gray-500 hover:text-blue-600">On Sale</a>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 grid-rows-2 gap-6 h-[40rem]">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <img src="{{ asset('img/5.jpg') }}" alt="Phone case" class="w-full h-32 object-contain mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Puma Sport</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$99.99</div>
                </div>
                
                <div class="col-span-2 row-span-2 bg-white border border-gray-200 rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/14.jpg') }}" alt="Airpods Max Pink" class="w-2/3 object-contain">
                    <h3 class="font-bold text-xl text-gray-800 mt-4">Airpods Max Pro Hi-Fi TWS</h3>
                     <div class="text-yellow-400 my-2">★★★★★</div>
                    <div class="font-bold text-3xl text-gray-900">$549.00</div>
                    <div class="flex space-x-2 mt-4">
                        <span class="w-6 h-6 bg-pink-300 rounded-full border-2 border-blue-500"></span>
                        <span class="w-6 h-6 bg-gray-300 rounded-full"></span>
                        <span class="w-6 h-6 bg-blue-300 rounded-full"></span>
                        <span class="w-6 h-6 bg-green-300 rounded-full"></span>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <img src="{{ asset('img/4.jpg') }}" alt="Watch" class="w-full h-32 object-contain mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Rolex</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$1,279.00</div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <img src="{{ asset('img/11.jpg') }}" alt="Microphone" class="w-full h-32 object-contain mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Nike r4</h3>
                    <div class="text-yellow-400 my-1">★★★★★</div>
                    <div class="font-bold text-lg text-gray-900">$120.00</div>
                </div>

                 <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <img src="{{ asset('img/15.jpg') }}" alt="Wireless Charger" class="w-full h-32 object-contain mb-2">
                    <h3 class="font-semibold text-sm text-gray-800">Rolex</h3>
                    <div class="text-yellow-400 my-1">★★★★☆</div>
                    <div class="font-bold text-lg text-gray-900">$1,149.99</div>
                </div>
            </div>
        </section>

    </div>
    </div>
            </div>
    </div>
</x-app-layout>
