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
        Schema::create('sj_penggunaan_pps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ttd_supervisor_peminjam')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('sj_penggunaan_pps', function (Blueprint $table) {
            $table->foreignUuid('penggunaan_id')->constrained('penggunaan_pps')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sj_penggunaan_pps');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
