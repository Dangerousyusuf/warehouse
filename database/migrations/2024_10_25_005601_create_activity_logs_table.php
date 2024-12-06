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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Yapılan işlem
            $table->string('model'); // Hangi model üzerinde işlem yapıldığı
            $table->unsignedBigInteger('model_id'); // Modelin ID'si
            $table->text('description')->nullable(); // İşlem açıklaması
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // user_id alanını users tablosundaki id alanına referans veriyoruz
            $table->timestamps(); // Oluşturulma ve güncellenme tarihleri
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
