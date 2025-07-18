<x-management-app-layout>
    <div class="pt-4 px-3 mt-2 lg:py-11">
        <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-6 bg-white rounded-lg shadow mb-56">
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
                                    <a href="{{ route('management.highlight.index') }}" class="ml-1 text-gray-400 hover:text-gray-600 md:ml-2">Dashboard Highlights</a>
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
                                        aria-current="page">Tambah Highlight</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Tambah Dashboard Highlight</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Buat highlight baru untuk ditampilkan di dashboard user</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('management.highlight.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Highlight *
                                </label>
                                <input type="text" name="title" id="title" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: Bring Happiness From Shopping Everyday"
                                    value="{{ old('title') }}">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Deskripsi singkat tentang highlight ini">{{ old('description') }}</textarea>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga (Opsional)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="price" id="price" step="0.01" min="0"
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="449.99"
                                        value="{{ old('price') }}">
                                </div>
                            </div>

                            <!-- Button Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teks Tombol *
                                    </label>
                                    <input type="text" name="button_text" id="button_text" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Shop Now"
                                        value="{{ old('button_text', 'Shop Now') }}">
                                </div>

                                <div>
                                    <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">
                                        Link Tombol
                                    </label>
                                    <input type="url" name="button_link" id="button_link"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="https://example.com"
                                        value="{{ old('button_link') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Highlight
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <div id="image-preview" class="hidden">
                                            <img id="preview-img" src="" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow">
                                        </div>
                                        <div id="upload-placeholder">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload gambar</span>
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                        </div>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="space-y-4">
                                <!-- Active Status -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <label for="is_active" class="text-sm font-medium text-gray-700">Status Aktif</label>
                                        <p class="text-xs text-gray-500">Highlight akan ditampilkan di dashboard jika aktif</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Urutan Tampil
                                    </label>
                                    <input type="number" name="sort_order" id="sort_order" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="0"
                                        value="{{ old('sort_order', 0) }}">
                                    <p class="text-xs text-gray-500 mt-1">Semakin kecil angka, semakin awal ditampilkan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                        <div id="highlight-preview" class="bg-blue-800 text-white rounded-lg overflow-hidden">
                            <div class="flex flex-col md:flex-row items-center">
                                <div class="p-8 md:p-16 w-full md:w-1/2 space-y-4">
                                    <h1 id="preview-title" class="text-4xl md:text-5xl font-bold leading-tight">Bring Happiness From Shopping Everyday</h1>
                                    <p id="preview-description" class="text-lg text-blue-200">Find the perfect product for your needs.</p>
                                    <p id="preview-price" class="text-3xl font-bold">$449.99</p>
                                    <button id="preview-button" class="bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
                                        Shop Now
                                    </button>
                                </div>
                                <div id="preview-image-container" class="relative w-full md:w-1/2 min-h-[30rem] flex items-center justify-center">
                                    <div id="preview-no-image" class="text-center text-blue-200">
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p>Gambar belum dipilih</p>
                                    </div>
                                    <img id="preview-image-display" src="" alt="Preview" class="hidden w-64 md:w-80 object-contain rounded-lg shadow-lg transform rotate-6 transition-transform duration-300 hover:rotate-0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('management.highlight.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Simpan Highlight
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview function
        function previewImage(input) {
            const file = input.files[0];
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const previewImg = document.getElementById('preview-img');
            const previewDisplay = document.getElementById('preview-image-display');
            const previewNoImage = document.getElementById('preview-no-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDisplay.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    previewDisplay.classList.remove('hidden');
                    previewNoImage.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                previewDisplay.classList.add('hidden');
                previewNoImage.classList.remove('hidden');
            }
        }

        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const descriptionInput = document.getElementById('description');
            const priceInput = document.getElementById('price');
            const buttonTextInput = document.getElementById('button_text');

            const previewTitle = document.getElementById('preview-title');
            const previewDescription = document.getElementById('preview-description');
            const previewPrice = document.getElementById('preview-price');
            const previewButton = document.getElementById('preview-button');

            function updatePreview() {
                previewTitle.textContent = titleInput.value || 'Bring Happiness From Shopping Everyday';
                previewDescription.textContent = descriptionInput.value || 'Find the perfect product for your needs.';
                
                if (priceInput.value) {
                    previewPrice.textContent = 'Rp ' + parseFloat(priceInput.value).toLocaleString('id-ID');
                    previewPrice.style.display = 'block';
                } else {
                    previewPrice.style.display = 'none';
                }
                
                previewButton.textContent = buttonTextInput.value || 'Shop Now';
            }

            titleInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            priceInput.addEventListener('input', updatePreview);
            buttonTextInput.addEventListener('input', updatePreview);

            // Initial update
            updatePreview();
        });
    </script>
</x-management-app-layout>
