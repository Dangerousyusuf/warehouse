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
        if (!Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_code')->unique();
            $table->string('barcode')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('image', 1000)->nullable();
            $table->decimal('standard_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->timestamp('last_restock_date')->nullable();
            $table->integer('total_stock_limit')->nullable();
            $table->string('unit')->nullable();
            $table->string('product_type')->nullable();
            $table->integer('estimated_daily_usage')->nullable();
            $table->integer('estimated_delivery_time')->nullable();
            $table->integer('auto_order_quantity')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('critical_stock_level')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit')->nullable();
            $table->decimal('size_x', 8, 2)->nullable();
            $table->decimal('size_y', 8, 2)->nullable();
            $table->decimal('size_z', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('products')->onDelete('cascade'); // Ürün id'si
            $table->foreignId('variation_option_id')->nullable()->constrained('variation_options')->onDelete('cascade'); // Varyasyon seçeneği id'si
            $table->foreignId('parent_variation_id')->nullable()->constrained('product_variations')->onDelete('cascade'); // Ana varyant id'si
            $table->softDeletes();
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
