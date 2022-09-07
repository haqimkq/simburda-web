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
        Schema::create('barangs', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->string('qrcode')->nullable();
            $table->string('nama');
            $table->integer('nomor_seri');
            $table->string('jenis');
            $table->string('gambar');
            // $table->integer('jumlah');
            $table->text('alamat');
            $table->double('latitude');
            $table->double('longitude');
            // $table->string('berat')->nullable();
            $table->string('satuan');
            $table->boolean('bagus')->default(true);
            $table->boolean('tersedia')->default(true);
            $table->string('kondisi')->nullable();
            $table->text('detail');
            $table->string('excerpt');
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
        Schema::dropIfExists('barangs');
    }
};
