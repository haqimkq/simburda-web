<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\ProjectManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proyek>
 */
class ProyekFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lat = fake()->latitude(-6.2,-6.1);
        $lon = fake()->longitude(106.7,106.8);
        // $address = Location::getAddress($lat, $lon);
        $date = fake()->dateTimeBetween('-2 years', 'now');
        $selesai = fake()->boolean();
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        if($selesai) $tgl_selesai = fake()->dateTimeBetween($date, '+2 month');
        else $tgl_selesai = NULL;
        return [
            'id' => fake()->uuid(),
            'project_manager_id' => User::factory()->state(['role' => 'PROJECT_MANAGER']),
            'nama_proyek' => fake()->words(3, true),
            'alamat' => fake()->streetAddress(),
            'kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'foto' => $randomImage,
            'latitude' => $lat,
            'longitude' => $lon,
            'selesai' => $selesai,
            'created_at'=> $date,
            'updated_at'=> $date,
            'tgl_selesai'=> $tgl_selesai,
        ];
    }

    /**
     * Menandakan proyek selesai.
     *
     * @return static
     */
    public function selesai()
    {
        return $this->state(function (array $attributes){
            $createdAt = $attributes['created_at'];
            $tgl_selesai = fake()->dateTimeBetween($createdAt, '+2 month');
            return [
                'selesai' => true,
                'tgl_selesai' => $tgl_selesai,
                'updated_at' => $tgl_selesai
            ];
        });
    }

    /**
     * Menandakan proyek belum selesai.
     *
     * @return static
     */
    public function belumSelesai()
    {
        return $this->state(function (array $attributes){
            $createdAt = $attributes['created_at'];
            return [
                'selesai' => false,
                'tgl_selesai' => NULL,
                'updated_at' => $createdAt
            ];
        });
    }

    /**
     * Mengatur koordinat.
     *
     * @return static
     */
    public function coordinate($lat, $lon)
    {
        return $this->state(function (array $attributes) use ($lat, $lon){
            return [
                'latitude' => $lat,
                'longitude' => $lon
            ];
        });
    }
}
