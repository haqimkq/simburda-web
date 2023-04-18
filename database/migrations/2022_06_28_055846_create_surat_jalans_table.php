<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('logistic_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('admin_gudang_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('kendaraan_id')->constrained('kendaraans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_surat');
            $table->string('ttd_admin');
            $table->string('ttd_driver')->nullable();
            $table->string('ttd_penerima')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->enum('tipe', ['pengiriman-proyek-proyek', 'pengiriman-gudang-proyek','pengembalian']);
            $table->enum('status', ['menunggu_konfirmasi_driver','driver_dalam_perjalanan', 'selesai'])->default('menunggu_konfirmasi_driver');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_jalans');
    }
};
