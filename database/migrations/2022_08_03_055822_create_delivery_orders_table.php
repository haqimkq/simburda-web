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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchasing_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('logistic_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignUuid('kendaraan_id')->constrained('kendaraans')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('perusahaan_id')->constrained('perusahaans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('kode_do');
            $table->enum('status', ['MENUNGGU_KONFIRMASI_ADMIN_GUDANG', 'MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI'])->default('MENUNGGU_KONFIRMASI_ADMIN_GUDANG');
            $table->string('untuk_perhatian');
            $table->string('perihal');
            $table->string('foto_bukti');
            $table->timestamp('tgl_pengambilan');
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
        Schema::dropIfExists('delivery_orders');
    }
};
