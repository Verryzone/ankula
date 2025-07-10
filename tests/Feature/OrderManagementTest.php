<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Addresses;

// Test admin can access orders list
test('admin can access orders index', function () {
    $admin = User::factory()->create([
        'email' => 'admin@test.com',
        'role' => 'admin'
    ]);

    $response = $this->actingAs($admin)
                     ->get(route('management.orders.index'));

    $response->assertStatus(200);
});

// Test admin can view order details
test('admin can view order details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $address = Addresses::factory()->create(['user_id' => $customer->id]);
    
    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'shipping_address_id' => $address->id,
        'status' => 'processing'
    ]);

    $response = $this->actingAs($admin)
                     ->get(route('management.orders.show', $order->id));

    $response->assertStatus(200);
});

// Test admin can update order status
test('admin can update order status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $address = Addresses::factory()->create(['user_id' => $customer->id]);
    
    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'shipping_address_id' => $address->id,
        'status' => 'processing'
    ]);

    $response = $this->actingAs($admin)
                     ->patch(route('management.orders.update-status', $order->id), [
                         'status' => 'completed'
                     ]);

    $response->assertRedirect();
    
    $order->refresh();
    expect($order->status)->toBe('completed');
});

// Test customer cannot access order management
test('customer cannot access order management', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)
                     ->get(route('management.orders.index'));

    $response->assertStatus(403);
});
