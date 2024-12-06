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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_warehouse');
            $table->unsignedBigInteger('to_warehouse');
            $table->unsignedBigInteger('from_shelves')->nullable();
            $table->unsignedBigInteger('to_shelves')->nullable();
            $table->dateTime('transfer_date');
            $table->string('status')->default('Beklemede');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Yabancı anahtar ilişkileri
            $table->foreign('from_warehouse')->references('id')->on('warehouses');
            $table->foreign('to_warehouse')->references('id')->on('warehouses');
            $table->foreign('from_shelves')->references('id')->on('shelves')->nullable();
            $table->foreign('to_shelves')->references('id')->on('shelves')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
