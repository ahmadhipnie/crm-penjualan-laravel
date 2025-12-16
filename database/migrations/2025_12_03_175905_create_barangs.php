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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('sku_barang');
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->text('material');
            $table->integer('harga');
            $table->integer('berat');
            $table->integer('stok');
            $table->timestamps();

            $table->foreign('id_kategori')->references('id')->on('kategoris')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
