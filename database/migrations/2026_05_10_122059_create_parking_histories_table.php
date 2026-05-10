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
        Schema::create('parking_histories', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->string('hari');
            $table->time('jam');
            $table->enum('jenis_kendaraan', ['sepeda motor', 'mobil']);
            $table->enum('kondisi', ['sepi', 'sedang', 'padat']);
            $table->integer('jumlah_kendaraan');
            $table->string('hasil_keputusan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_histories');
    }
};
