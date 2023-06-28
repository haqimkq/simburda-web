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
        Schema::create('logistics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('logistics', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('logistics');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
