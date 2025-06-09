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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->enum('type', ['receive_request', 'return_request']); // Loại yêu cầu
            $table->string('title'); // Tiêu đề thông báo
            $table->text('message'); // Nội dung thông báo
            $table->json('data')->nullable(); // Dữ liệu chi tiết (products, quantities, etc.)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('admin_notes')->nullable(); // Ghi chú của admin
            $table->text('admin_response')->nullable();
            $table->boolean('is_read')->default(false); // Đã đọc chưa
            $table->timestamps();
            
            // Index cho performance
            $table->index(['store_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
