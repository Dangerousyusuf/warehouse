<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        // Eğer tablo mevcut değilse oluştur
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('action');
                $table->string('model');
                $table->unsignedBigInteger('model_id'); // model_id'yi unsignedBigInteger olarak tanımlayın
                $table->unsignedBigInteger('user_id'); // user_id'yi unsignedBigInteger olarak tanımlayın
                $table->string('description')->nullable(); // Açıklama alanını ekle
                $table->timestamps();

                // Yabancı anahtar kısıtlamasını ekleyin
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
