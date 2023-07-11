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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->string('ukuran');
            $table->integer('jumlah');
            $table->string('status');
            $table->enum('status_set_manager',['DITERIMA','DITOLAK','BELUM DIKONFIRMASI'])->default('BELUM DIKONFIRMASI');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreignUuid('set_manager_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('supervisor_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pembelians');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
