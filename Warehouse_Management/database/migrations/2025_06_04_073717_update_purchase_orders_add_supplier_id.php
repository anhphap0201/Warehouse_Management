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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Thêm foreign key tới suppliers
            $table->foreignId('supplier_id')->nullable()->after('warehouse_id')->constrained()->onDelete('set null');
            
            // Giữ lại supplier_name, supplier_phone, supplier_address để tương thích ngược
            // Trong tương lai có thể xóa sau khi migrate dữ liệu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};
