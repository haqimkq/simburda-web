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
            $table->uuid('id')->primary();
            $table->boolean('disetujui_admin')->nullable();
            $table->boolean('disetujui_pm')->nullable();
            $table->string('keterangan_pm')->nullable();
            $table->string('keterangan_admin')->nullable();
            $table->foreignId('peminjaman_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('project_manager_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('admin_gudang_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('akses_barangs');
    }
};
