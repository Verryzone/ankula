const APP_URL = window.location.origin;

async function edit_product(id) {
      try {
            axios.get(`/api/product/${id}`).then(response => {
                  let product = response.data
      
                  $("#id-update").val(product.id);
                  $("#preview-image").attr("src", `${APP_URL}/storage/products/images/${product.image}`);
                  $("#preview-image").show();
                  $("#name").val(product.name);
                  $("#description").val(product.description);
                  $("#price").val(product.price);
                  $("#category_id").val(product.category_id).trigger('change');
                  $("#stock").val(product.stock);
      
                  $("#drawer-update-product-default").removeClass('translate-x-full').addClass('translate-x-0')
            })
      } catch(error) {
            console.log(error)
      }
      
}

async function update_product(e) {
      e.preventDefault()

      var id = $('#id-update').val();

      console.log(id)

      let formData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            stock: $('#stock').val(),
      }

      try{
            axios.put(`/api/product/${id}`, formData).then(response => {
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