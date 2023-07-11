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
        Schema::create('barangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('gambar');
            $table->string('nama');
            $table->string('tipe');
            $table->string('merk')->nullable();
            $table->text('detail');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('barangs', function (Blueprint $table) {
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
        Schema::dropIfExists('barangs');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
    }
};
