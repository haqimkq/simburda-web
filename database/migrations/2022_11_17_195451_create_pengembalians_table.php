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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_pengembalian');
            $table->enum('status',['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI'])->default('MENUNGGU_PENGEMBALIAN');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pengembalians', function (Blueprint $table) {
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
        Schema::dropIfExists('pengembalians');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
