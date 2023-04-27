<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Peminjaman;
use Carbon\Carbon;
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
        $id = fake()->uuid();
        $peminjaman = Peminjaman::get()->random();
        $barang = Barang::where('gudang_id', $peminjaman->gudang_id)->get()->random();
        $satuan = NULL;
        $jumlah = fake()->randomNumber(50);
        if($barang->jenis == 'TIDAK_HABIS_PAKAI') {
            $satuan = 'Unit';
        }else{
            $satuan = fake()->randomElement(['Lembar', 'Meter', '']);
        }
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        $now = Carbon::now();
        $start_date = Carbon::parse($peminjaman->tgl_peminjaman);
        $end_date = Carbon::parse($peminjaman->tgl_berakhir);

        
        $status = fake()->randomElement(['MENUNGGU_AKSES','DIGUNAKAN','TIDAK_DIGUNAKAN','DIKEMBALIKAN']);
        $sj_pengiriman = ($status!='Menunggu konfirmasi pengiriman') ? SuratJalan::all()->random()->id : NULL;
        $sj_pengembalian = (isset($sj_pengiriman) && $status!='Sedang dipinjam') ? SuratJalan::all()->random()->id : NULL;
        if($now->between($start_date,$end_date))
            Barang::where('id', $barang_id)->update(['peminjaman_id' => $peminjaman_id]);
            // $dipinjam = true;
            $status = 'Sedang dipinjam';
            $sj_pengiriman = SuratJalan::all()->random()->id;
            $sj_pengembalian = NULL;
        return [
            //
            'id' => $id,
            'barang_id' => $barang->id,
            'peminjaman_proyek_lain_id' => NULL,
            'status' => $status,
            'peminjaman_id' => $peminjaman->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
}
