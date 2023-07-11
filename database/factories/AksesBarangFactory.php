<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Menangani;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanDetailGp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AksesBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $peminjamanDetail = PeminjamanDetail::doesntHave('aksesBarang')->get()->random();
        $meminjamId = $peminjamanDetail->id;
        $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
        $menangani = Menangani::where('id', $peminjamanDetail->peminjaman->menangani->id)->first();
        $set_manager_id = $menangani->proyek->setManager->id;
        $disetujui_admin = fake()->optional()->boolean(50);
        $disetujui_sm = fake()->optional()->boolean(50);
        $keterangan_sm = NULL;
        $keterangan_admin = NULL;
        if(!$disetujui_admin){
            $keterangan_admin = fake()->text(20);
        }
        if(!$disetujui_sm){
            $keterangan_sm = fake()->text(20);
        }
        return [
            'id' => fake()->uuid(),
            'peminjaman_detail_id' => $meminjamId,
            'disetujui_admin' => $disetujui_admin,
            'keterangan_admin' => $keterangan_admin,
            'keterangan_sm' => $keterangan_sm,
            'admin_gudang_id' => $admin_gudang_id,
            'set_manager_id' => $set_manager_id,
            'disetujui_sm' => $disetujui_sm,
        ];
    }
    public function needAccessWithPeminjamanDetail(){
        return $this->state(function (array $attributes, PeminjamanDetail $peminjamanDetail){
            return [
                'disetujui_admin' => NULL,
                'disetujui_sm' => NULL,
                'admin_gudang_id' => NULL,
                'set_manager_id' => $peminjamanDetail->peminjaman->menangani->proyek->setManager->id, 
                'peminjaman_detail_id' => $peminjamanDetail->id,
                'updated_at' => $peminjamanDetail->peminjaman->created_at,
                'created_at' => $peminjamanDetail->peminjaman->created_at
            ];
        });
    }
    public function accessNotGrantedWithPeminjamanDetail(){
        return $this->state(function (array $attributes, PeminjamanDetail $peminjamanDetail){
            $ditolak_sm = fake()->boolean();
            $admin_gudang_id = User::where('role', 'ADMIN_GUDANG')->get()->random()->id;
            return [
                'disetujui_admin' => false,
                'disetujui_sm' => $ditolak_sm,
                'keterangan_sm' => (!$ditolak_sm) ? fake()->text() : null,
                'keterangan_admin' => fake()->text(),
                'admin_gudang_id' => $admin_gudang_id,
                'set_manager_id' => $peminjamanDetail->peminjaman->menangani->proyek->setManager->id,
                'peminjaman_detail_id' => $peminjamanDetail->id,
                'updated_at' => $peminjamanDetail->peminjaman->created_at,
                'created_at' => $peminjamanDetail->peminjaman->created_at
            ];
        });
    }
    public function accessGrantedWithPeminjamanDetail(){
        return $this->state(function (array $attributes, PeminjamanDetail $peminjamanDetail){
            $admin_gudang_id = User::where('role', 'ADMIN_GUDANG')->get()->random()->id;
            return [
                'disetujui_admin' => true,
                'disetujui_sm' => true,
                'admin_gudang_id' => $admin_gudang_id,
                'set_manager_id' => $peminjamanDetail->peminjaman->menangani->proyek->setManager->id,
                'peminjaman_detail_id' => $peminjamanDetail->id,
                'updated_at' => $peminjamanDetail->peminjaman->created_at,
                'created_at' => $peminjamanDetail->peminjaman->created_at
            ];
        });
    }
}
