<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Menangani;
use App\Models\Peminjaman;
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
        $peminjaman = Peminjaman::doesntHave('aksesBarang')->all()->random();
        $meminjamId = $peminjaman->id;
        $admin_gudang_id = User::where('role','ADMIN_GUDANG')->all()->random()->id;
        $menangani = Menangani::where('id', $peminjaman->menangani->id)->get();
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
}
