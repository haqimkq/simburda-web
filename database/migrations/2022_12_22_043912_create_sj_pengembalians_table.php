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
        Schema::create('sj_pengembalians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('sj_pengembalians', function (Blueprint $table) {
            $table->foreignUuid('surat_jalan_id')->constrained('surat_jalans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sj_pengembalians');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
