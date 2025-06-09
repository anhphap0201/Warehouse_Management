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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->string('manager')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('capacity', 15, 2)->nullable(); // Sức chứa tối đa
            $table->boolean('status')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Index cho performance
            $table->index(['code', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
