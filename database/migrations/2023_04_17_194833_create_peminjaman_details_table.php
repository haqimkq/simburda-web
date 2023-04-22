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
        Schema::create('peminjaman_details',function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('peminjaman_proyek_lain_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignUuid('barang_id')->constrained('barangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('peminjaman_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('jumlah_satuan');
            $table->enum('status',['MENUNGGU_AKSES','DIGUNAKAN','TIDAK_DIGUNAKAN','DIPINJAM_PROYEK_LAIN','DIKEMBALIKAN'])->default('MENUNGGU_AKSES');
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
        Schema::dropIfExists('peminjaman_details');
    }
};
