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
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500"
                                        aria-current="page">Dashboard Highlights</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Highlights</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola highlight yang tampil di dashboard user</p>
                        </div>
                        <a href="{{ route('management.highlight.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Tambah Highlight
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Highlights Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    @if($highlights->count() > 0)
                        <!-- Sort Instructions -->
                        <div class="bg-blue-50 px-4 py-3 border-b">
                            <p class="text-sm text-blue-800">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Anda dapat mengubah urutan highlight dengan menyeret baris ke posisi yang diinginkan
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 7h6v6H7z"></path>
                                                <path fill-rule="evenodd" d="M2 7a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1V7zM2 13a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path>
                                            </svg>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gambar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Highlight
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Urutan
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-highlights" class="bg-white divide-y divide-gray-200">
                                    @foreach($highlights as $highlight)
                                        <tr data-id="{{ $highlight->id }}" class="sortable-row hover:bg-gray-50 cursor-move">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-gray-400">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M7 2a1 1 0 00-1 1v1H4a1 1 0 00-1 1v1a1 1 0 001 1h1v1a1 1 0 001 1h1a1 1 0 001-1V7h1a1 1 0 001-1V5a1 1 0 00-1-1H8V3a1 1 0 00-1-1H7zM4 11a1 1 0 00-1 1v1a1 1 0 001 1h1v1a1 1 0 001 1h1a1 1 0 001-1v-1h1a1 1 0 001-1v-1a1 1 0 00-1-1H8v-1a1 1 0 00-1-1H6a1 1 0 00-1 1v1H4zM13 7a1 1 0 011-1h1V5a1 1 0 011-1h1a1 1 0 011 1v1h1a1 1 0 011 1v1a1 1 0 01-1 1h-1v1a1 1 0 01-1 1h-1a1 1 0 01-1-1V8h-1a1 1 0 01-1-1V7zM13 15a1 1 0 011-1h1v-1a1 1 0 011-1h1a1 1 0 011 1v1h1a1 1 0 011 1v1a1 1 0 01-1 1h-1v1a1 1 0 01-1 1h-1a1 1 0 01-1-1v-1h-1a1 1 0 01-1-1v-1z"></path>
                                                    </svg>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($highlight->image_path)
                                                    <img src="{{ asset('storage/' . $highlight->image_path) }}" 
                                                         alt="{{ $highlight->title }}" 
                                                         class="h-12 w-12 object-cover rounded-lg">
                                                @else
                                                    <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $highlight->title }}</div>
                                                @if($highlight->description)
                                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $highlight->description }}</div>
                                                @endif
                                                @if($highlight->price)
                                                    <div class="text-sm text-blue-600 font-semibold">Rp {{ number_format($highlight->price, 0, ',', '.') }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button onclick="toggleStatus({{ $highlight->id }})" 
                                                        class="status-toggle relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $highlight->is_active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                                        data-active="{{ $highlight->is_active ? 'true' : 'false' }}">
                                                    <span class="sr-only">Toggle status</span>
                                                    <span class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $highlight->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                                        <span class="opacity-100 ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                                            @if($highlight->is_active)
                                                                <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 12 12">
                                                                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 4-4"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                                                    <path d="m4 8 2-2m0 0 2-2M6 6L4 4m2 2 2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </span>
                                                </button>
                                                <span class="ml-2 text-sm text-gray-500">
                                                    {{ $highlight->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $highlight->sort_order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2 justify-end">
                                                    <a href="{{ route('management.highlight.edit', $highlight) }}" 
                                                       class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded transition-colors">
                                                        Edit
                                                    </a>
                                                    <button onclick="deleteHighlight({{ $highlight->id }})"
                                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition-colors">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada highlight</h3>
                            <p class="text-gray-500 mb-6">Mulai dengan menambahkan highlight pertama untuk dashboard</p>
                            <a href="{{ route('management.highlight.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Tambah Highlight Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Include SortableJS for drag & drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <script>
        // Initialize sortable
        @if($highlights->count() > 0)
            const sortable = Sortable.create(document.getElementById('sortable-highlights'), {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    const order = Array.from(evt.to.children).map(row => row.dataset.id);
                    updateOrder(order);
                }
            });
        @endif

        function updateOrder(order) {
            axios.post('{{ route("management.highlight.update-order") }}', {
                highlights: order
            })
            .then(response => {
                if (response.data.success) {
                    // Optional: Show success message
                    console.log('Order updated successfully');
                }
            })
            .catch(error => {
                console.error('Error updating order:', error);
                location.reload(); // Reload on error
            });
        }

        function toggleStatus(id) {
            axios.post(`/management/highlight/${id}/toggle-active`)
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error toggling status:', error);
                    alert('Terjadi kesalahan saat mengubah status');
                });
        }

        function deleteHighlight(id) {
            if (confirm('Apakah Anda yakin ingin menghapus highlight ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/management/highlight/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.4;
        }
        
        .sortable-chosen {
            cursor: grabbing;
        }
        
        .sortable-drag {
            opacity: 0.8;
        }
        
        .sortable-row:hover {
            background-color: #f9fafb;
        }
    </style>
</x-management-app-layout>
