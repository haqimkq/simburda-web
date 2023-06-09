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
        Schema::create('barang_habis_pakais', function (Blueprint $table) {
            $table->integer('jumlah');
            $table->string('ukuran');
            $table->string('satuan');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('barang_habis_pakais', function (Blueprint $table) {
            $table->foreignUuid('barang_id')->constrained('barangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('barang_habis_pakais');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
