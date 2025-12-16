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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jenis_ekspedisi')->nullable();
            $table->string('kode_transaksi');
            $table->string('nomor_resi')->nullable();
            $table->string('snap_token');
            $table->text('alamat_pengiriman');
            $table->enum('status', ['menunggu_pembayaran', 'sedang_diproses', 'dikirim', 'sampai', 'selesai', 'dibatalkan']);
            $table->date('prakiraan_tanggal_sampai')->nullable();
            $table->string('gambar_bukti_sampai')->nullable();
            $table->integer('total_harga');

            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_jenis_ekspedisi')->references('id')->on('jenis_ekspedisis')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
