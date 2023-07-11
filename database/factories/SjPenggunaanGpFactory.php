<?php

namespace Database\Factories;

use App\Enum\PenggunaanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\PenggunaanGp;
use App\Models\SjPenggunaanGp;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SjPenggunaanGp>
 */
class SjPenggunaanGpFactory extends Factory
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
            $penggunaanGp = $sjPenggunaanGp->peminjamanGp;
            $peminjaman = $sjPenggunaanGp->peminjamanGp->peminjaman;
            $proyek = $peminjaman->menangani->proyek;
            $user = $peminjaman->menangani->user;
            if($peminjaman->tipe == PenggunaanTipe::GUDANG_PROYEK->value){
                $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
                SuratJalan::find($sjPenggunaanGp->suratJalan->id)->update([
                    'tipe' => SuratJalanTipe::PENGGUNAAN_GUDANG_PROYEK->value,
                    'admin_gudang_id' => $admin_gudang_id,
                    'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGGUNAAN_GUDANG_PROYEK->value, $proyek->client, $user->nama),
                    'updated_at' => $peminjaman->created_at,
                    'created_at' => $peminjaman->created_at,
                ]);
                $penggunaanGp->update([
                    'updated_at' => $peminjaman->created_at,
                    'created_at' => $peminjaman->created_at,
                ]);
            }
            // if($peminjaman->tipe == PenggunaanTipe::PROYEK_PROYEK->value){
            //     $peminjamanPp = PeminjamanPp::find('penggunaan_id', $peminjaman->id);
            //     $sjPengirimanPp = SjPengirimanPp::find('penggunaan_id', $peminjamanPp->id);
            //     $admin_gudang_id = $peminjamanPp->peminjamanAsal->gudang->adminGudang->random(1)->all()[0]->user_id;
            //     SuratJalan::find($sjPengirimanPp->suratJalan->id)->update([
            //         'tipe' => SuratJalanTipe::PENGGUNAAN_PROYEK_PROYEK->value,
            //         'admin_gudang_id' => $admin_gudang_id,
            //         'updated_at' => $peminjaman->created_at,
            //         'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGGUNAAN_PROYEK_PROYEK->value, $proyek->client, $supervisor->nama),
            //         'created_at' => $peminjaman->created_at,
            //     ]);
            //     $peminjamanPp->update([
            //         'updated_at' => $peminjaman->created_at,
            //         'created_at' => $peminjaman->created_at,
            //     ]);
            // }
        });
    }
    public function selesai(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->selesaiSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
    public function dalamPerjalanan(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'surat_jalan_id' => $sj->id,
            ];
        });
    }
    public function menunggu(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->menungguSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
}
