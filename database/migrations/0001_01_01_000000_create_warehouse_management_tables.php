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
        // Table: users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Table: password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Table: sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Table: cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Table: cache_locks
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Table: categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            
            // Indexes
            $table->index(['name'], 'categories_parent_id_name_index');
        });

        // Table: suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Indexes
            $table->index(['name', 'status'], 'suppliers_name_status_index');
        });

        // Table: warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->timestamps();
        });

        // Table: stores
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->timestamps();
        });

        // Table: products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('unit');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['category_id'], 'products_category_id_status_index');
        });

        // Table: purchase_orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('supplier_name');
            $table->string('supplier_phone')->nullable();
            $table->text('supplier_address')->nullable();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('shipping_cost', 15, 2)->default(0.00);
            $table->decimal('final_amount', 15, 2)->default(0.00);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['warehouse_id', 'status'], 'purchase_orders_warehouse_id_status_index');
            $table->index(['supplier_id', 'status'], 'purchase_orders_supplier_id_status_index');
            $table->index(['status', 'created_at'], 'purchase_orders_status_created_at_index');
        });

        // Table: purchase_order_items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 15, 2);
            $table->integer('received_quantity')->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['purchase_order_id', 'product_id'], 'purchase_order_items_purchase_order_id_product_id_index');
        });

        // Table: inventory
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();
            
            // Unique constraint and indexes
            $table->unique(['product_id', 'warehouse_id'], 'inventory_product_id_warehouse_id_unique');
            $table->index(['warehouse_id', 'quantity'], 'inventory_warehouse_id_quantity_index');
            $table->index(['product_id', 'quantity'], 'inventory_product_id_quantity_index');
        });

        // Table: store_inventories
        Schema::create('store_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(1000);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('average_cost', 15, 2)->nullable();
            $table->timestamp('last_movement_at')->nullable();
            $table->timestamp('last_sale_at')->nullable();
            $table->integer('total_sold')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint and indexes
            $table->unique(['store_id', 'product_id'], 'store_inventories_store_id_product_id_unique');
            $table->index(['store_id', 'quantity'], 'store_inventories_store_id_quantity_index');
            $table->index(['product_id', 'quantity'], 'store_inventories_product_id_quantity_index');
        });

        // Table: stock_movements
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->enum('type', ['IN', 'OUT']);
            $table->integer('quantity');
            $table->timestamp('date');
            $table->string('reference_note')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'warehouse_id'], 'stock_movements_product_id_warehouse_id_index');
            $table->index(['type', 'date'], 'stock_movements_type_date_index');
        });

        // Table: notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->enum('type', ['receive_request', 'return_request']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('admin_response')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['store_id', 'status'], 'notifications_store_id_status_index');
            $table->index(['status', 'created_at'], 'notifications_status_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order due to foreign key constraints
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('store_inventories');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};