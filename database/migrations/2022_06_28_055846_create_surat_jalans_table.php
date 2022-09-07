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
            // $table->id();
            // $table->foreignId('logistic_id');
            // $table->foreign('logistic_id')->references('id')->on('users');
            // $table->foreignId('kendaraan_id');
            // $table->foreignId('meminjam_id');
            $table->uuid('id')->primary();
            $table->string('ttd_admin')->nullable();
            $table->string('ttd_driver')->nullable();
            $table->string('ttd_penerima')->nullable();
            $table->double('latitude_tujuan');
            $table->double('longitude_tujuan');
            $table->string('alamat_tujuan');
            $table->string('alamat_asal');
            $table->double('longitude_asal');
            $table->double('latitude_asal');
            $table->string('foto_bukti')->nullable();
            $table->boolean('diterima')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->foreignUuid('logistic_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('kendaraan_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
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
