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
        Schema::create('logistics', function (Blueprint $table) {
            // $table->foreignId('logistic_id');
            // $table->foreign('logistic_id')->references('id')->on('users');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('logistics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('logistic_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistics');
    }
};
