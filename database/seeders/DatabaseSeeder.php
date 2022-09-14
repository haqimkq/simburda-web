<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\Logistic;
use App\Models\Meminjam;
use App\Models\PreOrder;
use App\Models\Kendaraan;
use App\Models\menangani;
use App\Models\SuratJalan;
use App\Models\AksesBarang;
use Illuminate\Support\Str;
use App\Models\DeliveryOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'nama' => 'Pangeran Muhammad',
            'password' => Hash::make('123qweasd'),
            'email' => 'pangeranwaliyullah@gmail.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'remember_token' => Str::random(10),
            'no_hp' => fake()->phoneNumber()
        ]);
        User::factory(40)->create();
        Barang::factory(100)->create();
        for ($i=0; $i < 20; $i++) { 
            Kendaraan::factory(1)->create();
        }
        Logistic::factory(20)->create();
        DeliveryOrder::factory(50)->create();
        PreOrder::factory(100)->create();
        Proyek::factory(20)->create();
        Menangani::factory(20)->create();
        SuratJalan::factory(20)->create();
        Meminjam::factory(100)->create();
        for ($i=0; $i < 100; $i++) { 
            AksesBarang::factory(1)->create();
        }
    }
}
