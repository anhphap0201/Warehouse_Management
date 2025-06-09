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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('supplier_name'); // Giữ lại để tương thích ngược
            $table->string('supplier_phone')->nullable();
            $table->text('supplier_address')->nullable();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2)->default(0); // Tổng cuối cùng sau giảm giá và thuế
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable(); // Ghi chú nội bộ
            $table->timestamps();
            
            // Index cho performance
            $table->index(['warehouse_id', 'status']);
            $table->index(['supplier_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
