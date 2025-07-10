<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-2 lg:px-4 space-y-6">
            @if($cartItems->isEmpty())
                <!-- Empty Cart State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                        <p class="text-gray-500 mb-6">Belum ada produk yang ditambahkan ke keranjang</p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @else
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Cart Items Section -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100 overflow-hidden">
                        <!-- Cart Header -->
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" id="select-all" class="w-5 h-5 text-blue-700 bg-white border-2 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2">
                                <label for="select-all" class="font-semibold text-gray-900">Pilih Semua ({{ $totalItems }} item)</label>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        @foreach($cartItems as $item)
                        <div class="p-5 border-b border-gray-100 last:border-b-0" data-cart-item-id="{{ $item->id }}">
                            <div class="flex items-center gap-4">
                                <!-- Checkbox -->
                                <div class="shrink-0 pt-1">
                                    <input type="checkbox" class="cart-item-checkbox w-5 h-5 text-blue-700 bg-white border-2 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2" 
                                           data-price="{{ $item->product->price }}" 
                                           data-quantity="{{ $item->quantity }}">
                                </div>

                                <!-- Product Image -->
                                <div class="shrink-0 relative">
                                    <div class="w-28 h-28 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://via.placeholder.com/96x96?text=No+Image'">
                                    </div>
                                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-bl-lg">
                                        Sisa: {{ $item->product->stock }}
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-medium text-gray-900 text-sm leading-relaxed pr-2">
                                            {{ $item->product->name }}
                                        </h3>
                                    </div>

                                    <p class="text-sm text-gray-500 mb-3">
                                        Kategori: {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                    </p>

                                    <!-- Price -->
                                    <div class="flex items-baseline gap-2 mb-4">
                                        <span class="text-2xl font-bold text-red-600">{{ formatCurrency($item->product->price) }}</span>
                                        {{-- You can add original_price field to show discount --}}
                                        {{-- <span class="text-sm text-gray-400 line-through">{{ formatCurrency($item->product->original_price) }}</span> --}}
                                    </div>

                                    <!-- Actions Row -->
                                    <div class="flex items-center justify-between">
                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-2">
                                            <button class="p-2.5 hover:bg-gray-100 rounded-lg transition-colors group" title="Tambah ke Wishlist">
                                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="m12.75 20.66 6.184-7.098c2.677-2.884 2.559-6.506.754-8.705-.898-1.095-2.206-1.816-3.72-1.855-1.293-.034-2.652.43-3.963 1.442-1.315-1.012-2.678-1.476-3.973-1.442-1.515.04-2.825.76-3.724 1.855-1.806 2.201-1.915 5.823.772 8.706l6.183 7.097c.19.216.46.34.743.34a.985.985 0 0 0 .743-.34Z" />
                                                </svg>
                                            </button>
                                            <button class="p-2.5 hover:bg-red-500 hover:text-white rounded-lg transition-colors group" 
                                                    onclick="removeCartItem({{ $item->id }})" 
                                                    title="Hapus dari Keranjang">
                                                <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-2">
                                            <button class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                                                    onclick="updateQuantity({{ $item->id }}, -1)">
                                                -
                                            </button>
                                            <input type="number" 
                                                   id="quantity-{{ $item->id }}" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   class="w-16 text-center rounded-lg border border-gray-300"
                                                   onchange="updateQuantity({{ $item->id }}, 0, this.value)">
                                            <button class="py-2.5 px-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
                                                    onclick="updateQuantity({{ $item->id }}, 1)">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100 p-6 sticky top-4">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Ringkasan belanja</h3>

                        <!-- Subtotal -->
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Subtotal (<span id="summary-item-count">0</span> item)</span>
                            <span id="summary-subtotal" class="font-semibold text-gray-900">Rp0</span>
                        </div>

                        <!-- Shipping -->
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-semibold text-gray-900">Gratis</span>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Total</span>
                            <span id="total-price" class="text-3xl font-bold text-gray-900">Rp0</span>
                        </div>

                        <!-- Selected Items Info -->
                        <div id="selected-items-info" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 hidden">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fas fa-info text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm mb-1">
                                        <span id="selected-count">0</span> item dipilih
                                    </p>
                                    <p class="text-blue-700 text-sm">
                                        Total: <span id="selected-total" class="font-bold">Rp0</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($totalDiscount > 0)
                        <!-- Promo Section -->
                        <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-lg p-4 mb-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-100 rounded-full -mr-10 -mt-10 opacity-50"></div>
                            <div class="relative flex items-start gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-sm shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm mb-1">Promo berhasil dipakai</p>
                                    <p class="text-emerald-700 text-sm leading-relaxed">
                                        Hemat <span class="font-bold">{{ formatCurrency($totalDiscount) }}</span> 🎉
                                    </p>
                                </div>
                                <i class="fas fa-chevron-right text-emerald-400 text-sm mt-1"></i>
                            </div>
                        </div>
                        @endif

                        <!-- Buy Button -->
                        <button id="checkout-btn"
                            class="w-full bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all ease-in-out hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            onclick="proceedToCheckout()" 
                            disabled>
                            <span class="flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart text-sm"></i>
                                <span id="checkout-text">Pilih produk untuk checkout</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Hapus Produk</h3>
                        <p class="text-sm text-gray-500">Konfirmasi penghapusan item dari keranjang</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus produk ini dari keranjang belanja?</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900" id="deleteProductName">Nama Produk</p>
                                <p class="text-sm text-gray-500">Item akan dihapus secara permanen</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" 
                            class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmDeleteItem()" 
                            class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configure axios defaults
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Cart management functions
        function updateQuantity(cartItemId, change, newValue = null) {
            const quantityInput = document.getElementById(`quantity-${cartItemId}`);
            let quantity = newValue ? parseInt(newValue) : parseInt(quantityInput.value) + change;
            
            if (quantity < 1) quantity = 1;
            
            const maxStock = parseInt(quantityInput.getAttribute('max'));
            if (quantity > maxStock) {
                alert(`Stock tidak mencukupi. Maksimal ${maxStock} item.`);
                return;
            }
            
            quantityInput.value = quantity;
            
            // Update cart in database
            updateCartItem(cartItemId, quantity);
        }

        async function updateCartItem(cartItemId, quantity) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await axios.put(`/api/cart/update/${cartItemId}`, {
                    quantity: quantity
                }, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    // Update the checkbox data attribute for quantity
                    const checkbox = document.querySelector(`[data-cart-item-id="${cartItemId}"] .cart-item-checkbox`);
                    if (checkbox) {
                        checkbox.setAttribute('data-quantity', quantity);
                    }
                    updateCartTotals();
                } else {
                    alert(response.data.message || 'Gagal memperbarui keranjang');
                }
            } catch (error) {
                console.error('Error updating cart:', error);
                if (error.response && error.response.data && error.response.data.message) {
                    alert(error.response.data.message);
                } else {
                    alert('Gagal memperbarui keranjang');
                }
            }
        }

        let itemToDelete = null;

        function removeCartItem(cartItemId) {
            // Get product name for the modal
            const itemElement = document.querySelector(`[data-cart-item-id="${cartItemId}"]`);
            const productName = itemElement.querySelector('h3').textContent.trim();
            
            // Set the product name in modal and store item ID
            document.getElementById('deleteProductName').textContent = productName;
            itemToDelete = cartItemId;
            
            // Show the delete modal
            showDeleteModal();
        }

        function showDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            itemToDelete = null;
        }

        async function confirmDeleteItem() {
            if (!itemToDelete) return;
            
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await axios.delete(`/api/cart/remove/${itemToDelete}`, {
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                if (response.data.success) {
                    // Remove item from DOM
                    document.querySelector(`[data-cart-item-id="${itemToDelete}"]`).remove();
                    updateCartTotals();
                    closeDeleteModal();
                    
                    // Show success message
                    showSuccessMessage('Produk berhasil dihapus dari keranjang');
                    
                    // Reload page if no items left
                    if (document.querySelectorAll('[data-cart-item-id]').length === 0) {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    alert(response.data.message || 'Gagal menghapus produk dari keranjang');
                }
            } catch (error) {
                console.error('Error removing cart item:', error);
                if (error.response && error.response.data && error.response.data.message) {
                    alert(error.response.data.message);
                } else {
                    alert('Gagal menghapus produk dari keranjang');
                }
            }
        }

        function showSuccessMessage(message) {
            // Create success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[60] transform translate-x-full transition-transform duration-300';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function updateCartTotals() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            let total = 0;
            let count = 0;
            
            checkboxes.forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(checkbox.dataset.quantity);
                total += price * quantity;
                count++;
            });
            
            // Update selected items info (blue box)
            document.getElementById('selected-count').textContent = count;
            document.getElementById('selected-total').textContent = formatCurrency(total);
            
            // Update summary section (right sidebar)
            document.getElementById('summary-item-count').textContent = count;
            document.getElementById('summary-subtotal').textContent = formatCurrency(total);
            document.getElementById('total-price').textContent = formatCurrency(total);
            
            const selectedInfo = document.getElementById('selected-items-info');
            const checkoutBtn = document.getElementById('checkout-btn');
            const checkoutText = document.getElementById('checkout-text');
            
            if (count > 0) {
                selectedInfo.classList.remove('hidden');
                checkoutBtn.disabled = false;
                checkoutText.textContent = `Beli (${count})`;
            } else {
                selectedInfo.classList.add('hidden');
                checkoutBtn.disabled = true;
                checkoutText.textContent = 'Pilih produk untuk checkout';
            }
        }

        function proceedToCheckout() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            const selectedItems = Array.from(checkboxes).map(cb => 
                cb.closest('[data-cart-item-id]').dataset.cartItemId
            );
            
            if (selectedItems.length === 0) {
                alert('Pilih produk yang ingin dibeli');
                return;
            }
            
            // Debug log
            console.log('Selected items for checkout:', selectedItems);
            
            // Validate that all selected items exist and are valid
            const validItems = selectedItems.filter(id => id && id !== 'undefined');
            
            if (validItems.length === 0) {
                alert('Terjadi kesalahan: ID item tidak valid. Silakan refresh halaman.');
                return;
            }
            
            if (validItems.length !== selectedItems.length) {
                console.warn('Some items have invalid IDs:', selectedItems);
            }
            
            // Redirect directly to checkout page with selected items
            const params = validItems.map(id => `items[]=${id}`).join('&');
            const checkoutUrl = `{{ route('checkout') }}?${params}`;
            
            console.log('Redirecting to:', checkoutUrl);
            window.location.href = checkoutUrl;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize totals (start with 0 since nothing is selected initially)
            updateCartTotals();
            
            // Select all functionality
            const selectAllCheckbox = document.getElementById('select-all');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateCartTotals();
                });
            }

            // Individual checkbox change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('cart-item-checkbox')) {
                    updateCartTotals();
                    
                    // Update select all checkbox
                    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
                    const checkedBoxes = document.querySelectorAll('.cart-item-checkbox:checked');
                    const selectAll = document.getElementById('select-all');
                    
                    if (selectAll) {
                        selectAll.checked = checkboxes.length === checkedBoxes.length;
                        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
                    }
                }
            });

            // Close modal with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });

            // Close modal when clicking outside
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === deleteModal) {
                        closeDeleteModal();
                    }
                });
            }
        });
    </script>
</x-app-layout>
