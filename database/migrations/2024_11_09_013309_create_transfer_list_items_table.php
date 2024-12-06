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
        Schema::create('transfer_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_list_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('stock_id');
            $table->integer('quantity')->nullable();
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('from_shelves_id')->nullable()->constrained('shelves');
            $table->foreignId('to_shelves_id')->nullable()->constrained('shelves');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_list_items');
    }
};
