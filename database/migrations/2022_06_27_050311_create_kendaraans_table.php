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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jenis');
            $table->string('merk');
            // $table->string('kapasitas');
            $table->string('plat_nomor');
            $table->string('gambar');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->foreignUuid('logistic_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kendaraans');
    }
};
