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
        if (!Schema::hasTable('product_variations')) {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku'); 
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Ürün id'si
            $table->foreignId('variation_option_id')->constrained('variation_options')->onDelete('cascade'); // Varyasyon seçeneği id'si
            $table->string('variation_images', 1000)->nullable();
            $table->foreignId('parent_variation_id')->nullable()->constrained('product_variations')->onDelete('cascade'); // Ana varyant id'si
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
