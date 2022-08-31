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
        Schema::create('menanganis', function (Blueprint $table) {
            // $table->foreignId('supervisor_id')->unsigned();
            // $table->foreign('supervisor_id')->references('id')->on('users');
            // $table->foreignId('projek_id');
            $table->timestamps();
        });
        Schema::table('menanganis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supervisor_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('proyek_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menanganis');
    }
};
