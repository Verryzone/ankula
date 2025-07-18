@extends('management.layouts.app')

@section('content')
    <div class="py-11 lg:px-3">
        <div class="px-8 pt-2 bg-white block sm:flex items-center justify-between border-b rounded-t-lg border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-5">
                <div class="mb-2 py-4">
                    <nav class="flex mb-5" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                            <li class="inline-flex items-center">
                                <a href="{{ route('management.index') }}" class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('management.orders.index') }}" class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">Orders</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Order #{{ $order->order_number }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <div class="flex items-center justify-between">
                        <h1 class="text-lg font-semibold text-gray-900 sm:text-2xl dark:text-white">Order #{{ $order->order_number }}</h1>
                        <a href="{{ route('management.orders.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Order Details</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Number:</span>
                                    <span class="font-medium">#{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order ID:</span>
                                    <span class="font-medium">{{ $order->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Current Status:</span>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'processing' => 'Processing',
                                            'completed' => 'Completed',
                                            'cancelled' => 'Cancelled'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Customer Information</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{ $order->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $order->user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Customer ID:</span>
                                    <span class="font-medium">{{ $order->user->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    @if($item->product->category)
                                        <p class="text-sm text-gray-600 mb-2">{{ $item->product->category->name }}</p>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="font-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Information -->
                @if($order->payment)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold mb-4">Payment Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Payment Method</p>
                                <p class="font-medium">{{ ucfirst($order->payment->payment_method) }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                @php
                                    $paymentClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'success' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $paymentLabels = [
                                        'pending' => 'Unpaid',
                                        'success' => 'Paid',
                                        'failed' => 'Failed',
                                        'expired' => 'Expired'
                                    ];
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $paymentClasses[$order->payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $paymentLabels[$order->payment->status] ?? ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            
                            @if($order->payment->paid_at)
                                <div>
                                    <p class="text-sm text-gray-600">Payment Date</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($order->payment->paid_at)->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                            
                            @if($order->payment->payment_reference)
                                <div>
                                    <p class="text-sm text-gray-600">Payment Reference</p>
                                    <p class="font-medium font-mono text-sm">{{ $order->payment->payment_reference }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Status Management -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Update Order Status</h2>
                    
                    <form method="POST" action="{{ route('management.orders.update-status', $order->id) }}" onsubmit="return confirmStatusUpdate()">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Select New Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal ({{ $order->orderItems->sum('quantity') }} items)</span>
                            <span class="font-medium">Rp {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping Cost</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if($order->shippingAddress || $order->shipping_address_snapshot)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-xl font-semibold mb-4">Shipping Address</h2>
                        
                        <div class="text-gray-700">
                            @if($order->shippingAddress)
                                <p class="font-medium">{{ $order->shippingAddress->name }}</p>
                                <p class="text-sm text-gray-600 mb-2">{{ $order->shippingAddress->phone }}</p>
                                <p>{{ $order->shippingAddress->address }}</p>
                                <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->postal_code }}</p>
                                <p>{{ $order->shippingAddress->province }}</p>
                            @elseif($order->shipping_address_snapshot)
                                @php 
                                    $snapshot = $order->shipping_address_snapshot; 
                                @endphp
                                <p class="font-medium">{{ $snapshot['name'] ?? 'Name not available' }}</p>
                                <p class="text-sm text-gray-600 mb-2">{{ $snapshot['phone'] ?? '' }}</p>
                                <p>{{ $snapshot['address'] ?? 'Address not available' }}</p>
                                <p>{{ $snapshot['city'] ?? '' }}, {{ $snapshot['postal_code'] ?? '' }}</p>
                                @if(isset($snapshot['province']))
                                    <p>{{ $snapshot['province'] }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    
                    <div class="space-y-3">
                        @if($order->status === 'pending')
                            <button onclick="quickStatusUpdate('processing')" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                                Mark as Processing
                            </button>
                        @endif
                        
                        @if($order->status === 'processing')
                            <button onclick="quickStatusUpdate('completed')" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300">
                                Mark as Completed
                            </button>
                        @endif
                        
                        @if($order->status !== 'cancelled' && $order->status !== 'completed')
                            <button onclick="quickStatusUpdate('cancelled')" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300">
                                Cancel Order
                            </button>
                        @endif
                        
                        <a href="{{ route('management.orders.index') }}" class="block w-full px-4 py-2 bg-gray-600 text-white text-center rounded-md hover:bg-gray-700 transition duration-300">
                            Back to All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmStatusUpdate() {
            const newStatus = document.getElementById('status').value;
            const currentStatus = '{{ $order->status }}';
            
            if (newStatus === currentStatus) {
                alert('Status is already set to ' + newStatus);
                return false;
            }
            
            return confirm('Are you sure you want to change the order status to "' + newStatus + '"?');
        }

        function quickStatusUpdate(status) {
            if (confirm('Are you sure you want to change the order status to "' + status + '"?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("management.orders.update-status", $order->id) }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method override
                const methodOverride = document.createElement('input');
                methodOverride.type = 'hidden';
                methodOverride.name = '_method';
                methodOverride.value = 'PATCH';
                form.appendChild(methodOverride);
                
                // Add status input
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                form.appendChild(statusInput);
                
                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
