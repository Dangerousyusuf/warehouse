<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variation_id')->nullable()->constrained('product_variations')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('type'); 
            $table->string('stock_out')->nullable(); 
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('from_shelves_id')->nullable()->constrained('shelves');
            $table->foreignId('to_shelves_id')->nullable()->constrained('shelves');
            $table->string('note')->nullable(); 
            $table->boolean('transfer_status')->default(1);
            $table->foreignId('created_user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('update_user')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
