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
        Schema::create('penggunaan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jumlah_satuan');
            $table->enum('status',['MENUNGGU_AKSES','DIGUNAKAN','TIDAK_DIGUNAKAN','DIGUNAKAN_PROYEK_LAIN','DIKEMBALIKAN'])->default('MENUNGGU_AKSES');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('peminjaman_details', function (Blueprint $table) {
            $table->foreignUuid('penggunaan_proyek_lain_id')->nullable()->constrained('penggunaan_pps')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('barang_id')->constrained('barang_habis_pakais')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('penggunaan_details');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
