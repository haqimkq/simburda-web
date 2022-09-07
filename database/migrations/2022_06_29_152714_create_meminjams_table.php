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
            $table->uuid('id')->primary();
            $table->timestamp('tgl_peminjaman')->nullable();
            // $table->integer('jumlah');
            $table->string('satuan');
            $table->timestamp('tgl_berakhir')->nullable();
            $table->boolean('dipinjam')->nullable();
            $table->timestamps();
        });
        Schema::table('meminjams', function (Blueprint $table) {
            $table->foreignUuid('surat_jalan_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('proyek_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('supervisor_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('barang_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
