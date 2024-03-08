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
        Schema::create('detail__penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penjualan');
            $table->string('nama');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__penjualans');
    }
};
