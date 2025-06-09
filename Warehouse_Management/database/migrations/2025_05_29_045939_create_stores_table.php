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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('manager')->nullable();
            $table->string('type')->default('branch'); // flagship, branch, outlet, etc.
            $table->decimal('area', 8, 2)->nullable(); // Diện tích m2
            $table->integer('capacity')->nullable(); // Sức chứa tối đa
            $table->boolean('status')->default(true);
            $table->text('description')->nullable();
            $table->json('operating_hours')->nullable(); // Giờ hoạt động
            $table->timestamps();
            
            // Index cho performance
            $table->index(['code', 'status']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
