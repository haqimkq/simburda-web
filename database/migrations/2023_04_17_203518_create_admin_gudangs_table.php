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
        Schema::create('admin_gudangs', function (Blueprint $table) {
            $table->string('kode_ag');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('admin_gudangs', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('admin_gudangs');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
