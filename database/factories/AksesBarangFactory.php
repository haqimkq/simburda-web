<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Meminjam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\aksesBarang>
 */
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
            $meminjamId = Meminjam::all()->random()->id;
            $meminjamIdExist = AksesBarang::where('meminjam_id', $meminjamId)->exists();
        } while ($meminjamIdExist);
        return [
            'id' => fake()->uuid(),
            'meminjam_id' => $meminjamId,
            'disetujui_admin' => fake()->optional()->boolean(50),
            'disetujui_pm' => fake()->optional()->boolean(50),
        ];
    }
}
