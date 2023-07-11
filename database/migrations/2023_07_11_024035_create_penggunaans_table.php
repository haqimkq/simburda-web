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
        Schema::create('penggunaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_penggunaan');
            $table->string('penggunaan_type');
            $table->uuid('penggunaan_id');
            $table->enum('status',['MENUNGGU_AKSES','AKSES_DITOLAK','MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM', 'DIPINJAM', 'SELESAI'])->default('MENUNGGU_AKSES');
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
        Schema::dropIfExists('penggunaans');
    }
};
