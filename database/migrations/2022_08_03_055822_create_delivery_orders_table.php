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
            $table->enum('status', ['menunggu_konfirmasi_admin_gudang', 'menunggu_konfirmasi_driver','driver_dalam_perjalanan', 'selesai'])->default('menunggu_konfirmasi_admin_gudang');
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
