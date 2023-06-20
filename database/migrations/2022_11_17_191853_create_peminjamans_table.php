<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_peminjaman');
            $table->enum('tipe',['GUDANG_PROYEK','PROYEK_PROYEK']);
            $table->enum('status',['MENUNGGU_AKSES','AKSES_DITOLAK','MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM', 'DIPINJAM', 'SELESAI'])->default('MENUNGGU_AKSES');
            $table->timestamps();
            $table->timestamp('tgl_peminjaman');
            $table->timestamp('tgl_berakhir');
            $table->softDeletes();
        });
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('peminjamans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
