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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('brand')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->nullable();
            $table->boolean('status')->default(true);
            $table->string('image')->nullable();
            $table->json('attributes')->nullable(); // Các thuộc tính khác như màu sắc, kích thước, etc.
            $table->timestamps();
            
            // Index cho performance
            $table->index(['category_id', 'status']);
            $table->index(['sku', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
