<?php

namespace Database\Factories;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
use App\Models\Penggunaan;
use App\Models\PenggunaanDetail;
use App\Models\PenggunaanGp;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

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
    public function configure(){
        return $this->afterCreating(function (SjPengirimanGp $sjPengirimanGp) {
            $peminjamanGp = ($sjPengirimanGp->peminjamanGp) ? $sjPengirimanGp->peminjamanGp : $sjPengirimanGp->penggunaanGp;
            $peminjaman = ($sjPengirimanGp->peminjamanGp) ? $sjPengirimanGp->peminjamanGp->peminjaman : $sjPengirimanGp->penggunaanGp->penggunaan;
            $proyek = $peminjaman->menangani->proyek;
            $user = $peminjaman->menangani->user;
            if($peminjaman->tipe == PeminjamanTipe::GUDANG_PROYEK->value){
                $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
                SuratJalan::find($sjPengirimanGp->suratJalan->id)->update([
                    'tipe' => SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value,
                    'admin_gudang_id' => $admin_gudang_id,
                    'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value, $proyek->client, $user->nama,$peminjaman->created_at),
                    'updated_at' => $peminjaman->created_at,
                    'created_at' => $peminjaman->created_at,
                ]);
                if($sjPengirimanGp->peminjamanGp){
                    $sjPengirimanGp->peminjamanGp->update([
                        'updated_at' => $peminjaman->created_at,
                        'created_at' => $peminjaman->created_at,
                    ]);
                }
                if($sjPengirimanGp->penggunaanGp){
                    $sjPengirimanGp->penggunaanGp->update([
                        'updated_at' => $peminjaman->created_at,
                        'created_at' => $peminjaman->created_at,
                    ]);
                }
            }
            // if($peminjaman->tipe == PeminjamanTipe::PROYEK_PROYEK->value){
            //     $peminjamanPp = PeminjamanPp::find('peminjaman_id', $peminjaman->id);
            //     $sjPengirimanPp = SjPengirimanPp::find('peminjaman_id', $peminjamanPp->id);
            //     $admin_gudang_id = $peminjamanPp->peminjamanAsal->gudang->adminGudang->random(1)->all()[0]->user_id;
            //     SuratJalan::find($sjPengirimanPp->suratJalan->id)->update([
            //         'tipe' => SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value,
            //         'admin_gudang_id' => $admin_gudang_id,
            //         'updated_at' => $peminjaman->created_at,
            //         'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value, $proyek->client, $user->nama),
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
        return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
            $sj = SuratJalan::factory()->selesaiSj()->create();
            $withPenggunaan = fake()->boolean();
            $penggunaanGp = null;
            if ($withPenggunaan){
                if($peminjamanGp->peminjaman->status=='DIPINJAM')
                $penggunaanGp = Penggunaan::factory()->dipinjamGpNoSj()
                        ->has(PenggunaanDetail::factory(rand(3,10))->resetData(), 'penggunaanDetail')
                        ->create()->penggunaanGp->id;
                else
                $penggunaanGp = Penggunaan::factory()->selesaiGpNoSj()
                        ->has(PenggunaanDetail::factory(rand(3,10))->resetData(), 'penggunaanDetail')
                        ->create()->penggunaanGp->id;
            }
            return [
                'id' => fake()->uuid(),
                'peminjaman_id' => $peminjamanGp->id,
                'penggunaan_id' => $penggunaanGp,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
    public function dalamPerjalanan(){
        return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
            $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
            $withPenggunaan = fake()->boolean();
            $penggunaanGp = null;
            if ($withPenggunaan){
                $penggunaanGp = Penggunaan::factory()->sedangDikirimGpNoSj()
                        ->has(PenggunaanDetail::factory(rand(3,10))->resetData(), 'penggunaanDetail')
                        ->create()->penggunaanGp->id;
            }
            return [
                'id' => fake()->uuid(),
                'peminjaman_id' => $peminjamanGp->id,
                'penggunaan_id' => $penggunaanGp,
                'surat_jalan_id' => $sj->id,
            ];
        });
    }
    public function menunggu(){
        return $this->state(function (array $attributes, PeminjamanGp $peminjamanGp){
            $sj = SuratJalan::factory()->menungguSj()->create();
            $withPenggunaan = fake()->boolean();
            $penggunaanGp = null;
            if ($withPenggunaan){
                $penggunaanGp = Penggunaan::factory()->menungguPengirimanGpNoSj()
                        ->has(PenggunaanDetail::factory(rand(3,10))->resetData(), 'penggunaanDetail')
                        ->create()->penggunaanGp->id;
            }
            return [
                'id' => fake()->uuid(),
                'peminjaman_id' => $peminjamanGp->id,
                'penggunaan_id' => $penggunaanGp,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
    public function selesaiPenggunaan(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->selesaiSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'peminjaman_id' => null,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
    public function dalamPerjalananPenggunaan(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'peminjaman_id' => null,
                'surat_jalan_id' => $sj->id,
            ];
        });
    }
    public function menungguPenggunaan(){
        return $this->state(function (array $attributes, PenggunaanGp $penggunaanGp){
            $sj = SuratJalan::factory()->menungguSj()->create();
            return [
                'id' => fake()->uuid(),
                'penggunaan_id' => $penggunaanGp->id,
                'peminjaman_id' => null,
                'surat_jalan_id' => $sj->id
            ];
        });
    }
}
