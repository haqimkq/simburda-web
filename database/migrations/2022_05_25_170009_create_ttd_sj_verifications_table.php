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
            $table->enum('sebagai', ['PEMBERI', 'PENGIRIM','PENERIMA']);
            $table->timestamps();
        });
        Schema::table('ttd_sj_verifications', function (Blueprint $table) {
            $table->foreignUuid('ttd_verification_id')->constrained('ttd_verifications')->onUpdate('cascade')->onDelete('cascade');
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
