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
        Schema::create('meminjams', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('supervisor_id');
            // $table->foreign('supervisor_id')->references('id')->on('users');
            // $table->foreignId('barang_id');
            // $table->foreignId('proyek_id');
            // $table->foreignId('surat_jalan_id')->nullable();
            $table->uuid('id')->primary();
            $table->timestamp('tgl_peminjaman')->nullable();
            $table->integer('jumlah');
            $table->timestamp('tgl_berakhir')->nullable();
            $table->boolean('dipinjam')->default(false);
            $table->timestamps();
        });
        Schema::table('meminjams', function (Blueprint $table) {
            $table->foreignUuid('surat_jalan_id')->constrained()->nullable();
            $table->foreignUuid('proyek_id')->constrained();
            $table->foreignUuid('supervisor_id')->constrained('users');
            $table->foreignUuid('barang_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meminjams');
    }
};
