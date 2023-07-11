<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembalian_penggunaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_pengembalian');
            $table->enum('status',['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI'])->default('MENUNGGU_SURAT_JALAN');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('pengembalian_penggunaans', function (Blueprint $table) {
            $table->foreignUuid('penggunaan_id')->constrained('penggunaans')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('pengembalian_penggunaans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
