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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // Số lượng đã đặt trước
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->nullable();
            $table->decimal('average_cost', 15, 2)->nullable(); // Giá nhập trung bình
            $table->timestamp('last_movement_at')->nullable(); // Lần cuối có biến động
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']);
            
            // Index cho performance
            $table->index(['warehouse_id', 'quantity']);
            $table->index(['product_id', 'quantity']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
