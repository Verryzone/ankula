import $ from 'jquery';

$(document).ready(function() {
    $('#createProductButton').click(function() {
        $("#drawer-create-product-default").removeClass('translate-x-full').addClass('translate-x-0')
        console.log('Create Product Drawer Opened');
    });

    $('#drawer-cancel').click(function() {
        $("#drawer-create-product-default").removeClass('translate-x-0').addClass('translate-x-full')
    });

    $('.drawer-close').click(function() {
        $("#drawer-create-product-default").removeClass('translate-x-0').addClass('translate-x-full')
    });
});
