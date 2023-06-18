<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $peminjaman = Peminjaman::latest()->first();
        $satuan = NULL;
        $jumlah = NULL;
        $status = NULL;
        $barang = Barang::whereDoesntHave('peminjamanDetail', function (Builder $query) use ($peminjaman){
            $query->where('peminjaman_id', $peminjaman->id);
        })->get()->random();

        if($barang->jenis == 'TIDAK_HABIS_PAKAI') {
            $satuan = 'Unit';
            $jumlah = 1;
            if($peminjaman->status == "DIPINJAM"){
                BarangTidakHabisPakai::where('barang_id', $barang->id)->update(['peminjaman_id' => $peminjaman->id]);
                $status = "DIGUNAKAN";
            }else if($peminjaman->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
                BarangTidakHabisPakai::where('barang_id', $barang->id)->update(['peminjaman_id' => NULL]);
            }else if($peminjaman->status == "MENUNGGU_AKSES" || $peminjaman->status == "MENUNGGU_SURAT_JALAN" || $peminjaman->status == "MENUNGGU_PENGIRIMAN" || $peminjaman->status == "SEDANG_DIKIRIM"){
                $status = "MENUNGGU_AKSES";
            }
        }else{
            $barang_habis_pakai = BarangHabisPakai::where('barang_id', $barang->id)->first();
            $satuan = $barang_habis_pakai->satuan;
            $jumlah = fake()->numberBetween(1, $barang_habis_pakai->jumlah);
            if($peminjaman->status == "DIPINJAM"){
                $status = "DIGUNAKAN";
            }else if($peminjaman->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
            }else if($peminjaman->status == "MENUNGGU_AKSES" || $peminjaman->status == "MENUNGGU_SURAT_JALAN" || $peminjaman->status == "MENUNGGU_PENGIRIMAN" || $peminjaman->status == "SEDANG_DIKIRIM"){
                $status = "MENUNGGU_AKSES";
            }
        }
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'peminjaman_proyek_lain_id' => NULL,
            'status' => $status,
            'peminjaman_id' => $peminjaman->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
}
