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
        Schema::create('pengajuans', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->string('nama_barang');
            $table->string('foto')->nullable();
            $table->integer('jumlah');
            $table->string('satuan');
            $table->double('harga');
            $table->boolean('disetujui')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->foreignUuid('proyek_manager_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('admin_gudang_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuans');
    }
};
