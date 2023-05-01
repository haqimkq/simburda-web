<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\ProjectManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::where('role', 'PROJECT_MANAGER')->all()->random()->id,
            'kode_pm' => IDGenerator::generateID(ProjectManager::class,'kode_pm',5,'PM')
        ];
    }
}
