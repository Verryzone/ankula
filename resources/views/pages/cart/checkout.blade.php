<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <!-- Show error messages -->
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

                <!-- Show success messages -->
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

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                        <p class="text-gray-600 mt-1">Review pesanan Anda sebelum melakukan pembayaran</p>
                    </div>
                    <a href="{{ route('cart.list') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Kembali ke Keranjang
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Selected Products (Left) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Produk yang Dibeli ({{ $totalItems }} item)
                        </h3>
                        
                        @foreach($selectedItems as $item)
                        <div class="flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <!-- Product Image -->
                            <div class="shrink-0">
                                <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/products/images/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-500 mb-2">
                                    Kategori: {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-bold text-red-600">{{ formatCurrency($item->product->price) }}</span>
                                        <span class="text-sm text-gray-500">x {{ $item->quantity }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            {{ formatCurrency($item->product->price * $item->quantity) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h3>
                        
                        @if($addresses && $addresses->count() > 0)
                            <div class="space-y-3 mb-4">
                                @foreach($addresses as $address)
                                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors {{ $address->is_default ? 'border-indigo-500 bg-indigo-50' : '' }}">
                                    <input type="radio" name="shipping_address_id" value="{{ $address->id }}" 
                                           class="mt-1" {{ $address->is_default ? 'checked' : '' }} required>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-medium text-gray-900">{{ $address->label }}</span>
                                            @if($address->is_default)
                                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">Default</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mb-1">{{ $address->recipient_name }} - {{ $address->phone_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $address->full_address }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500 mb-3">Belum ada alamat pengiriman</p>
                                <button type="button" onclick="openAddAddressModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Alamat
                                </button>
                            </div>
                        @endif

                        <!-- Add Address Button (even if addresses exist) -->
                        @if($addresses && $addresses->count() > 0)
                        <div class="mt-4 text-center">
                            <button type="button" onclick="openAddAddressModal()" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Alamat Baru
                            </button>
                        </div>
                        @endif

                        <!-- Shipping Info -->
                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Estimasi Pengiriman</p>
                                    <p class="text-sm text-gray-600">3-5 hari kerja</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Ongkos Kirim</p>
                                    <p class="text-sm text-green-600 font-medium">{{ formatCurrency($shippingCost) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                <input type="radio" name="payment_method" value="midtrans" class="text-indigo-600" checked required>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Midtrans Payment Gateway</p>
                                        <p class="text-sm text-gray-600">Kartu Kredit, Transfer Bank, E-Wallet, QRIS</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary (Right) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 sticky top-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Pembayaran</h3>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $totalItems }} item)</span>
                                <span class="font-medium text-gray-900">{{ formatCurrency($subtotal) }}</span>
                            </div>
                            
                            @if($totalDiscount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="text-green-600 font-medium">-{{ formatCurrency($totalDiscount) }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="text-green-600 font-medium">
                                    {{ $shippingCost > 0 ? formatCurrency($shippingCost) : 'Gratis' }}
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between text-lg font-bold text-gray-900 py-4 border-t border-gray-200 mb-6">
                            <span>Total Pembayaran</span>
                            <span>{{ formatCurrency($total) }}</span>
                        </div>

                        <!-- Payment Button -->
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                            @csrf
                            @foreach($selectedItems as $item)
                            <input type="hidden" name="items[]" value="{{ $item->id }}">
                            @endforeach
                            <input type="hidden" name="shipping_address_id" id="selectedAddressId" value="">
                            <input type="hidden" name="payment_method" value="midtrans">
                            
                            <button type="submit" id="payButton"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-sm">
                                <i class="fas fa-credit-card mr-2"></i> 
                                <span id="payButtonText">Bayar Sekarang</span>
                            </button>
                        </form>

                        <!-- Security Note -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-gray-600">Pembayaran aman dan terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div id="addAddressModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Alamat Baru</h3>
                    <button onclick="closeAddAddressModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <form id="addAddressForm">
                    @csrf
                    <div class="space-y-4">
                        <!-- Label Alamat -->
                        <div>
                            <label for="address_label" class="block text-sm font-medium text-gray-700 mb-1">Label Alamat</label>
                            <input type="text" id="address_label" name="label" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Contoh: Rumah, Kantor, Kos" required>
                        </div>

                        <!-- Nama Penerima -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                            <input type="text" id="recipient_name" name="recipient_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Nama lengkap penerima" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="tel" id="phone_number" name="phone_number" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label for="address_line" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea id="address_line" name="address_line" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Jalan, No. Rumah, RT/RW, Kelurahan" required></textarea>
                        </div>

                        <!-- Kota -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" id="city" name="city" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Nama kota/kabupaten" required>
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <input type="text" id="province" name="province" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Nama provinsi" required>
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" id="postal_code" name="postal_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="12345" required>
                        </div>

                        <!-- Set as Default -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_default" name="is_default" value="1"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_default" class="ml-2 block text-sm text-gray-700">
                                Jadikan alamat utama
                            </label>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeAddAddressModal()" 
                                class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            <span id="addAddressButtonText">Simpan Alamat</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkoutForm');
            const payButton = document.getElementById('payButton');
            const payButtonText = document.getElementById('payButtonText');
            const selectedAddressInput = document.getElementById('selectedAddressId');

            // Handle address selection
            const addressRadios = document.querySelectorAll('input[name="shipping_address_id"]');
            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedAddressInput.value = this.value;
                    console.log('Address selected:', this.value);
                });
            });

            // Set initial address if default exists
            const defaultAddress = document.querySelector('input[name="shipping_address_id"]:checked');
            if (defaultAddress) {
                selectedAddressInput.value = defaultAddress.value;
                console.log('Default address set:', defaultAddress.value);
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                console.log('Form submission started');
                
                // Check if address is selected
                if (!selectedAddressInput.value) {
                    alert('Silakan pilih alamat pengiriman terlebih dahulu.');
                    return;
                }
                
                // Debug form data
                const formData = new FormData(form);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                // Show confirmation
                const confirmed = confirm('Konfirmasi pembayaran sebesar {{ formatCurrency($total) }}?');
                
                if (confirmed) {
                    // Show loading state
                    payButton.disabled = true;
                    payButtonText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                    
                    console.log('Submitting form to:', form.action);
                    
                    // Submit form
                    form.submit();
                } else {
                    // Reset button state if cancelled
                    payButton.disabled = false;
                    payButtonText.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
                }
            });
        });

        // Add Address Modal Functions
        function openAddAddressModal() {
            const modal = document.getElementById('addAddressModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAddAddressModal() {
            const modal = document.getElementById('addAddressModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            
            // Reset form
            document.getElementById('addAddressForm').reset();
        }

        // Handle add address form submission
        document.getElementById('addAddressForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = e.target.querySelector('button[type="submit"]');
            const buttonText = document.getElementById('addAddressButtonText');
            const originalText = buttonText.textContent;
            
            try {
                // Show loading state
                button.disabled = true;
                buttonText.textContent = 'Menyimpan...';
                
                const formData = new FormData(this);
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch('/api/addresses', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal
                    closeAddAddressModal();
                    
                    // Show success message
                    showSuccessMessage('Alamat berhasil ditambahkan! Halaman akan dimuat ulang...');
                    
                    // Reload page to show new address
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    alert(result.message || 'Gagal menambah alamat');
                }
            } catch (error) {
                console.error('Error adding address:', error);
                alert('Terjadi kesalahan saat menambah alamat');
            } finally {
                // Reset button state
                button.disabled = false;
                buttonText.textContent = originalText;
            }
        });

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

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddAddressModal();
            }
        });

        // Close modal when clicking outside
        const addAddressModal = document.getElementById('addAddressModal');
        if (addAddressModal) {
            addAddressModal.addEventListener('click', function(e) {
                if (e.target === addAddressModal) {
                    closeAddAddressModal();
                }
            });
        }
    </script>
</x-app-layout>
