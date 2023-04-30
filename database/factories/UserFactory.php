<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $role = fake()->randomElement(['PROJECT_MANAGER', 'PURCHASING', 'LOGISTIC', 'SUPERVISOR', 'ADMIN_GUDANG', 'USER']);
        $json = file_get_contents('https://randomuser.me/api'); // Get the JSON content
        $obj=json_decode($json);
        $results = $obj->results[0];
        $name = $results->name->first.' '.$results->name->last;
        $picture = $results->picture->large;

        return [
            'id' => fake()->uuid(),
            'nama' => $name,
            'email' => fake()->safeEmail(),
            'role' => $role,
            'foto' => $picture,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'no_hp' => fake()->phoneNumber()
        ];
    }

    /**
     * Indicate that the model's role should be the same as parameter.
     *
     * @return static
     */
    public function setRole($role)
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'role' => $role,
            ];
        });
    }
}
