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
        Schema::create('akses_barangs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('disetujui_admin')->nullable();
            $table->boolean('disetujui_pm')->nullable();
            $table->string('keterangan_pm')->nullable();
            $table->string('keterangan_admin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('akses_barangs', function (Blueprint $table) {
            $table->foreignUuid('peminjaman_detail_id')->constrained('peminjaman_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('project_manager_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('admin_gudang_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('akses_barangs');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
