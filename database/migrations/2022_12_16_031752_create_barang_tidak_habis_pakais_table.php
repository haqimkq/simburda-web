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
        Schema::create('barang_tidak_habis_pakais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('qrcode');
            $table->integer('nomor_seri');
            $table->boolean('tersedia')->default(true);
            $table->enum('kondisi', ['Baru', 'Bekas']);
            $table->string('keterangan');
            $table->foreignUuid('barang_id')->constrained('barangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('barang_tidak_habis_pakais');
    }
};
