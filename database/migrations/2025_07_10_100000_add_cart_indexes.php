<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better performance
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasIndex('carts', 'carts_user_id_index')) {
                $table->index('user_id');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasIndex('cart_items', 'cart_items_cart_id_index')) {
                $table->index('cart_id');
            }
            if (!Schema::hasIndex('cart_items', 'cart_items_product_id_index')) {
                $table->index('product_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasIndex('orders', 'orders_user_id_index')) {
                $table->index('user_id');
            }
            if (!Schema::hasIndex('orders', 'orders_order_number_index')) {
                $table->index('order_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_user_id_index');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('cart_items_cart_id_index');
            $table->dropIndex('cart_items_product_id_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_id_index');
            $table->dropIndex('orders_order_number_index');
        });
    }
};
