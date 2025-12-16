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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjualan');
            $table->unsignedBigInteger('id_produk');
            $table->integer('qty');
            $table->integer('harga');
            $table->integer('subtotal');

            $table->timestamps();

            $table->foreign('id_penjualan')->references('id')->on('penjualans')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
