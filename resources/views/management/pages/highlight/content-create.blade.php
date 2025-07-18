@extends('management.layouts.app')

@section('content')
    <div class="pt-4 px-3 mt-2 lg:py-11">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 bg-white rounded-lg shadow mb-56">
            <div class="w-full mb-5">
                <div class="mb-2 py-4">
                    <nav class="flex mb-5" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                            <li class="inline-flex items-center">
                                <a href="{{ route('management.index') }}"
                                    class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">Home</span>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('management.highlight.index') }}"
                                        class="ml-1 text-gray-500 hover:text-gray-700 md:ml-2">Dashboard Management</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500"
                                        aria-current="page">Tambah Konten</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Tambah Konten Baru</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Buat konten card baru untuk ditampilkan di dashboard user</p>
                </div>

                <form action="{{ route('management.content.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title"
                                       value="{{ old('title') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                       placeholder="Contoh: Clear Choice Price"
                                       required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subtitle -->
                            <div>
                                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subtitle
                                </label>
                                <input type="text" 
                                       name="subtitle" 
                                       id="subtitle"
                                       value="{{ old('subtitle') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subtitle') border-red-500 @enderror"
                                       placeholder="Contoh: Beli Pulsa">
                                @error('subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Konten <span class="text-red-500">*</span>
                                </label>
                                <select name="type" 
                                        id="type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="promo" {{ old('type') === 'promo' ? 'selected' : '' }}>Promo</option>
                                    <option value="category" {{ old('type') === 'category' ? 'selected' : '' }}>Kategori</option>
                                    <option value="featured" {{ old('type') === 'featured' ? 'selected' : '' }}>Featured</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Size -->
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ukuran Card <span class="text-red-500">*</span>
                                </label>
                                <select name="size" 
                                        id="size"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('size') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih Ukuran</option>
                                    <option value="small" {{ old('size') === 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ old('size') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ old('size') === 'large' ? 'selected' : '' }}>Large</option>
                                </select>
                                @error('size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Small: 1/4 lebar, Medium: 1/2 lebar, Large: full lebar</p>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga (Opsional)
                                </label>
                                <input type="number" 
                                       name="price" 
                                       id="price"
                                       value="{{ old('price') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                       placeholder="50000"
                                       min="0"
                                       step="1">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ada harga</p>
                            </div>

                            <!-- Price Display -->
                            <div>
                                <label for="price_display" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tampilan Harga (Opsional)
                                </label>
                                <input type="text" 
                                       name="price_display" 
                                       id="price_display"
                                       value="{{ old('price_display') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price_display') border-red-500 @enderror"
                                       placeholder="Mulai Rp5K">
                                @error('price_display')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Teks khusus untuk tampilan harga (contoh: "Mulai Rp5K")</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar (Opsional)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload gambar</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 10MB</p>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-4 hidden">
                                    <img id="preview-img" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg">
                                    <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <!-- Background Color -->
                            <div>
                                <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    Warna Background
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" 
                                           name="background_color" 
                                           id="background_color"
                                           value="{{ old('background_color', '#3B82F6') }}" 
                                           class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" 
                                           id="bg_color_text"
                                           value="{{ old('background_color', '#3B82F6') }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="#3B82F6">
                                </div>
                                @error('background_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Warna latar belakang card (jika tidak ada gambar)</p>
                            </div>

                            <!-- Text Color -->
                            <div>
                                <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    Warna Teks
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" 
                                           name="text_color" 
                                           id="text_color"
                                           value="{{ old('text_color', '#FFFFFF') }}" 
                                           class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" 
                                           id="text_color_text"
                                           value="{{ old('text_color', '#FFFFFF') }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="#FFFFFF">
                                </div>
                                @error('text_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Warna teks di dalam card</p>
                            </div>

                            <!-- Link -->
                            <div>
                                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Link (Opsional)
                                </label>
                                <input type="url" 
                                       name="link" 
                                       id="link"
                                       value="{{ old('link') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('link') border-red-500 @enderror"
                                       placeholder="https://example.com">
                                @error('link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">URL yang akan dibuka ketika card diklik</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           id="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Aktif</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-500">Konten akan ditampilkan di dashboard user</p>
                            </div>

                            <!-- Preview Card -->
                            <div id="card-preview" class="border-2 border-gray-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Preview Card:</h4>
                                <div id="preview-card" 
                                     class="rounded-lg p-4 text-white relative overflow-hidden min-h-[160px] flex items-center shadow-lg"
                                     style="background-color: #3B82F6; color: #FFFFFF;">
                                    
                                    <!-- Left Content Area -->
                                    <div class="flex-1 z-10 relative">
                                        <div class="space-y-2">
                                            <h5 id="preview-title" class="font-semibold text-xl drop-shadow-lg">Clear Choice Price</h5>
                                            <p id="preview-subtitle" class="text-sm opacity-90 drop-shadow-lg">Beli Pulsa</p>
                                            <div id="preview-price" class="font-bold text-lg drop-shadow-lg"></div>
                                            <button class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Shop Now
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Image Area -->
                                    <div class="w-32 h-32 ml-4 relative z-10 flex-shrink-0">
                                        <div id="preview-image-container" 
                                             class="w-full h-full rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('management.highlight.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Simpan Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Color picker sync
        document.getElementById('background_color').addEventListener('change', function() {
            document.getElementById('bg_color_text').value = this.value;
            updatePreview();
        });

        document.getElementById('bg_color_text').addEventListener('input', function() {
            document.getElementById('background_color').value = this.value;
            updatePreview();
        });

        document.getElementById('text_color').addEventListener('change', function() {
            document.getElementById('text_color_text').value = this.value;
            updatePreview();
        });

        document.getElementById('text_color_text').addEventListener('input', function() {
            document.getElementById('text_color').value = this.value;
            updatePreview();
        });

        // Preview update
        function updatePreview() {
            const title = document.getElementById('title').value || 'Clear Choice Price';
            const subtitle = document.getElementById('subtitle').value || 'Beli Pulsa';
            const price = document.getElementById('price').value;
            const priceDisplay = document.getElementById('price_display').value;
            const bgColor = document.getElementById('background_color').value;
            const textColor = document.getElementById('text_color').value;

            document.getElementById('preview-title').textContent = title;
            document.getElementById('preview-subtitle').textContent = subtitle;
            
            const previewPrice = document.getElementById('preview-price');
            if (priceDisplay) {
                previewPrice.textContent = priceDisplay;
                previewPrice.style.display = 'block';
            } else if (price) {
                previewPrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                previewPrice.style.display = 'block';
            } else {
                previewPrice.style.display = 'none';
            }

            const previewCard = document.getElementById('preview-card');
            previewCard.style.backgroundColor = bgColor;
            previewCard.style.color = textColor;
        }

        // Image preview
        function previewImage(input) {
            console.log('previewImage called', input.files);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Image loaded:', e.target.result);
                    // Show image preview below upload area
                    const previewImg = document.getElementById('preview-img');
                    const imagePreview = document.getElementById('image-preview');
                    
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    
                    // Show image in card preview - update image container
                    const previewImageContainer = document.getElementById('preview-image-container');
                    
                    console.log('Setting background image for preview image container');
                    
                    // Set background image untuk container image
                    previewImageContainer.style.backgroundImage = `url("${e.target.result}")`;
                    previewImageContainer.style.backgroundSize = 'cover';
                    previewImageContainer.style.backgroundPosition = 'center';
                    previewImageContainer.style.backgroundRepeat = 'no-repeat';
                    
                    // Hide placeholder icon
                    previewImageContainer.innerHTML = '';
                    
                    console.log('Background image set successfully');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            
            // Remove image from card preview
            const previewImageContainer = document.getElementById('preview-image-container');
            
            previewImageContainer.style.backgroundImage = '';
            // Show placeholder icon again
            previewImageContainer.innerHTML = `
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                </svg>
            `;
        }

        // Form input listeners for live preview
        document.getElementById('title').addEventListener('input', updatePreview);
        document.getElementById('subtitle').addEventListener('input', updatePreview);
        document.getElementById('price').addEventListener('input', updatePreview);
        document.getElementById('price_display').addEventListener('input', updatePreview);

        // Initialize preview
        updatePreview();
    </script>
@endsection
