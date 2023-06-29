<?php

namespace Database\Factories;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\Gudang;
use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
use App\Models\PeminjamanPp;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeminjamanGp>
 */
class PeminjamanGpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gudang_id' => Gudang::get()->random()->id,
            'peminjaman_id' => Peminjaman::factory(),
        ];
    }
    // public function configure(){
    //     return $this->afterCreating(function (PeminjamanGp $peminjamanGp) {
    //         $peminjaman = $peminjamanGp->peminjaman;
    //         $proyek = $peminjaman->menangani->proyek;
    //         $supervisor = $peminjaman->menangani->supervisor;
    //         if($peminjaman->tipe == PeminjamanTipe::GUDANG_PROYEK->value){
    //             $sjPengirimanGp = SjPengirimanGp::where('peminjaman_id', $peminjamanGp->id)->get();
    //             $admin_gudang_id = $peminjamanGp->gudang->adminGudang->random(1)->all()[0]->user_id;
    //             SuratJalan::find($sjPengirimanGp->suratJalan->id)->update([
    //                 'tipe' => SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value,
    //                 'admin_gudang_id' => $admin_gudang_id,
    //                 'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value, $proyek->client, $supervisor->nama),
    //                 'updated_at' => $peminjaman->created_at,
    //                 'created_at' => $peminjaman->created_at,
    //             ]);
    //             $peminjamanGp->update([
    //                 'updated_at' => $peminjaman->created_at,
    //                 'created_at' => $peminjaman->created_at,
    //             ]);
    //             if($peminjaman->status == PeminjamanStatus::SELESAI->value){
    //                 $sjPengembalian = SjPengembalian::where('peminjaman_id', $peminjaman->id)->get();
    //                 SuratJalan::find($sjPengembalian->suratJalan->id)->update([
    //                     'tipe' => SuratJalanTipe::PENGEMBALIAN->value,
    //                     'admin_gudang_id' => $admin_gudang_id,
    //                     'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGEMBALIAN->value, $proyek->client, $supervisor->nama),
    //                     'updated_at' => $peminjaman->created_at,
    //                     'created_at' => $peminjaman->created_at,
    //                 ]);
    //             }
    //         }
    //         // if($peminjaman->tipe == PeminjamanTipe::PROYEK_PROYEK->value){
    //         //     $peminjamanPp = PeminjamanPp::find('peminjaman_id', $peminjaman->id);
    //         //     $sjPengirimanPp = SjPengirimanPp::find('peminjaman_id', $peminjamanPp->id);
    //         //     $admin_gudang_id = $peminjamanPp->peminjamanAsal->gudang->adminGudang->random(1)->all()[0]->user_id;
    //         //     SuratJalan::find($sjPengirimanPp->suratJalan->id)->update([
    //         //         'tipe' => SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value,
    //         //         'admin_gudang_id' => $admin_gudang_id,
    //         //         'updated_at' => $peminjaman->created_at,
    //         //         'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value, $proyek->client, $supervisor->nama),
    //         //         'created_at' => $peminjaman->created_at,
    //         //     ]);
    //         //     $peminjamanPp->update([
    //         //         'updated_at' => $peminjaman->created_at,
    //         //         'created_at' => $peminjaman->created_at,
    //         //     ]);
    //         // }
    //     });
    // }
    public function withPeminjaman(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'id' => fake()->uuid(),
                'gudang_id' => Gudang::get()->random()->id,
                'peminjaman_id' => $peminjaman->id,
                'created_at' => $peminjaman->created_at,
                'updated_at' => $peminjaman->updated_at,
            ];
        });
    }
}
