<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
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
        $peminjaman = Peminjaman::doesntHave('aksesBarang')->get()->random();
        $meminjamId = $peminjaman->id;
        $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
        $menangani = Menangani::where('id', $peminjaman->menangani->id)->first();
        $project_manager_id = $menangani->proyek->projectManager->id;
        $disetujui_admin = fake()->optional()->boolean(50);
        $disetujui_pm = fake()->optional()->boolean(50);
        $keterangan_pm = NULL;
        $keterangan_admin = NULL;
        if(!$disetujui_admin){
            $keterangan_admin = fake()->text(20);
        }
        if(!$disetujui_pm){
            $keterangan_pm = fake()->text(20);
        }
        return [
            'id' => fake()->uuid(),
            'peminjaman_id' => $meminjamId,
            'disetujui_admin' => $disetujui_admin,
            'keterangan_admin' => $keterangan_admin,
            'keterangan_pm' => $keterangan_pm,
            'admin_gudang_id' => $admin_gudang_id,
            'project_manager_id' => $project_manager_id,
            'disetujui_pm' => $disetujui_pm,
        ];
    }
    public function needAccessWithPeminjaman(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'disetujui_admin' => NULL,
                'disetujui_pm' => NULL,
                'admin_gudang_id' => NULL,
                'project_manager_id' => $peminjaman->menangani->proyek->projectManager->id,
                'peminjaman_id' => $peminjaman->id,
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        });
    }
    public function accessNotGrantedWithPeminjaman(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            $ditolak_pm = fake()->boolean();
            $admin_gudang_id = ($peminjaman->peminjamanGp != null) 
                ? $peminjaman->peminjamanGp->gudang->adminGudang->random(1)->all()[0]['user_id']
                : $peminjaman->peminjamanPp->peminjamanAsal->gudang->adminGudang->random(1)->all()[0]['user_id'];
            return [
                'disetujui_admin' => false,
                'disetujui_pm' => $ditolak_pm,
                'keterangan_pm' => (!$ditolak_pm) ? fake()->text() : null,
                'keterangan_admin' => fake()->text(),
                'admin_gudang_id' => $admin_gudang_id,
                'project_manager_id' => $peminjaman->menangani->proyek->projectManager->id,
                'peminjaman_id' => $peminjaman->id,
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        });
    }
    public function accessGrantedWithPeminjaman(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            $admin_gudang_id = ($peminjaman->peminjamanGp != null) 
                ? $peminjaman->peminjamanGp->gudang->adminGudang->random(1)->all()[0]['user_id']
                : $peminjaman->peminjamanPp->peminjamanAsal->gudang->adminGudang->random(1)->all()[0]['user_id'];
            return [
                'disetujui_admin' => true,
                'disetujui_pm' => true,
                'admin_gudang_id' => $admin_gudang_id,
                'project_manager_id' => $peminjaman->menangani->proyek->projectManager->id,
                'peminjaman_id' => $peminjaman->id,
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        });
    }
}
