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
        Schema::create('factory_settings', function (Blueprint $table) {
            $table->id();                             // Benzersiz kimlik
            $table->string('variable');               // Değişken adı
            $table->text('value');                    // Değişken değeri
            $table->timestamps();                     // Oluşturulma ve güncellenme zamanları
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factory_settings');
    }
};
