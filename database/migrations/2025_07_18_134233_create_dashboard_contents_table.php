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
        Schema::create('dashboard_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_text')->nullable(); // e.g., "Starting from"
            $table->string('button_text')->default('Shop Now');
            $table->string('button_link')->nullable();
            $table->string('background_color')->default('#ffffff');
            $table->string('text_color')->default('#1f2937');
            $table->enum('size', ['small', 'medium', 'large'])->default('medium');
            $table->enum('type', ['promo', 'featured', 'category'])->default('promo');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_contents');
    }
};
