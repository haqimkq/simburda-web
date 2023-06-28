<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SjPengirimanGpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'surat_jalan_id' => SuratJalan::factory(),
            'peminjaman_id' => PeminjamanGp::factory()
        ];
    }
    public function selesai(){
        // return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
        //     return [
        //         'peminjaman_id' => $peminjamanGp->id,
        //     ];
        // })->for(SuratJalan::factory()->pengirimanGp()->selesai());
        return SuratJalan::factory()->pengirimanGp()->selesai()->has(
            $this->state(function (array $attributes, PeminjamanGp $peminjamanGp, SuratJalan $sj){
                return [
                    'surat_jalan_id' => $sj->id,
                    'peminjaman_id' => $peminjamanGp->id,
                ];
            })
        );
    }
    public function dalamPerjalanan(){
        return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
            return [
                'peminjaman_id' => $peminjamanGp->id,
            ];
        })->for(SuratJalan::factory()->pengirimanGp()->dalamPerjalanan());
        // return SuratJalan::factory()->pengirimanGp()->dalamPerjalanan()->has(
        //     $this->state(function (array $attributes, PeminjamanGp $peminjamanGp, SuratJalan $sj){
        //         return [
        //             'surat_jalan_id' => $sj->id,
        //             'peminjaman_id' => $peminjamanGp->id,
        //         ];
        //     })
        // );
    }
    public function menunggu(){
        return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
            return [
                'peminjaman_id' => $peminjamanGp->id,
            ];
        })->for(SuratJalan::factory()->pengirimanGp()->menunggu());
        // return SuratJalan::factory()->pengirimanGp()->menunggu()->has(
        //     $this->state(function (array $attributes, PeminjamanGp $peminjamanGp, SuratJalan $sj){
        //         return [
        //             'surat_jalan_id' => $sj->id,
        //             'peminjaman_id' => $peminjamanGp->id,
        //         ];
        //     })
        // );
    }
}
