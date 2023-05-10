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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis', ['MOTOR', 'MOBIL','PICKUP', 'TRUCK', 'TRONTON']);
            $table->string('merk');
            $table->string('plat_nomor');
            $table->string('gambar');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->foreignUuid('logistic_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('gudang_id')->nullable()->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('kendaraans');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
