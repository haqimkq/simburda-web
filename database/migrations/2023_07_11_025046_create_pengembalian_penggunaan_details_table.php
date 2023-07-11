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
        Schema::create('pengembalian_penggunaan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jumlah_satuan');
            $table->string('satuan');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pengembalian_penggunaan_details', function (Blueprint $table) {
            $table->foreignUuid('barang_id')->constrained('barangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('pengembalian_penggunaan_id')->constrained('pengembalian_penggunaans')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengembalian_penggunaan_details');
    }
};