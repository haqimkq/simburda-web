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
        Schema::create('peminjaman_details',function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status',['MENUNGGU_AKSES','DIGUNAKAN','TIDAK_DIGUNAKAN','DIPINJAM_PROYEK_LAIN','DIKEMBALIKAN'])->default('MENUNGGU_AKSES');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('peminjaman_details', function (Blueprint $table) {
            $table->foreignUuid('peminjaman_proyek_lain_id')->nullable()->constrained('peminjaman_pps')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('barang_id')->constrained('barang_tidak_habis_pakais')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('penanggung_jawab_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('peminjaman_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('peminjaman_details');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
