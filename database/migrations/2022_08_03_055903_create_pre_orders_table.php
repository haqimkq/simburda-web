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
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_po');
            $table->string('nama_material');
            $table->string('satuan');
            $table->string('ukuran');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->foreignUuid('delivery_order_id')->constrained('delivery_orders')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pre_orders');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
