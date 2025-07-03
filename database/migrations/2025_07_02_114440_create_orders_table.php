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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke alamat pengiriman. onDelete('set null') agar jika alamat dihapus, data order tetap ada.
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null');

            $table->string('order_number')->unique(); // Nomor order unik, misal: INV/2025/07/XYZ
            $table->decimal('total_amount', 15, 2); // Total harga akhir
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->text('shipping_address_snapshot'); // Simpan salinan alamat dalam bentuk teks/json untuk arsip
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
