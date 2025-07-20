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
                               class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('management.users.index') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">User Management</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-500 ml-1 md:ml-2">Detail User</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

    <div class="container mx-auto px-4 py-6">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('management.users.edit', $user) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('management.users.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- User Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi User</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                <p class="text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Role</label>
                                <p class="text-gray-900">{{ ucfirst($user->role) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Bergabung</label>
                                <p class="text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Terakhir Update</label>
                                <p class="text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders History -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Riwayat Order ({{ $user->orders->count() }})</h3>
                        </div>
                        <div class="p-6">
                            @if($user->orders->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->orders->take(10) as $order)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-900">#{{ $order->id }}</p>
                                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($order->status === 'paid') bg-green-100 text-green-800
                                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if($order->orderItems->count() > 0)
                                                <div class="mt-2 text-sm text-gray-600">
                                                    {{ $order->orderItems->count() }} item(s) - 
                                                    {{ $order->orderItems->pluck('product.name')->take(2)->join(', ') }}
                                                    @if($order->orderItems->count() > 2)
                                                        dan {{ $order->orderItems->count() - 2 }} lainnya
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($user->orders->count() > 10)
                                        <div class="text-center">
                                            <a href="{{ route('management.orders.index', ['user_id' => $user->id]) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Lihat semua order ({{ $user->orders->count() }})
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <p class="mt-2 text-gray-500">Belum ada order</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses (if customer) -->
            @if($user->role === 'customer' && $user->addresses->count() > 0)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Alamat ({{ $user->addresses->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->addresses as $address)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $address->label }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ $address->full_address }}</p>
                                            <p class="text-sm text-gray-500">{{ $address->city }}, {{ $address->postal_code }}</p>
                                        </div>
                                        @if($address->is_default)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>
</div>
@endsection
