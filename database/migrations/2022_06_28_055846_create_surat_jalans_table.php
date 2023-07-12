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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_surat');
            $table->string('foto_bukti')->nullable();
            $table->enum('tipe', ['PENGIRIMAN_PROYEK_PROYEK', 'PENGIRIMAN_GUDANG_PROYEK','PENGEMBALIAN','PENGGUNAAN_PROYEK_PROYEK', 'PENGGUNAAN_GUDANG_PROYEK','PENGEMBALIAN_PENGGUNAAN']);
            $table->enum('status', ['MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI'])->default('MENUNGGU_KONFIRMASI_DRIVER');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->foreignUuid('ttd_admin')->nullable()->constrained('ttd_verifications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('ttd_driver')->nullable()->constrained('ttd_verifications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('ttd_tgg_jwb')->nullable()->constrained('ttd_verifications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('logistic_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('admin_gudang_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('kendaraan_id')->nullable()->constrained('kendaraans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('surat_jalans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
