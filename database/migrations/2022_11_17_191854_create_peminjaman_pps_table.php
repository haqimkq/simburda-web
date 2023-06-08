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
        Schema::create('peminjaman_pps', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('peminjaman_pps', function (Blueprint $table) {
            $table->foreignUuid('peminjaman_asal_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('peminjaman_pps');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
