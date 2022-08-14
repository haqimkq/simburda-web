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
            // $table->id();
            // $table->foreignId('logistic_id');
            // $table->foreignId('purchasing_id');
            // $table->foreign('logistic_id')->references('id')->on('users');
            // $table->foreign('purchasing_id')->references('id')->on('users');
            // $table->foreignId('kendaraan_id');
            $table->uuid('id')->primary();
            $table->string('kode_delivery')->nullable();
            $table->boolean('diambil')->nullable();
            $table->double('longitude');
            $table->double('latitude');
            $table->string('untuk_perusahaan');
            $table->string('untuk_perhatian');
            $table->string('perihal');
            $table->timestamps();
        });
        Schema::table('delivery_orders', function (Blueprint $table) {
            $table->foreignUuid('purchasing_id')->constrained('users');
            $table->foreignUuid('logistic_id')->constrained('users');
            $table->foreignUuid('kendaraan_id')->constrained();
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
