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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // onDelete('restrict') mencegah produk dihapus jika sudah pernah ada di dalam order
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            
            $table->unsignedInteger('quantity');
            
            // Simpan harga produk pada saat transaksi untuk menjaga histori harga
            $table->decimal('price', 15, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
