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
            // $table->id();
            // $table->foreignId('proyek_manager_id');
            // $table->foreign('proyek_manager_id')->references('id')->on('users');
            $table->uuid('id')->primary();
            $table->string('nama_proyek');
            $table->string('alamat');
            $table->double('latitude');
            $table->double('longitude');
            $table->boolean('selesai')->default(false);
            $table->timestamps();
        });
        Schema::table('proyeks', function (Blueprint $table) {
            $table->foreignUuid('proyek_manager_id')->constrained('users');
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
