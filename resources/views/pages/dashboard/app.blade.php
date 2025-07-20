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
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Today's Featured Deals</h2>
                            <a href="#" class="text-blue-600 font-semibold hover:underline flex items-center space-x-1">
                                <span>View All</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                            @foreach ($products as $product)
                                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1"
                                     data-product-id="{{ $product->id }}">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        {{-- <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            30% OFF
                                        </div> --}}
                                        <img src="{{ asset("storage/products/images/{$product->image}") }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                        <!-- Quick view button on hover -->
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                            <button class="quick-view-icon bg-white text-blue-600 font-semibold p-3 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 hover:bg-gray-50">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-3 space-y-1">
                                        <p class="text-xs text-blue-600 font-medium">{{ $product->name }}</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ limitText($product->description, 35) }}
                                        </h3>
                                        <div class="flex items-center space-x-1 my-1">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= 4)
                                                        <span>★</span>
                                                    @else
                                                        <span class="text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-gray-400 text-xs">(18)</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="font-bold text-base text-gray-900">
                                                {{ formatCurrency($product->price) }}
                                            </div>
                                            <div class="text-xs text-gray-500 line-through">
                                                {{ formatCurrency($product->price * 1.43) }}
                                            </div>
                                        </div>
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
                                <a href="#" class="tab-filter text-green-600 border-b-2 border-green-600 pb-1" data-filter="all">All</a>
                                <a href="#" class="tab-filter text-gray-500 hover:text-green-600 pb-1" data-filter="new-arrival">New
                                    Arrival</a>
                                <a href="#" class="tab-filter text-gray-500 hover:text-green-600 pb-1" data-filter="best-seller">Best Seller</a>
                                <a href="#" class="tab-filter text-gray-500 hover:text-green-600 pb-1" data-filter="top-rated">Top Rated</a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="fashion-products-grid">
                            <!-- Fashion Product Cards -->
                            @if($products->count() > 0)
                                @foreach($products->take(5) as $product)
                                    @php
                                        // Determine product category dynamically
                                        $productCategory = 'Fashion';
                                        if ($product->category_id) {
                                            $category = \App\Models\Category::find($product->category_id);
                                            $productCategory = $category ? $category->name : 'Fashion';
                                        } elseif ($product->category) {
                                            $productCategory = $product->category->name;
                                        }
                                        
                                        // Assign random product types for demonstration
                                        $productTypes = ['new-arrival', 'best-seller', 'top-rated'];
                                        $randomType = $productTypes[($loop->index) % 3];
                                        
                                        // Generate random ratings
                                        $rating = rand(3, 5);
                                        $reviewCount = rand(15, 150);
                                    @endphp
                                    <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" 
                                         data-category="{{ strtolower(str_replace(' ', '-', $productCategory)) }}" 
                                         data-type="{{ $randomType }}"
                                         data-product-id="{{ $product->id }}">
                                        <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                            @if($loop->first || $randomType === 'best-seller')
                                                <div class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                                    {{ $randomType === 'best-seller' ? 'BEST' : 'SALE' }}
                                                </div>
                                            @elseif($randomType === 'new-arrival')
                                                <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                                    NEW
                                                </div>
                                            @elseif($randomType === 'top-rated')
                                                <div class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                                    ⭐ TOP
                                                </div>
                                            @endif
                                            <img src="{{ asset("storage/products/images/{$product->image}") }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                        </div>
                                        <div class="p-4 space-y-2">
                                            <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">{{ $productCategory }}</p>
                                            <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                {{ limitText($product->name, 40) }}
                                            </h3>
                                            <div class="flex items-center space-x-1 my-2">
                                                <div class="flex text-yellow-400 text-xs">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $rating)
                                                            <span>★</span>
                                                        @else
                                                            <span class="text-gray-300">★</span>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-gray-400 text-xs">({{ $reviewCount }})</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="font-bold text-lg text-gray-900">
                                                    {{ formatCurrency($product->price) }}
                                                </div>
                                                @if($randomType === 'best-seller' || $loop->first)
                                                    <div class="text-xs text-gray-500 line-through">
                                                        {{ formatCurrency($product->price * 1.3) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default Fashion Cards with different categories -->
                                <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" data-category="bags" data-type="best-seller" data-product-id="demo-1">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        <div class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            BEST
                                        </div>
                                        <img src="{{ asset('img/15.jpg') }}" alt="Backpack"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Bags</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">
                                            Tan Solid Laptop Backpack
                                        </h3>
                                        <div class="flex items-center space-x-1 my-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                <span>★★★★★</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">(125)</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="font-bold text-lg text-gray-900">$48.00</div>
                                            <div class="text-xs text-gray-500 line-through">$65.00</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" data-category="clothing" data-type="new-arrival" data-product-id="demo-2">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            NEW
                                        </div>
                                        <img src="{{ asset('img/kemeja.jpg') }}" alt="Shirt"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Clothing</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">
                                            Premium Cotton Shirt
                                        </h3>
                                        <div class="flex items-center space-x-1 my-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                <span>★★★★☆</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">(18)</span>
                                        </div>
                                        <div class="font-bold text-lg text-gray-900">$35.00</div>
                                    </div>
                                </div>

                                <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" data-category="accessories" data-type="top-rated" data-product-id="demo-3">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        <div class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            ⭐ TOP
                                        </div>
                                        <img src="{{ asset('img/2.jpg') }}" alt="Accessories"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Accessories</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">
                                            Fashion Watch
                                        </h3>
                                        <div class="flex items-center space-x-1 my-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                <span>★★★★★</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">(89)</span>
                                        </div>
                                        <div class="font-bold text-lg text-gray-900">$89.99</div>
                                    </div>
                                </div>

                                <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" data-category="electronics" data-type="best-seller" data-product-id="demo-4">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        <div class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            BEST
                                        </div>
                                        <img src="{{ asset('img/13.jpg') }}" alt="Electronics"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Electronics</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">
                                            Wireless Headphones
                                        </h3>
                                        <div class="flex items-center space-x-1 my-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                <span>★★★★☆</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">(67)</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="font-bold text-lg text-gray-900">$59.99</div>
                                            <div class="text-xs text-gray-500 line-through">$79.99</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-card bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer transform hover:-translate-y-1" data-category="smartphones" data-type="new-arrival" data-product-id="demo-5">
                                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 aspect-square overflow-hidden">
                                        <div class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-md">
                                            NEW
                                        </div>
                                        <img src="{{ asset('img/2.jpg') }}" alt="Smartphone"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                    </div>
                                    <div class="p-4 space-y-2">
                                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide">Smartphones</p>
                                        <h3 class="font-bold text-gray-800 text-sm leading-tight group-hover:text-blue-600 transition-colors">
                                            Latest Smartphone Pro
                                        </h3>
                                        <div class="flex items-center space-x-1 my-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                <span>★★★★★</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">(156)</span>
                                        </div>
                                        <div class="font-bold text-lg text-gray-900">$899.99</div>
                                    </div>
                                </div>
                            @endif
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
                <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold p-1">
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
    
    <!-- Custom CSS for Mobile Modal and Product Cards -->
    <style>
        /* Text line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Tab Filter Enhancements */
        .tab-filter {
            transition: all 0.3s ease;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            padding-bottom: 0.25rem;
            scroll-behavior: auto; /* Prevent smooth scrolling on hash changes */
        }
        
        .tab-filter:hover {
            color: #10b981 !important;
        }
        
        .tab-filter.active {
            color: #10b981 !important;
            border-bottom-color: #10b981 !important;
        }
        
        /* Prevent hash scroll globally */
        html {
            scroll-behavior: auto;
        }
        
        /* Product Card Animations */
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .product-card:hover {
            transform: translateY(-8px);
        }
        
        /* Enhanced card shadows */
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-shadow-hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Smooth gradient overlays */
        .gradient-overlay {
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        }
        
        /* Enhanced hover effects */
        .product-card:hover .product-image {
            transform: scale(1.1) rotate(2deg);
        }
        
        .product-card:hover .quick-view-btn {
            transform: translateY(0);
            opacity: 1;
        }
        
        /* Quick view icon styling */
        .quick-view-icon {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .quick-view-icon:hover {
            transform: scale(1.1);
            background-color: #f9fafb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Star rating improvements */
        .star-rating {
            color: #fbbf24;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        /* Badge styling improvements */
        .badge-sale {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        
        .badge-new {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .badge-best {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .badge-top {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        
        /* Mobile Modal Enhancements */
        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
            top: var(--scroll-y, 0);
            left: 0;
        }
        
        /* Modal positioning fix */
        #productModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
        }
        
        /* Prevent scroll jump */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
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
            
            /* Tab filters on mobile */
            .tab-filter {
                font-size: 0.875rem;
                padding: 0.5rem 0.25rem;
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
        
        /* Price tag styling */
        .price-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Category badge styling */
        .category-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        /* Grid animation on filter change */
        #fashion-products-grid {
            transition: all 0.3s ease;
        }
        
        /* Smooth show/hide animations for filtered products */
        .product-card.filtering {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .product-card.hidden {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            pointer-events: none;
        }
        
        .product-card.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
    </style>
    
    <script>
        // Configure axios defaults
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Pass user data to JavaScript
        window.userRole = '{{ auth()->check() ? auth()->user()->role : "guest" }}';
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // Global scroll management variables
        let scrollY = 0;
        
        // Scroll management functions
        window.preventModalScroll = function() {
            console.log('Preventing scroll - current position:', window.scrollY);
            scrollY = window.scrollY;
            document.documentElement.style.setProperty('--scroll-y', `-${scrollY}px`);
            document.body.classList.add('modal-open');
        };
        
        window.restoreModalScroll = function() {
            console.log('Restoring scroll to position:', scrollY);
            document.body.classList.remove('modal-open');
            document.documentElement.style.removeProperty('--scroll-y');
            window.scrollTo(0, scrollY);
        };

        // Override showProductDetail IMMEDIATELY before other scripts load
        window.originalShowProductDetail = window.showProductDetail;
        window.showProductDetail = function(productId) {
            console.log('showProductDetail intercepted for product:', productId);
            preventModalScroll();
            
            // Call original function if it exists, otherwise wait for it
            if (window.originalShowProductDetail) {
                window.originalShowProductDetail(productId);
            } else {
                // Wait for the original function to be loaded
                let attempts = 0;
                const waitForFunction = setInterval(() => {
                    attempts++;
                    if (window.originalShowProductDetail || attempts > 50) {
                        clearInterval(waitForFunction);
                        if (window.originalShowProductDetail) {
                            window.originalShowProductDetail(productId);
                        } else {
                            console.error('Original showProductDetail function not found after waiting');
                        }
                    }
                }, 100);
            }
        };

        // Fashion Products Tab Filtering
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Setting up event listeners');
            
            // Wait a bit for external scripts to load
            setTimeout(function() {
                console.log('Delayed setup - ensuring all scripts are loaded');
                setupProductCards();
            }, 200); // Increased delay
            
            function setupProductCards() {
                const tabFilters = document.querySelectorAll('.tab-filter');
                const productCards = document.querySelectorAll('.product-card');
                
                // Get ALL product cards including Today's Featured Deals
                const allProductCards = document.querySelectorAll('[data-product-id]');
                console.log('Found product cards:', allProductCards.length);
                
                // Enhanced Product card click handler
                function handleProductCardClick(productId) {
                    console.log('handleProductCardClick called for:', productId);
                    preventModalScroll();
                    
                    // Handle demo products
                    if (typeof productId === 'string' && productId.startsWith('demo-')) {
                        console.log('Demo product clicked:', productId);
                        return;
                    }
                    
                    console.log('Calling showProductDetail for real product:', productId);
                    if (window.originalShowProductDetail) {
                        window.originalShowProductDetail(productId);
                    } else if (window.showProductDetail) {
                        // Use the current showProductDetail if original not found
                        window.showProductDetail(productId);
                    } else {
                        console.error('No showProductDetail function found');
                    }
                }
                
                // Function to close modal and restore scroll
                window.closeProductModal = function() {
                    console.log('Closing modal and restoring scroll');
                    if (window.productDetailModal && window.productDetailModal.closeModal) {
                        window.productDetailModal.closeModal();
                    }
                    restoreModalScroll();
                };
            
            // Also handle ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('productModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeProductModal();
                    }
                }
            });
            
            // Handle click outside modal
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('productModal');
                if (e.target === modal) {
                    closeProductModal();
                }
            });
            
            // Add click event to all tab filters
            tabFilters.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent event bubbling
                    
                    const filterType = this.getAttribute('data-filter');
                    
                    // Update active tab styling
                    tabFilters.forEach(t => {
                        t.classList.remove('text-green-600', 'border-b-2', 'border-green-600');
                        t.classList.add('text-gray-500');
                    });
                    
                    this.classList.remove('text-gray-500');
                    this.classList.add('text-green-600', 'border-b-2', 'border-green-600');
                    
                    // Filter products with animation
                    productCards.forEach((card, index) => {
                        const cardType = card.getAttribute('data-type');
                        
                        if (filterType === 'all' || cardType === filterType) {
                            // Show card with staggered animation
                            card.style.display = 'block';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0) scale(1)';
                            }, index * 50); // Staggered animation
                        } else {
                            // Hide card with animation
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px) scale(0.95)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    });
                    
                    // Update URL hash for better UX (optional) - but don't scroll
                    if (filterType !== 'all') {
                        history.replaceState(null, null, `#filter-${filterType}`);
                    } else {
                        history.replaceState(null, null, window.location.pathname);
                    }
                });
            });
            
            // Initialize ALL product cards with proper styling and event listeners
            allProductCards.forEach((card, index) => {
                console.log(`Setting up card ${index + 1}:`, card.getAttribute('data-product-id'));
                
                // Remove any existing onclick to prevent conflicts
                card.removeAttribute('onclick');
                
                // Add specific click listener for ALL product cards
                card.addEventListener('click', function(e) {
                    console.log('Product card clicked:', this.getAttribute('data-product-id'));
                    e.stopPropagation();
                    e.preventDefault();
                    
                    const productId = this.getAttribute('data-product-id');
                    if (productId) {
                        console.log('Calling handleProductCardClick for:', productId);
                        handleProductCardClick(productId);
                    }
                });
            });
            
            // Initialize fashion product cards styling for tab filtering
            productCards.forEach((card, index) => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            });
            
            // Check URL hash on page load for deep linking
            const currentHash = window.location.hash;
            if (currentHash.startsWith('#filter-')) {
                const filterType = currentHash.replace('#filter-', '');
                const targetTab = document.querySelector(`[data-filter="${filterType}"]`);
                if (targetTab) {
                    // Simulate click without scrolling
                    const event = new Event('click', { bubbles: false });
                    targetTab.dispatchEvent(event);
                }
            }
            } // End setupProductCards function
        });
    </script>
    
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Load admin functions for product management -->
        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="{{ asset('api/private-axios.js') }}"></script>
    @endif
    
    <script src="{{ asset('js/product-detail.js') }}"></script>
</x-app-layout>
