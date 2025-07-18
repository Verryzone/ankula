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
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500"
                                        aria-current="page">Dashboard Management</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola highlights dan konten yang ditampilkan di dashboard user</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button onclick="switchTab('highlights')" id="highlights-tab" 
                                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-blue-500 text-blue-600">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Highlights Banner
                        </button>
                        <button onclick="switchTab('contents')" id="contents-tab" 
                                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                            </svg>
                            Konten Cards
                        </button>
                    </nav>
                </div>

                <!-- Highlights Tab Content -->
                <div id="highlights-content" class="tab-content">
                    <div class="flex justify-between items-center mb-6 mt-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Highlights Banner</h3>
                            <p class="text-gray-600 text-sm">Banner utama yang ditampilkan di bagian atas dashboard</p>
                        </div>
                        <a href="{{ route('management.highlight.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Tambah Highlight
                        </a>
                    </div>

                    @if($highlights->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Highlight</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="highlights-table" class="bg-white divide-y divide-gray-200">
                                    @foreach($highlights as $highlight)
                                        <tr data-id="{{ $highlight->id }}" class="hover:bg-gray-50 cursor-move">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($highlight->image_path)
                                                        <img src="{{ asset('storage/' . $highlight->image_path) }}" 
                                                             alt="{{ $highlight->title }}" 
                                                             class="w-16 h-16 object-cover rounded-lg mr-4">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $highlight->title }}</div>
                                                        @if($highlight->description)
                                                            <div class="text-sm text-gray-500">{{ Str::limit($highlight->description, 50) }}</div>
                                                        @endif
                                                        @if($highlight->price)
                                                            <div class="text-sm font-semibold text-blue-600">Rp {{ number_format($highlight->price, 0, ',', '.') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" 
                                                           {{ $highlight->is_active ? 'checked' : '' }}
                                                           onchange="toggleHighlightStatus({{ $highlight->id }}, this)">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $highlight->sort_order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('management.highlight.edit', $highlight) }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('management.highlight.destroy', $highlight) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus highlight ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada highlights</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan membuat highlight pertama untuk dashboard</p>
                            <a href="{{ route('management.highlight.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Tambah Highlight
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Contents Tab Content -->
                <div id="contents-content" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6 mt-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Konten Cards</h3>
                            <p class="text-gray-600 text-sm">Card promosi dan kategori yang ditampilkan di dashboard</p>
                        </div>
                        <a href="{{ route('management.content.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Tambah Konten
                        </a>
                    </div>

                    @if($contents->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konten</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="contents-table" class="bg-white divide-y divide-gray-200">
                                    @foreach($contents as $content)
                                        <tr data-id="{{ $content->id }}" class="hover:bg-gray-50 cursor-move">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($content->image_path)
                                                        <img src="{{ asset('storage/' . $content->image_path) }}" 
                                                             alt="{{ $content->title }}" 
                                                             class="w-12 h-12 object-cover rounded mr-4">
                                                    @else
                                                        <div class="w-12 h-12 rounded mr-4 flex items-center justify-center" 
                                                             style="background-color: {{ $content->background_color }};">
                                                            <svg class="w-6 h-6" style="color: {{ $content->text_color }};" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $content->title }}</div>
                                                        @if($content->subtitle)
                                                            <div class="text-sm text-gray-500">{{ $content->subtitle }}</div>
                                                        @endif
                                                        @if($content->price_display)
                                                            <div class="text-sm font-semibold text-green-600">{{ $content->price_display }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $content->type === 'promo' ? 'bg-blue-100 text-blue-800' : 
                                                       ($content->type === 'featured' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($content->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                    {{ $content->size === 'large' ? 'bg-red-100 text-red-800' : 
                                                       ($content->size === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($content->size) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" 
                                                           {{ $content->is_active ? 'checked' : '' }}
                                                           onchange="toggleContentStatus({{ $content->id }}, this)">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('management.content.edit', $content) }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('management.content.destroy', $content) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada konten</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan membuat konten pertama untuk dashboard</p>
                            <a href="{{ route('management.content.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Tambah Konten
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Load SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Tab switching
        function switchTab(tab) {
            // Hide all tab contents
            document.getElementById('highlights-content').classList.add('hidden');
            document.getElementById('contents-content').classList.add('hidden');
            
            // Remove active styles from all tabs
            document.getElementById('highlights-tab').className = 'py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            document.getElementById('contents-tab').className = 'py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            
            // Show selected tab and apply active styles
            if (tab === 'highlights') {
                document.getElementById('highlights-content').classList.remove('hidden');
                document.getElementById('highlights-tab').className = 'py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-blue-500 text-blue-600';
            } else {
                document.getElementById('contents-content').classList.remove('hidden');
                document.getElementById('contents-tab').className = 'py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-blue-500 text-blue-600';
            }
        }

        // Initialize sortable tables
        document.addEventListener('DOMContentLoaded', function() {
            // Sortable for highlights
            const highlightsTable = document.getElementById('highlights-table');
            if (highlightsTable) {
                Sortable.create(highlightsTable, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        const order = Array.from(evt.to.children).map(row => row.dataset.id);
                        updateHighlightOrder(order);
                    }
                });
            }

            // Sortable for contents
            const contentsTable = document.getElementById('contents-table');
            if (contentsTable) {
                Sortable.create(contentsTable, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        const order = Array.from(evt.to.children).map(row => row.dataset.id);
                        updateContentOrder(order);
                    }
                });
            }
        });

        // Toggle highlight status
        function toggleHighlightStatus(id, element) {
            axios.post(`/management/highlight/${id}/toggle-active`, {
                _token: '{{ csrf_token() }}'
            })
            .then(response => {
                if (response.data.success) {
                    showToast(response.data.message, 'success');
                }
            })
            .catch(error => {
                element.checked = !element.checked; // Revert toggle
                showToast('Gagal mengubah status', 'error');
            });
        }

        // Toggle content status
        function toggleContentStatus(id, element) {
            axios.post(`/management/content/${id}/toggle-active`, {
                _token: '{{ csrf_token() }}'
            })
            .then(response => {
                if (response.data.success) {
                    showToast(response.data.message, 'success');
                }
            })
            .catch(error => {
                element.checked = !element.checked; // Revert toggle
                showToast('Gagal mengubah status', 'error');
            });
        }

        // Update highlight order
        function updateHighlightOrder(order) {
            axios.post('/management/highlight/update-order', {
                highlights: order,
                _token: '{{ csrf_token() }}'
            })
            .then(response => {
                if (response.data.success) {
                    showToast(response.data.message, 'success');
                }
            })
            .catch(error => {
                showToast('Gagal mengupdate urutan', 'error');
            });
        }

        // Update content order
        function updateContentOrder(order) {
            axios.post('/management/content/update-order', {
                contents: order,
                _token: '{{ csrf_token() }}'
            })
            .then(response => {
                if (response.data.success) {
                    showToast(response.data.message, 'success');
                }
            })
            .catch(error => {
                showToast('Gagal mengupdate urutan', 'error');
            });
        }

        // Simple toast notification
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endsection
