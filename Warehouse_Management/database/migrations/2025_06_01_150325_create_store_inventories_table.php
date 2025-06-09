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
        Schema::create('store_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // Số lượng đã đặt trước
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(1000);
            $table->decimal('selling_price', 15, 2)->nullable(); // Giá bán tại cửa hàng
            $table->decimal('average_cost', 15, 2)->nullable(); // Giá nhập trung bình
            $table->timestamp('last_movement_at')->nullable(); // Lần cuối có biến động
            $table->timestamp('last_sale_at')->nullable(); // Lần cuối bán
            $table->integer('total_sold')->default(0); // Tổng số đã bán
            $table->text('notes')->nullable();
            $table->timestamps();

            // Đảm bảo sự kết hợp duy nhất của cửa hàng và sản phẩm
            $table->unique(['store_id', 'product_id']);
            
            // Index cho performance
            $table->index(['store_id', 'quantity']);
            $table->index(['product_id', 'quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_inventories');
    }
};
