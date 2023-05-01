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
        do {
            $peminjaman = Peminjaman::all()->random();
            $meminjamId = $peminjaman->id;
            $admin_gudang_id = User::where('role','ADMIN_GUDANG')->all()->random()->id;
            $menangani = Menangani::where('id', $peminjaman->menangani->id)->get();
            $project_manager_id = $menangani->proyek->projectManager->id;
            $meminjamIdExist = AksesBarang::where('peminjaman_id', $meminjamId)->exists();
        } while ($meminjamIdExist);
        return [
            'id' => fake()->uuid(),
            'peminjaman_id' => $meminjamId,
            'disetujui_admin' => fake()->optional()->boolean(50),
            'admin_gudang_id' => $admin_gudang_id,
            'project_manager_id' => $project_manager_id,
            'disetujui_pm' => fake()->optional()->boolean(50),
        ];
    }
}
