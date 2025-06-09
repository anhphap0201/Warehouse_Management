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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 15, 2);
            $table->integer('received_quantity')->default(0); // Số lượng đã nhận
            $table->decimal('discount_amount', 12, 2)->default(0); // Số tiền giảm giá
            $table->decimal('tax_amount', 12, 2)->default(0); // Số tiền thuế
            $table->text('notes')->nullable();
            $table->timestamp('received_at')->nullable(); // Thời gian nhận hàng
            $table->timestamps();
            
            // Index cho performance
            $table->index(['purchase_order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
