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
        $role = fake('id_ID')->randomElement(['PROJECT_MANAGER', 'PURCHASING', 'LOGISTIC', 'SUPERVISOR', 'ADMIN_GUDANG', 'USER']);
        $gender = fake('id_ID')->randomElement(['male', 'female', 'pixel']);
        $ttd = fake('id_ID')->randomElement(['assets/ttd/83719273uawey02938he.png', 'assets/ttd/92673616-2c3a-3338-a435-2d79d880833e.png', 'assets/ttd/awdjawoueuy2803910382938djq3e.png', 'assets/ttd/TTD - 1477f10f-6e0f-3f32-b878-120e38f58689.png', 'assets/ttd/no238193he012938.png', 'assets/ttd/hjgawedyahwdh2837289371jh.png', 'assets/ttd/TTD - 9505e956-d21f-388f-bb54-bcf5e5380dca.png', 'assets/ttd/TTD - c00b586d-0dc1-3953-8bd3-808eb242cd70.png', 'assets/ttd/TTD - db88039f-fe82-3b6f-a7ba-77ed938d8e96.png', 'assets/ttd/TTD - bea6913c-209a-3b52-9ecb-16c31f78525c.png', 'assets/ttd/TTD - df5a8571-67f0-375a-94c6-49dba4b5594e.png', 'assets/ttd/TTD - fafaa894-9571-3054-9c76-f1bb8cb791d6.png', 'assets/ttd/uawyeu2893jaskdh893qu23ajkw.png']);
        
        $randomNumber = $gender!='pixel' ? fake('id_ID')->numberBetween(0,78) : fake('id_ID')->numberBetween(0,53);
        $name = $gender!='pixel' ? fake('id_ID')->name($gender) : fake('id_ID')->name();
        $picture = "https://xsgames.co/randomusers/assets/avatars/$gender/$randomNumber.jpg";

        return [
            'id' => fake('id_ID')->uuid(),
            'nama' => $name,
            'email' => fake('id_ID')->safeEmail(),
            'role' => $role,
            'foto' => $picture,
            'ttd' => $ttd,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'no_hp' => fake('id_ID')->phoneNumber()
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
