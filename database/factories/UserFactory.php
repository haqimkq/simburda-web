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
        $gender = fake()->randomElement(['male', 'female', 'pixel']);
        $randomNumber = $gender!='pixel' ? fake()->numberBetween(0,78) : fake()->numberBetween(0,53);
        $name = $gender!='pixel' ? fake()->name($gender) : fake()->name();
        $picture = "https://xsgames.co/randomusers/assets/avatars/$gender/$randomNumber.jpg";

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
