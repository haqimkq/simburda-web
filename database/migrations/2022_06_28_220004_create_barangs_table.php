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
            $table->string('qrcode');
            $table->string('nama');
            $table->string('jenis');
            $table->string('gambar');
            $table->integer('jumlah');
            $table->string('alamat');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('berat');
            $table->boolean('bagus')->default(true);
            $table->string('kondisi')->nullable();
            $table->text('detail');
            $table->string('excerpt');
            $table->timestamps();
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
