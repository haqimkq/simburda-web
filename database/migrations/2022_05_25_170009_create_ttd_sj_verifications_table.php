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
        Schema::create('ttd_sj_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('sebagai', ['PEMBERI', 'PENGIRIM','PENERIMA']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
        Schema::table('ttd_sj_verifications', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignUuid('surat_jalan_id')->constrained('surat_jalans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('ttd_sj_verifications');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
