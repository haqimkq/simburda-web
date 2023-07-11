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
        Schema::create('penggunaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_penggunaan');
            $table->enum('tipe',['GUDANG_PROYEK','PROYEK_PROYEK']);
            $table->enum('status',['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM', 'DIGUNAKAN', 'SELESAI'])->default('MENUNGGU_AKSES');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('penggunaans', function (Blueprint $table) {
            $table->foreignUuid('menangani_id')->constrained('menanganis')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('penggunaans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
