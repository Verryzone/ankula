<x-app-layout>
    <div class="max-w-screen-xl mx-auto p-4">
        <div class="space-y-12 py-8">

            @if($highlights->count() > 0)
                @foreach($highlights as $highlight)
                    <section class="bg-blue-800 text-white rounded-lg overflow-hidden">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="p-8 md:p-16 w-full md:w-1/2 space-y-4">
                                <h1 class="text-4xl md:text-5xl font-bold leading-tight">{{ $highlight->title }}</h1>
                                @if($highlight->description)
                                    <p class="text-lg text-blue-200">{{ $highlight->description }}</p>
                                @endif
                                @if($highlight->price)
                                    <p class="text-3xl font-bold">Rp {{ number_format($highlight->price, 0, ',', '.') }}</p>
                                @endif
                                @if($highlight->button_link)
                                    <a href="{{ $highlight->button_link }}"
                                       class="inline-block bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
                                        {{ $highlight->button_text }}
                                    </a>
                                @else
                                    <button
                                        class="bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
                                        {{ $highlight->button_text }}
                                    </button>
                                @endif
                            </div>
                            <div class="relative w-full md:w-1/2 min-h-[30rem] flex items-center justify-center">
                                @if($highlight->image_path)
                                    <img src="{{ asset('storage/' . $highlight->image_path) }}" alt="{{ $highlight->title }}"
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform rotate-6 z-10 transition-transform duration-300 hover:rotate-0 ">
                                    <img src="{{ asset('storage/' . $highlight->image_path) }}" alt="{{ $highlight->title }}"
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform -rotate-6 transition-transform duration-300 hover:rotate-0 ">
                                @else
                                    <img src="{{ asset('img/kemeja.jpg') }}" alt="Default Product"
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform rotate-6 z-10 transition-transform duration-300 hover:rotate-0 ">
                                    <img src="{{ asset('img/kemeja.jpg') }}" alt="Default Product"
                                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform -rotate-6 transition-transform duration-300 hover:rotate-0 ">
                                @endif
                            </div>
                        </div>
                    </section>
                @endforeach
            @else
                <!-- Default highlight if no highlights are configured -->
                <section class="bg-blue-800 text-white rounded-lg overflow-hidden">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="p-8 md:p-16 w-full md:w-1/2 space-y-4">
                            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Bring Happiness From Shopping
                                Everyday</h1>
                            <p class="text-lg text-blue-200">Find the perfect product for your needs.</p>
                            <p class="text-3xl font-bold">$449.99</p>
                            <button
                                class="bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
                                Shop Now
                            </button>
                        </div>
                        <div class="relative w-full md:w-1/2 min-h-[30rem] flex items-center justify-center">
                            <img src="{{ asset('img/kemeja.jpg') }}" alt="Kemeja Hitam"
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform rotate-6 z-10 transition-transform duration-300 hover:rotate-0 ">
                            <img src="{{ asset('img/kemeja.jpg') }}" alt="Kemeja Putih"
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 md:w-80 object-contain rounded-lg shadow-lg transform -rotate-6 transition-transform duration-300 hover:rotate-0 ">
                        </div>
                    </div>
                </section>
            @endif

                    {{--   --}}

                    <section>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Today's Featured Deals</h2>
                            <a href="#" class="text-blue-600 font-semibold hover:underline">View All</a>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                            <!-- 1 -->
                            @foreach ($products as $product)
                                <div
                                    class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group cursor-pointer"
                                    onclick="showProductDetail({{ $product->id }})">
                                    <div class="relative bg-gray-100 aspect-square">
                                        <div
                                            class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">
                                            30% OFF
                                        </div>
                                        <img src="{{ asset("storage/products/images/{$product->image}") }}" alt="Sweater"
                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </div>
                                    <div class="p-4">
                                        <p class="text-xs text-gray-500">{{ $product->name }}</p>
                                        <h3 class="font-semibold text-gray-800 text-sm mt-1">{{ $product->description }}
                                        </h3>
                                        <div class="text-yellow-400 my-2 text-xs">★★★★☆ <span
                                                class="text-gray-400">(18)</span></div>
                                        <div class="font-bold text-lg text-gray-900">{{ formatCurrency($product->price) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </section>

                    @if($contents->count() > 0)
                        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($contents as $content)
                                @php
                                    $colSpan = match($content->size) {
                                        'large' => 'col-span-1 lg:col-span-2',
                                        'medium' => 'col-span-1',
                                        'small' => 'col-span-1',
                                        default => 'col-span-1'
                                    };
                                @endphp
                                <div class="rounded-lg p-6 flex items-center justify-between {{ $colSpan }}" 
                                     style="background-color: {{ $content->background_color }}; color: {{ $content->text_color }};">
                                    <div>
                                        <h3 class="text-xl font-bold mb-2">{{ $content->title }}</h3>
                                        @if($content->subtitle)
                                            <p class="mb-2 opacity-80">{{ $content->subtitle }}</p>
                                        @endif
                                        @if($content->description)
                                            <p class="mb-2 opacity-70 text-sm">{{ $content->description }}</p>
                                        @endif
                                        @if($content->price_display)
                                            <p class="mb-4 opacity-90">{{ $content->price_display }}</p>
                                        @endif
                                        @if($content->button_link)
                                            <a href="{{ $content->button_link }}"
                                               class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors">
                                                {{ $content->button_text }}
                                            </a>
                                        @else
                                            <button class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors">
                                                {{ $content->button_text }}
                                            </button>
                                        @endif
                                    </div>
                                    @if($content->image_path)
                                        <img src="{{ asset('storage/' . $content->image_path) }}" 
                                             alt="{{ $content->title }}" 
                                             class="{{ $content->size === 'large' ? 'w-48' : 'w-24' }} h-auto">
                                    @else
                                        <!-- Default images based on content type -->
                                        @if($content->type === 'promo' || $content->title === 'Clear Choice Price')
                                            <img src="{{ asset('img/2.jpg') }}" alt="{{ $content->title }}" class="{{ $content->size === 'large' ? 'w-48' : 'w-24' }} h-auto">
                                        @elseif($content->type === 'category' || $content->title === 'Charge Your Devices')
                                            <img src="{{ asset('img/13.jpg') }}" alt="{{ $content->title }}" class="{{ $content->size === 'large' ? 'w-48' : 'w-24' }} h-auto">
                                        @else
                                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </section>
                    @else
                        <!-- Default content sections if no content is configured -->
                        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-white rounded-lg p-6 flex items-center justify-between col-span-1 lg:col-span-2">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Clear Choice Price</h3>
                                    <p class="text-gray-600">Earn 20% Back in Rewards</p>
                                    <button
                                        class="bg-blue-600 text-white font-semibold py-2 px-5 rounded-lg mt-4 hover:bg-blue-700">Shop
                                        Now</button>
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
                    @endif

                    <section>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Fashion Products</h2>
                            <div class="flex space-x-4 text-sm font-semibold">
                                <a href="#" class="text-green-600 border-b-2 border-green-600 pb-1">New
                                    Arrival</a>
                                <a href="#" class="text-gray-500 hover:text-green-600">Best Seller</a>
                                <a href="#" class="text-gray-500 hover:text-green-600">Top Rated</a>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                            <div
                                class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 group">
                                <div class="relative bg-gray-100 aspect-square">
                                    <div
                                        class="absolute top-3 left-3 bg-orange-400 text-white text-xs font-bold px-2 py-1 rounded-md z-10">
                                        SALE
                                    </div>
                                    <img src="{{ asset('img/15.jpg') }}" alt="Backpack"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-500">Backpacks</p>
                                    <h3 class="font-semibold text-gray-800 text-sm mt-1">Tan Solid Laptop Backpack</h3>
                                    <div class="text-yellow-400 my-2 text-xs">★★★★★ <span
                                            class="text-gray-400">(25)</span></div>
                                    <div class="font-bold text-lg text-gray-900">$48.00 - $95.00</div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-2 sm:p-4">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 sm:p-6 border-b bg-white sticky top-0 z-10">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Detail Produk</h2>
                <button onclick="productDetailModal.closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold p-1">
                    &times;
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
                        <!-- Product Image -->
                        <div class="space-y-4 order-1 lg:order-1">
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                <img id="modalProductImage" src="" alt="Product Image" 
                                     class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="space-y-4 sm:space-y-6 order-2 lg:order-2">
                            <div>
                                <p id="modalProductCategory" class="text-sm text-gray-500 mb-2"></p>
                                <h1 id="modalProductName" class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-2"></h1>
                                <div class="text-yellow-400 mb-4 text-sm">★★★★☆ <span class="text-gray-400">(18 reviews)</span></div>
                            </div>
                            
                            <div>
                                <p id="modalProductPrice" class="text-2xl sm:text-3xl font-bold text-blue-600 mb-4"></p>
                                <div class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full inline-block mb-4">
                                    <span id="modalProductStock"></span> tersedia
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold mb-2">Deskripsi</h3>
                                <p id="modalProductDescription" class="text-gray-600 leading-relaxed text-sm sm:text-base"></p>
                            </div>
                            
                            <!-- Quantity Selector (only for customers) -->
                            <div id="quantitySection">
                                <h3 class="text-base sm:text-lg font-semibold mb-2">Jumlah</h3>
                                <div class="flex items-center space-x-3">
                                    <button class="btn-quantity-decrease bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                                        -
                                    </button>
                                    <input id="quantity" type="number" value="1" min="1" class="w-12 sm:w-16 text-center border border-gray-300 rounded py-2 text-sm sm:text-base">
                                    <button class="btn-quantity-increase bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                                        +
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Action Buttons (dynamic based on user role) -->
                            <div id="actionButtons" class="space-y-3">
                                <!-- Buttons will be populated by JavaScript based on user role -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Custom CSS for Mobile Modal -->
    <style>
        /* Mobile Modal Enhancements */
        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
        }
        
        @media (max-width: 640px) {
            #productModal {
                padding: 0;
            }
            
            #productModal .bg-white {
                border-radius: 0;
                border-top-left-radius: 1rem;
                border-top-right-radius: 1rem;
            }
            
            /* Better touch targets for mobile */
            .btn-quantity-decrease,
            .btn-quantity-increase {
                min-height: 44px;
                min-width: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Full width buttons on mobile */
            #actionButtons button {
                width: 100%;
                padding: 12px 16px;
                font-size: 16px;
            }
        }
        
        /* Ensure modal content is scrollable */
        #productModal .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Better close button visibility */
        #productModal button[onclick*="closeModal"] {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    <script>
        // Configure axios defaults
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Pass user data to JavaScript
        window.userRole = '{{ auth()->check() ? auth()->user()->role : "guest" }}';
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Load admin functions for product management -->
        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="{{ asset('api/private-axios.js') }}"></script>
    @endif
    
    <script src="{{ asset('js/product-detail.js') }}"></script>
</x-app-layout>
