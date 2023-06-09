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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_do');
            $table->enum('status', ['MENUNGGU_KONFIRMASI_ADMIN_GUDANG', 'MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI'])->default('MENUNGGU_KONFIRMASI_ADMIN_GUDANG');
            $table->string('untuk_perhatian');
            $table->string('perihal');
            $table->string('foto_bukti')->nullable();
            $table->timestamp('tgl_pengambilan');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('delivery_orders', function (Blueprint $table) {
            $table->foreignUuid('purchasing_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('logistic_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('admin_gudang_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('kendaraan_id')->nullable()->constrained('kendaraans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('perusahaan_id')->constrained('perusahaans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('ttd')->nullable()->constrained('ttd_verifications')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_orders');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
