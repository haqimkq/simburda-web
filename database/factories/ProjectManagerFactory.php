<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\ProjectManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectManager>
 */
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
            'user_id' => User::where('role', 'PROJECT_MANAGER')->get()->random()->id,
            'kode_pm' => IDGenerator::generateID(ProjectManager::class,'kode_pm',5,'PM')
        ];
    }
}
