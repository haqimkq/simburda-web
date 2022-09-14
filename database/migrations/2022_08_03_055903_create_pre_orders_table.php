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
        Schema::create('pre_orders', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('delivery_order_id');
            $table->uuid('id')->primary();
            $table->string('kode_preorder')->nullable();
            $table->string('nama_material');
            $table->string('satuan');
            $table->string('ukuran');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->foreignUuid('delivery_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_orders');
    }
};
