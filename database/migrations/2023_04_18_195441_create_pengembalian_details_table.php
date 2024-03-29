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
        Schema::create('pengembalian_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->string('jumlah_satuan');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pengembalian_details', function (Blueprint $table) {
            $table->foreignUuid('barang_id')->constrained('barang_tidak_habis_pakais')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('pengembalian_id')->constrained('pengembalians')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pengembalian_details');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
