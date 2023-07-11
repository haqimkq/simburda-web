<?php

namespace Database\Factories;

use App\Enum\PenggunaanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\PenggunaanGp;
use App\Models\PenggunaanPp;
use App\Models\SjPenggunaanGp;
use App\Models\SjPenggunaanPp;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SjPenggunaanPp>
 */
class SjPenggunaanPpFactory extends Factory
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
            'penggunaan_id' => PenggunaanGp::factory()
        ];
    }
    public function configure(){
        return $this->afterCreating(function (SjPenggunaanGp $sjPenggunaanGp) {
            $penggunaanPp = $sjPenggunaanGp->penggunaanGp;
            $penggunaan = $sjPenggunaanGp->penggunaanGp->penggunaan;
            $proyek = $penggunaan->menangani->proyek;
            $user = $penggunaan->menangani->user;
            // if($penggunaan->tipe == PenggunaanTipe::GUDANG_PROYEK->value){
            //     $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
            //     SuratJalan::find($sjPenggunaanGp->suratJalan->id)->update([
            //         'tipe' => SuratJalanTipe::PENGGUNAAN_GUDANG_PROYEK->value,
            //         'admin_gudang_id' => $admin_gudang_id,
            //         'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGGUNAAN_GUDANG_PROYEK->value, $proyek->client, $user->nama),
            //         'updated_at' => $penggunaan->created_at,
            //         'created_at' => $penggunaan->created_at,
            //     ]);
            //     $penggunaanPp->update([
            //         'updated_at' => $penggunaan->created_at,
            //         'created_at' => $penggunaan->created_at,
            //     ]);
            // }
            if($penggunaan->tipe == PenggunaanTipe::PROYEK_PROYEK->value){
                $penggunaanPp = PenggunaanPp::find('penggunaan_id', $penggunaan->id);
                $sjPengirimanPp = SjPenggunaanPp::find('penggunaan_id', $penggunaanPp->id);
                $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
                SuratJalan::find($sjPengirimanPp->suratJalan->id)->update([
                    'tipe' => SuratJalanTipe::PENGGUNAAN_PROYEK_PROYEK->value,
                    'admin_gudang_id' => $admin_gudang_id,
                    'updated_at' => $penggunaan->created_at,
                    'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGGUNAAN_PROYEK_PROYEK->value, $proyek->client, $user->nama),
                    'created_at' => $penggunaan->created_at,
                ]);
                $penggunaanPp->update([
                    'updated_at' => $penggunaan->created_at,
                    'created_at' => $penggunaan->created_at,
                ]);
            }
        });
    }
    public function selesai(){
        return $this->state(function (array $attributes, PenggunaanPp $penggunaanPp){
            $sj = SuratJalan::factory()->selesaiSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanPp->id,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
    public function dalamPerjalanan(){
        return $this->state(function (array $attributes, PenggunaanPp $penggunaanPp){
            $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanPp->id,
                'surat_jalan_id' => $sj->id,
            ];
        });
    }
    public function menunggu(){
        return $this->state(function (array $attributes, PenggunaanPp $penggunaanPp){
            $sj = SuratJalan::factory()->menungguSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanPp->id,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
}
