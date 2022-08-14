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
        Schema::create('kendaraans', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('logistic_id');
            // $table->foreign('logistic_id')->references('id')->on('users');
            $table->uuid('id')->primary();
            $table->string('jenis');
            $table->string('merk');
            $table->string('kapasitas');
            $table->string('plat_nomor');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->foreignUuid('logistic_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kendaraans');
    }
};
