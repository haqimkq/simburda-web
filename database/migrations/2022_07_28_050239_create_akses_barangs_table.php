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
        Schema::create('akses_barangs', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('meminjam_id');
            // $table->uuid('meminjam_id');
            $table->uuid('id')->primary();
            $table->boolean('disetujui_admin')->nullable();
            $table->boolean('disetujui_pm')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('akses_barangs', function (Blueprint $table) {
            $table->foreignUuid('meminjam_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akses_barangs');
    }
};
