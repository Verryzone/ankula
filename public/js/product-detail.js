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
        $('body').css('overflow', 'hidden');
        
        // Show loading content
        $('#modalProductName').text('Memuat produk...');
        $('#modalProductImage').attr('src', '');
        $('#modalProductCategory').text('');
        $('#modalProductDescription').text('');
        $('#modalProductPrice').text('');
        $('#modalProductStock').text('');
        $('#quantity').val(1);
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

        if (!this.isAuthenticated) {
            // Show login prompt for guests
            buttonContainer.html(`
                <div class="text-center space-y-3">
                    <p class="text-gray-600">Silakan login untuk melakukan pembelian</p>
                    <a href="/login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 inline-block text-center">
                        Login Sekarang
                    </a>
                </div>
            `);
        } else if (this.userRole === 'customer') {
            // Show customer actions
            buttonContainer.html(`
                <div class="space-y-3">
                    <button id="btnBuyNow" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Beli Sekarang
                    </button>
                    <button id="btnAddToCart" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Masukkan Keranjang
                    </button>
                </div>
            `);
        } else if (this.userRole === 'admin') {
            // Show admin actions
            buttonContainer.html(`
                <div class="space-y-3">
                    <button onclick="edit_product(${this.currentProduct.id})" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Edit Produk
                    </button>
                    <button onclick="push_state(${this.currentProduct.id})" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Hapus Produk
                    </button>
                </div>
            `);
        }
    }

    /**
     * Close product modal
     */
    closeModal() {
        $('#productModal').addClass('hidden').removeClass('flex');
        $('body').css('overflow', 'auto');
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
        
        // Redirect to checkout with selected product
        window.location.href = `/checkout?product_id=${this.currentProduct.id}&quantity=${quantity}`;
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
                    .text('Berhasil Ditambahkan!')
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
