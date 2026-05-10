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
        Schema::create('parking_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->string('hari');
            $table->time('jam');
            $table->string('jenis_kendaraan');
            $table->string('kondisi');
            $table->integer('jumlah_kendaraan');
            $table->string('label_keputusan_final');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_datasets');
    }
};
