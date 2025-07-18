@extends('management.layouts.app')

@section('content')
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
                                        aria-current="page">Detail Highlight</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-xl lg:text-2xl font-semibold text-gray-900 dark:text-white">Detail Highlight</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $highlight->title }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('management.highlight.edit', $highlight) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('management.highlight.index') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Highlight Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Info Section -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Highlight</h3>
                            
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $highlight->title }}</dd>
                                </div>
                                
                                @if($highlight->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $highlight->description }}</dd>
                                    </div>
                                @endif
                                
                                @if($highlight->price)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Harga</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($highlight->price, 0, ',', '.') }}</dd>
                                    </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teks Tombol</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $highlight->button_text }}</dd>
                                </div>
                                
                                @if($highlight->button_link)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Link Tombol</dt>
                                        <dd class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                            <a href="{{ $highlight->button_link }}" target="_blank" class="underline">
                                                {{ $highlight->button_link }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $highlight->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $highlight->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Urutan Tampil</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $highlight->sort_order }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $highlight->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $highlight->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="space-y-6">
                        @if($highlight->image_path)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Highlight</h3>
                                <img src="{{ asset('storage/' . $highlight->image_path) }}" 
                                     alt="{{ $highlight->title }}" 
                                     class="w-full h-auto rounded-lg shadow-lg">
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Highlight</h3>
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-gray-500">Tidak ada gambar</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview di Dashboard</h3>
                    <div class="bg-blue-800 text-white rounded-lg overflow-hidden">
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
                                    <button class="bg-white text-blue-800 font-semibold py-3 px-8 rounded-lg mt-4 hover:bg-gray-200 transition-colors">
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
                                    <div class="text-center text-blue-200">
                                        <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p>Tidak ada gambar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
