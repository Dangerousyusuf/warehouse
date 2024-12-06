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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('products')->onDelete('cascade'); // Ürün id'si
            $table->foreignId('variation_option_id')->nullable()->constrained('variation_options')->onDelete('cascade'); // Varyasyon seçeneği id'si
            $table->foreignId('parent_variation_id')->nullable()->constrained('product_variations')->onDelete('cascade'); // Ana varyant id'si
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropForeign(['variation_option_id']);
            $table->dropColumn('variation_option_id');
            $table->dropForeign(['parent_variation_id']);
            $table->dropColumn('parent_variation_id');
        });
    }
};
