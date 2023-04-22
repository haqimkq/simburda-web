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
        Schema::create('proyeks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_manager_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_proyek');
            $table->string('foto')->nullable();
            $table->string('alamat');
            $table->string('client');
            $table->string('provinsi');
            $table->string('kota');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('selesai')->default(false);
            $table->timestamp('tgl_selesai')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyeks');
    }
};
