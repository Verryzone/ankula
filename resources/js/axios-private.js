async function edit_product(id) {
      $(`#loading-${id}`).show();
      $(`#editlogo-${id}`).hide();
      try {
            axios.get(`/api/products/${id}`).then(response => {
                  let product = response.data
      
                  $("#product-id-update").val(product.id);
                  $("#name").val(product.name);
                  $("#description").val(product.description);
                  $("#price").val(product.price);
                  $("#stok").val(product.stok);
      
                  // $('.updateProductButton').click(function () {
                  $("#drawer-update-product-default").removeClass('translate-x-full').addClass('translate-x-0')
                  $(`#loading-${id}`).hide();
                  $(`#editlogo-${id}`).show();
                  // })
            })
      } catch(error) {
            console.log(error)
      }
      
}

async function update_product(e) {
      e.preventDefault()
      $(`#loading-gif`).show();

      var id = $('#product-id-update').val();

      console.log(id)

      let formData = {
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),
            stok: $('#stok').val(),
      }

      try{
            axios.put(`/api/products/${id}`, formData).then(response => {
                  console.log(response.data)
                  $(`#loading-gif`).show();
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
