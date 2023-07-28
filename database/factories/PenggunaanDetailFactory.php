<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Penggunaan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenggunaanDetail>
 */
class PenggunaanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $penggunaan = Penggunaan::get()->random();
        $satuan = NULL;
        $jumlah = NULL;
        $status = NULL;
        $barang = Barang::get()->random();
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'penggunaan_proyek_lain_id' => NULL,
            'status' => $status,
            'penggunaan_id' => $penggunaan->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
    public function resetData(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            // $penggunaan = Penggunaan::find($penggunaan->id);
            $barang_habis_pakai = BarangHabisPakai::whereRelation('barang','gudang_id',$penggunaan->penggunaanGp->gudang_id)->whereDoesntHave('penggunaanDetail', function (Builder $query) use ($penggunaan){
                $query->where('penggunaan_id', $penggunaan->id);
            })->get()->random();
            // $barang_habis_pakai = BarangHabisPakai::where('id', $barang->id)->first();
            $satuan = $barang_habis_pakai->satuan;
            $jumlah = fake()->numberBetween(1, $barang_habis_pakai->jumlah);
            if($penggunaan->status == "DIGUNAKAN"){
                $status = "DIGUNAKAN";
            }else if($penggunaan->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
            }else if($penggunaan->status == "MENUNGGU_SURAT_JALAN" || $penggunaan->status == "MENUNGGU_PENGIRIMAN" || $penggunaan->status == "SEDANG_DIKIRIM"){
                $status = "MENUNGGU_AKSES";
            }
            $jumlah_satuan = $jumlah . ' ' . $satuan;
            return [
                'barang_id' => $barang_habis_pakai->id,
                'status' => $status,
                'penggunaan_id' => $penggunaan->id,
                'jumlah_satuan' => $jumlah_satuan
            ];
        });
    }
}
