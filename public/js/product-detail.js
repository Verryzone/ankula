/**
 * Product Detail Modal Management
 * Handles product detail modal display and interactions
 */

class ProductDetailModal {
    constructor() {
        this.currentProduct = null;
        this.userRole = window.userRole || 'guest';
        this.isAuthenticated = window.isAuthenticated || false;
        this.init();
    }

    /**
     * Initialize modal and event listeners
     */
    init() {
        this.bindEvents();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Close modal when clicking outside
        $(document).on('click', '#productModal', (e) => {
            if (e.target.id === 'productModal') {
                this.closeModal();
            }
        });

        // Close modal with ESC key
        $(document).on('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });

        // Quantity controls
        $(document).on('click', '.btn-quantity-increase', () => this.increaseQuantity());
        $(document).on('click', '.btn-quantity-decrease', () => this.decreaseQuantity());

        // Action buttons
        $(document).on('click', '#btnBuyNow', () => this.buyNow());
        $(document).on('click', '#btnAddToCart', () => this.addToCart());
    }

    /**
     * Show product detail modal
     * @param {number} productId - Product ID to display
     */
    async showModal(productId) {
        try {
            // Show modal with loading state
            this.showLoadingState();
            
            const response = await axios.get(`/api/product/${productId}/detail`);
            
            if (response.data.success) {
                this.currentProduct = response.data.data;
                this.populateModal(this.currentProduct);
                this.showActionButtons();
            } else {
                this.showError('Gagal memuat detail produk');
                this.closeModal();
            }
        } catch (error) {
            console.error('Error fetching product details:', error);
            this.showError('Terjadi kesalahan saat memuat detail produk');
            this.closeModal();
        }
    }

    /**
     * Show modal with loading state
     */
    showLoadingState() {
        const modal = $('#productModal');
        modal.removeClass('hidden').addClass('flex');
        
        // Prevent body scroll and fix mobile viewport issues
        $('body').addClass('modal-open').css({
            'overflow': 'hidden',
            'position': 'fixed',
            'width': '100%'
        });
        
        // Show loading content
        $('#modalProductName').text('Memuat produk...');
        $('#modalProductImage').attr('src', '');
        $('#modalProductCategory').text('');
        $('#modalProductDescription').text('');
        $('#modalProductPrice').text('');
        $('#modalProductStock').text('');
        $('#quantity').val(1);
        
        // Focus trap for accessibility
        modal.focus();
    }

    /**
     * Populate modal with product data
     * @param {object} product - Product data
     */
    populateModal(product) {
        $('#modalProductImage').attr('src', `/storage/products/images/${product.image}`);
        $('#modalProductName').text(product.name);
        $('#modalProductCategory').text(product.category ? product.category.name : 'Tanpa Kategori');
        $('#modalProductDescription').text(product.description);
        $('#modalProductPrice').text(this.formatCurrency(product.price));
        $('#modalProductStock').text(product.stock);
        $('#quantity').val(1).attr('max', product.stock);
    }

    /**
     * Show appropriate action buttons based on user role
     */
    showActionButtons() {
        const buttonContainer = $('#actionButtons');
        buttonContainer.empty();

        // Add inline styles to ensure buttons are always styled
        const buttonBaseStyle = `
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            transition: all 0.2s ease-in-out !important;
            text-decoration: none !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            margin-bottom: 12px !important;
            font-size: 16px !important;
        `;

        if (!this.isAuthenticated) {
            // Show login prompt for guests
            buttonContainer.html(`
                <div class="text-center space-y-3">
                    <p class="text-gray-600 mb-4">Silakan login untuk melakukan pembelian</p>
                    <a href="/login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 inline-block text-center shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                       style="${buttonBaseStyle} background-color: #2563eb !important; color: white !important;">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login Sekarang
                        </span>
                    </a>
                </div>
            `);
        } else if (this.userRole === 'customer') {
            // Show customer actions
            buttonContainer.html(`
                <div class="space-y-3">
                    <button id="btnBuyNow" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                            style="${buttonBaseStyle} background-color: #2563eb !important; color: white !important;">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"></path>
                            </svg>
                            Beli Sekarang
                        </span>
                    </button>
                    <button id="btnAddToCart" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" 
                            style="${buttonBaseStyle} background-color: #1f2937 !important; color: white !important;">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                            Masukkan Keranjang
                        </span>
                    </button>
                </div>
            `);
        } else if (this.userRole === 'admin') {
            // Show admin actions
            buttonContainer.html(`
                <div class="space-y-3">
                    <button onclick="edit_product(${this.currentProduct.id})" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" 
                            style="${buttonBaseStyle} background-color: #059669 !important; color: white !important;">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Produk
                        </span>
                    </button>
                    <button onclick="push_state(${this.currentProduct.id})" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" 
                            style="${buttonBaseStyle} background-color: #dc2626 !important; color: white !important;">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Produk
                        </span>
                    </button>
                </div>
            `);
        }

        // Add hover effects using JavaScript since CSS might not work
        this.addButtonHoverEffects();
    }

    /**
     * Close product modal
     */
    closeModal() {
        $('#productModal').addClass('hidden').removeClass('flex');
        
        // Restore body scroll and remove mobile fixes
        $('body').removeClass('modal-open').css({
            'overflow': '',
            'position': '',
            'width': ''
        });
        
        this.currentProduct = null;
    }

    /**
     * Increase quantity
     */
    increaseQuantity() {
        const $quantityInput = $('#quantity');
        const currentValue = parseInt($quantityInput.val());
        const maxStock = parseInt($quantityInput.attr('max'));
        
        if (currentValue < maxStock) {
            $quantityInput.val(currentValue + 1);
        }
    }

    /**
     * Decrease quantity
     */
    decreaseQuantity() {
        const $quantityInput = $('#quantity');
        const currentValue = parseInt($quantityInput.val());
        
        if (currentValue > 1) {
            $quantityInput.val(currentValue - 1);
        }
    }

    /**
     * Buy now action
     */
    buyNow() {
        if (!this.currentProduct) return;
        
        const quantity = $('#quantity').val();
        const $btnBuyNow = $('#btnBuyNow');
        const originalText = 'Beli Sekarang'; // Fixed text since we use HTML content
        
        // Show loading state
        $btnBuyNow.html(`
            <span class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Memproses...
            </span>
        `).prop('disabled', true);
        
        // Add product to cart first, then redirect to checkout
        this.buyNowProcess(quantity, $btnBuyNow, originalText);
    }

    /**
     * Process buy now by adding to cart then redirecting to checkout
     */
    async buyNowProcess(quantity, $btnBuyNow, originalText) {
        try {
            // Get CSRF token
            const token = $('meta[name="csrf-token"]').attr('content');
            
            // Add product to cart first
            const response = await axios.post('/api/cart/add', {
                product_id: this.currentProduct.id,
                quantity: parseInt(quantity)
            }, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                withCredentials: true
            });

            if (response.data.success) {
                // Get the newly added cart item ID
                const cartItemId = response.data.cart_item_id;
                
                // Show success message briefly
                $btnBuyNow.html(`
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Berhasil! Mengarahkan...
                    </span>
                `).css('background-color', '#059669');
                
                // Redirect to checkout with the cart item
                setTimeout(() => {
                    if (cartItemId) {
                        window.location.href = `/checkout?items[]=${cartItemId}`;
                    } else {
                        // Fallback: redirect to cart page
                        window.location.href = '/cart';
                    }
                }, 1000);
            } else {
                this.showError('Gagal memproses pesanan');
                this.resetBuyNowButton($btnBuyNow, originalText);
            }
        } catch (error) {
            console.error('Error processing buy now:', error);
            
            if (error.response?.status === 401) {
                this.showError('Silakan login terlebih dahulu');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else if (error.response?.status === 403) {
                this.showError('Anda tidak memiliki akses untuk melakukan pembelian');
            } else {
                const errorMessage = error.response?.data?.message || 'Terjadi kesalahan saat memproses pesanan';
                this.showError(errorMessage);
            }
            
            this.resetBuyNowButton($btnBuyNow, originalText);
        }
    }

    /**
     * Reset buy now button to original state
     */
    resetBuyNowButton($btnBuyNow, originalText) {
        $btnBuyNow
            .html(`
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"></path>
                    </svg>
                    ${originalText}
                </span>
            `)
            .prop('disabled', false);
    }

    /**
     * Add to cart action
     */
    async addToCart() {
        if (!this.currentProduct) return;
        
        const quantity = $('#quantity').val();
        const $btnAddToCart = $('#btnAddToCart');
        const originalText = $btnAddToCart.text();
        
        try {
            // Show loading state
            $btnAddToCart.text('Menambahkan...').prop('disabled', true);
            
            // Get CSRF token
            const token = $('meta[name="csrf-token"]').attr('content');
            
            const response = await axios.post('/api/cart/add', {
                product_id: this.currentProduct.id,
                quantity: parseInt(quantity)
            }, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                withCredentials: true  // This ensures cookies/session are sent
            });

            if (response.data.success) {
                $btnAddToCart
                    .text('✓ Berhasil Ditambahkan!')
                    .css({
                        'background-color': '#059669',
                        'color': 'white'
                    })
                    .removeClass('bg-gray-800 hover:bg-gray-900')
                    .addClass('bg-green-600 hover:bg-green-700');
                
                setTimeout(() => {
                    this.closeModal();
                }, 1500);
            } else {
                this.showError('Gagal menambahkan produk ke keranjang');
            }
        } catch (error) {
            console.error('Full error object:', error);
            console.error('Error response:', error.response);
            
            if (error.response?.status === 401) {
                this.showError('Silakan login terlebih dahulu');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else if (error.response?.status === 403) {
                this.showError('Anda tidak memiliki akses untuk menambahkan ke keranjang');
            } else if (error.response?.status === 422) {
                // Validation errors
                const errors = error.response.data.errors;
                let errorMessage = 'Validation errors: ';
                Object.keys(errors).forEach(key => {
                    errorMessage += `${key}: ${errors[key].join(', ')} `;
                });
                this.showError(errorMessage);
            } else {
                const errorMessage = error.response?.data?.message || 'Terjadi kesalahan saat menambahkan ke keranjang';
                this.showError(errorMessage);
            }
        } finally {
            // Reset button state after delay
            setTimeout(() => {
                $btnAddToCart
                    .text(originalText)
                    .prop('disabled', false)
                    .css({
                        'background-color': '#1f2937',
                        'color': 'white'
                    })
                    .removeClass('bg-green-600 hover:bg-green-700')
                    .addClass('bg-gray-800 hover:bg-gray-900');
            }, 2000);
        }
    }

    /**
     * Format currency to Indonesian Rupiah
     * @param {number} amount - Amount to format
     * @returns {string} Formatted currency
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    /**
     * Show error message
     * @param {string} message - Error message to display
     */
    showError(message) {
        alert(message); // You can replace this with a more elegant notification system
    }

    /**
     * Add hover effects to buttons using JavaScript
     */
    addButtonHoverEffects() {
        // Remove any existing event listeners to avoid duplicates
        $('#actionButtons').off('mouseenter mouseleave');
        
        // Add hover effects
        $('#actionButtons').on('mouseenter', 'button:not([disabled]), a', function() {
            const $this = $(this);
            const id = $this.attr('id');
            const onclick = $this.attr('onclick');
            
            // Apply hover effects based on button type
            if (id === 'btnBuyNow' || $this.attr('href') === '/login') {
                $this.css('background-color', '#1d4ed8');
            } else if (id === 'btnAddToCart') {
                $this.css('background-color', '#111827');
            } else if (onclick && onclick.includes('edit_product')) {
                $this.css('background-color', '#047857');
            } else if (onclick && onclick.includes('push_state')) {
                $this.css('background-color', '#b91c1c');
            }
            
            $this.css({
                'transform': 'translateY(-2px) scale(1.02)',
                'box-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
            });
        });
        
        $('#actionButtons').on('mouseleave', 'button:not([disabled]), a', function() {
            const $this = $(this);
            const id = $this.attr('id');
            const onclick = $this.attr('onclick');
            
            // Reset to original colors
            if (id === 'btnBuyNow' || $this.attr('href') === '/login') {
                $this.css('background-color', '#2563eb');
            } else if (id === 'btnAddToCart') {
                $this.css('background-color', '#1f2937');
            } else if (onclick && onclick.includes('edit_product')) {
                $this.css('background-color', '#059669');
            } else if (onclick && onclick.includes('push_state')) {
                $this.css('background-color', '#dc2626');
            }
            
            $this.css({
                'transform': 'translateY(0) scale(1)',
                'box-shadow': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)'
            });
        });
        
        // Add click effects (only for enabled buttons)
        $('#actionButtons').on('mousedown', 'button:not([disabled]), a', function() {
            $(this).css('transform', 'translateY(0) scale(0.95)');
        });
        
        $('#actionButtons').on('mouseup', 'button:not([disabled]), a', function() {
            const $this = $(this);
            if (!$this.prop('disabled')) {
                $this.css('transform', 'translateY(-2px) scale(1.02)');
            }
        });
    }
}

// Initialize ProductDetailModal when document is ready
$(document).ready(function() {
    window.productDetailModal = new ProductDetailModal();
});

/**
 * Global function to show product detail (called from product cards)
 * @param {number} productId - Product ID to display
 */
function showProductDetail(productId) {
    if (window.productDetailModal) {
        window.productDetailModal.showModal(productId);
    }
}
