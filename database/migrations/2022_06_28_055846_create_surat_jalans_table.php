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
            $table->foreignUuid('kendaraan_id')->constrained('kendaraans')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->string('kode_surat');
            $table->string('ttd_admin');
            $table->string('ttd_driver')->nullable();
            $table->string('ttd_penerima')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->enum('tipe', ['PENGIRIMAN_PROYEK_PROYEK', 'PENGIRIMAN_GUDANG_PROYEK','PENGEMBALIAN']);
            $table->enum('status', ['MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI'])->default('MENUNGGU_KONFIRMASI_DRIVER');
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
