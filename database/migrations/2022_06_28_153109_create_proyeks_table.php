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
        Schema::create('proyeks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_proyek');
            $table->string('foto')->nullable();
            $table->string('alamat');
            $table->string('client');
            $table->string('provinsi');
            $table->string('kota');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('selesai')->default(false);
            $table->timestamps();
            $table->timestamp('tgl_selesai')->nullable();
            $table->softDeletes();
        });
        Schema::table('proyeks', function (Blueprint $table) {
            $table->foreignUuid('project_manager_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('proyeks');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
