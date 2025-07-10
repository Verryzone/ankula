<x-management-app-layout>
    <div class="py-11 lg:px-3">
        <div class="px-8 pt-2 bg-white block sm:flex items-center justify-between border-b rounded-t-lg border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-5">
                <div class="mb-2">
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
                                    <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Orders</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-lg font-semibold text-gray-900 sm:text-2xl dark:text-white">Order Management</h1>
                </div>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                        <div class="text-sm text-blue-700">Total Orders</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                        <div class="text-sm text-yellow-700">Pending</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
                        <div class="text-sm text-blue-700">Processing</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                        <div class="text-sm text-green-700">Completed</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
                        <div class="text-sm text-red-700">Cancelled</div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 p-4 rounded-lg border mb-6">
                    <form method="GET" action="{{ route('management.orders.index') }}" class="flex flex-col lg:flex-row gap-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Orders</label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ $search }}"
                                   placeholder="Order number, customer name, email, or product..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="lg:w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                                Filter
                            </button>
                        </div>
                    </form>
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

        <!-- Orders Table -->
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow bg-white dark:bg-gray-800">
                        @if($orders->count() > 0)
                            <!-- Bulk Actions -->
                            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                                <form id="bulk-action-form" method="POST" action="{{ route('management.orders.bulk-update') }}">
                                    @csrf
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="select-all" class="ml-2 text-sm text-gray-900">Select All</label>
                                        </div>
                                        
                                        <select name="status" id="bulk-status" class="px-3 py-1 border border-gray-300 rounded text-sm">
                                            <option value="">Change Status To...</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        
                                        <button type="submit" id="bulk-update-btn" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50" disabled>
                                            Update Selected
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <div class="flex items-center">
                                                <span class="sr-only">Select</span>
                                            </div>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Order
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Customer
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Products
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Total
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Status
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Payment
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Date
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 w-4">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                </div>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                <div class="text-base font-semibold text-gray-900 dark:text-white">
                                                    #{{ $order->order_number }}
                                                </div>
                                                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                    ID: {{ $order->id }}
                                                </div>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                <div class="text-base font-semibold text-gray-900 dark:text-white">
                                                    {{ $order->user->name }}
                                                </div>
                                                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                    {{ $order->user->email }}
                                                </div>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                                <div class="text-sm">
                                                    {{ $order->orderItems->count() }} item(s)
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    @foreach($order->orderItems->take(2) as $item)
                                                        {{ $item->product->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                    @if($order->orderItems->count() > 2)
                                                        +{{ $order->orderItems->count() - 2 }} more
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                <div class="text-base font-semibold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    +Rp {{ number_format($order->shipping_cost, 0, ',', '.') }} shipping
                                                </div>
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
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
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                @if($order->payment)
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
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $paymentClasses[$order->payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ $paymentLabels[$order->payment->status] ?? ucfirst($order->payment->status) }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                        No Payment
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                                <div class="text-sm">
                                                    {{ $order->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $order->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="p-4 space-x-2 whitespace-nowrap">
                                                <a href="{{ route('management.orders.show', $order->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <!-- Empty State -->
                            <div class="p-8 text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                                <p class="text-gray-500">No orders match your current filters.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');
            const bulkStatusSelect = document.getElementById('bulk-status');
            const bulkUpdateBtn = document.getElementById('bulk-update-btn');
            const bulkActionForm = document.getElementById('bulk-action-form');

            // Handle select all
            selectAllCheckbox.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkButton();
            });

            // Handle individual checkboxes
            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
                    selectAllCheckbox.checked = checkedCount === orderCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < orderCheckboxes.length;
                    updateBulkButton();
                });
            });

            // Handle bulk status change
            bulkStatusSelect.addEventListener('change', updateBulkButton);

            function updateBulkButton() {
                const hasCheckedOrders = document.querySelectorAll('.order-checkbox:checked').length > 0;
                const hasSelectedStatus = bulkStatusSelect.value !== '';
                bulkUpdateBtn.disabled = !(hasCheckedOrders && hasSelectedStatus);
            }

            // Handle bulk form submission
            bulkActionForm.addEventListener('submit', function(e) {
                const checkedOrders = document.querySelectorAll('.order-checkbox:checked');
                if (checkedOrders.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one order.');
                    return;
                }

                if (bulkStatusSelect.value === '') {
                    e.preventDefault();
                    alert('Please select a status to update to.');
                    return;
                }

                const confirmMessage = `Are you sure you want to update ${checkedOrders.length} order(s) to "${bulkStatusSelect.options[bulkStatusSelect.selectedIndex].text}"?`;
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-management-app-layout>
