const APP_URL = window.location.origin;

/**
 * Product Management Functions
 */

// Edit product - fetch product data and populate form
async function edit_product(id) {
    try {
        const response = await axios.get(`/api/product/${id}`);
        const product = response.data;

        // Populate form fields
        $('#id-update').val(product.id);
        $('#preview-image')
            .attr('src', `${APP_URL}/storage/products/images/${product.image}`)
            .show();
        $('#name').val(product.name);
        $('#description').val(product.description);
        $('#price').val(product.price);
        $('#category_id').val(product.category_id).trigger('change');
        $('#stock').val(product.stock);

        // Show drawer
        $('#drawer-update-product-default')
            .removeClass('translate-x-full')
            .addClass('translate-x-0');

        // Close product modal if open
        if (window.productDetailModal) {
            window.productDetailModal.closeModal();
        }

    } catch (error) {
        console.error('Error fetching product:', error);
        alert('Gagal memuat data produk');
    }
}

// Update product - submit form data
async function update_product(e) {
    e.preventDefault();

    const id = $('#id-update').val();
    
    if (!id) {
        alert('ID produk tidak ditemukan');
        return;
    }

    // Create FormData for file upload
    const formData = new FormData();
    
    // Add image file if selected
    const imageFile = $('#image')[0].files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }
    
    // Add other form fields
    formData.append('name', $('#name').val());
    formData.append('description', $('#description').val());
    formData.append('price', $('#price').val());
    formData.append('stock', $('#stock').val());
    formData.append('category_id', $('#category_id').val());

    try {
        const response = await axios.post(`/api/product/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log('Product updated:', response.data);
        
        // Hide drawer
        $('#drawer-update-product-default')
            .removeClass('translate-x-0')
            .addClass('translate-x-full');
        
        // Reload page after animation
        setTimeout(() => {
            window.location.reload();
        }, 800);

    } catch (error) {
        console.error('Error updating product:', error);
        
        if (error.response?.data?.errors) {
            // Show validation errors
            const errors = error.response.data.errors;
            let errorMessage = 'Validation errors:\n';
            Object.keys(errors).forEach(key => {
                errorMessage += `${key}: ${errors[key].join(', ')}\n`;
            });
            alert(errorMessage);
        } else {
            alert('Gagal memperbarui produk');
        }
    }
}

async function edit_category(id) {
      try {
            axios.get(`/api/category/${id}`).then(response => {
                  let category = response.data.category

                  console.log(category)
      
                  $("#id-update").val(category.id);
                  $("#name").val(category.name);
                  $("#description").val(category.description);
      
                  $("#drawer-update-product-default").removeClass('translate-x-full').addClass('translate-x-0')
            })
      } catch(error) {
            console.log(error)
      }
      
}

async function update_category(e) {
      e.preventDefault()

      var id = $('#id-update').val();

      console.log(id)

      let formData = {
            name: $('#name').val(),
            description: $('#description').val(),
      }

      try{
            axios.put(`/api/category/${id}`, formData).then(response => {
                  console.log(response.data)
                  $("#drawer-update-product-default").removeClass('translate-x-0').addClass('translate-x-full')
                  window.setTimeout(function (){
                        window.location.reload()
                  }, 800)
            }).catch(error => {
                  console.log(error)
            })
      } catch(error) {
            console.log(error)
      }
}