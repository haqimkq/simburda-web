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
        Schema::create('peminjaman_gps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('peminjaman_gps', function (Blueprint $table) {
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('peminjaman_gps');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
