<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeminjamanDetail>
 */
class PeminjamanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $surat_jalan = SuratJalan::where()
        $barang_id = Barang::where('tersedia', 1)->get()->random()->id;
        $peminjaman_proyek_lain_id = Peminjaman::where('tipe', 'PROYEK_PROYEK')->get()->random()->id;
        if($barang_id==NULL) return NULL;

        // $dipinjam = false;
        $satuan = Barang::where('id', $barang_id)->first()->satuan;
        $status = fake()->randomElement(['Menunggu konfirmasi pengiriman', 'Sedang dikirim driver','Sedang dipinjam','Menunggu konfirmasi pengembalian', 'Sedang dikembalikan driver','Selesai']);
        $sj_pengiriman = ($status!='Menunggu konfirmasi pengiriman') ? SuratJalan::all()->random()->id : NULL;
        $sj_pengembalian = (isset($sj_pengiriman) && $status!='Sedang dipinjam') ? SuratJalan::all()->random()->id : NULL;
        if($now->between($start_date,$end_date))
            Barang::where('id', $barang_id)->update(['tersedia' => 0]);
            // $dipinjam = true;
            $status = 'Sedang dipinjam';
            $sj_pengiriman = SuratJalan::all()->random()->id;
            $sj_pengembalian = NULL;
        return [
            //
            $table->uuid('id')->primary();
            $table->foreignUuid('peminjaman_proyek_lain_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignUuid('barang_id')->constrained('barangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('peminjaman_id')->constrained('peminjamans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('jumlah_satuan');
            $table->enum('status',['MENUNGGU_AKSES','DIGUNAKAN','TIDAK_DIGUNAKAN','DIPINJAM_PROYEK_LAIN','DIKEMBALIKAN'])->default('MENUNGGU_AKSES');
            $table->timestamps();
            $table->softDeletes();
            'id' => fake()->uuid();
            'peminjaman_proyek_lain_id' => 
        ];
    }
}
