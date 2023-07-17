<?php

namespace Database\Factories;

use App\Models\Proyek;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Builder;
class MenanganiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $proyek = Proyek::get()->random();
        $user = User::where('role','SUPERVISOR')->orWhere('role','SITE_MANAGER')->whereDoesntHave('proyeks', function (Builder $query) use ($proyek){
            $query->where('proyek_id', $proyek->id);
        })->get()->random();
        return [
            'id' => fake()->uuid(),
            'user_id' => $user->id,
            'proyek_id' => $proyek->id,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }
}
