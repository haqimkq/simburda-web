<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $role = 'user';
        $role = fake()->randomElement(['project manager', 'purchasing', 'logistic', 'supervisor', 'admin gudang', 'user']);
        $name = fake()->name();
        $firstName = explode(' ', $name, 2)[0];
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        
        return [
            'id' => fake()->uuid(),
            'nama' => $name,
            'email' => fake()->safeEmail(),
            'role' => $role,
            // 'foto' => fake()->imageUrl(360, 360, 'user', true, $firstName, true),
            'foto' => $randomImage,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'no_hp' => fake()->phoneNumber()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
