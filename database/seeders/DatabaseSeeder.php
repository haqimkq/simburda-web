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
            'remember_token' => Str::random(10),
            'no_hp' => fake()->phoneNumber()
        ]);
        User::factory(40)->create();
        Barang::factory(20)->create();
        Kendaraan::factory(20)->create();
        Logistic::factory(20)->create();
        DeliveryOrder::factory(20)->create();
        PreOrder::factory(20)->create();
        Proyek::factory(20)->create();
        Menangani::factory(20)->create();
        SuratJalan::factory(20)->create();
        Meminjam::factory(20)->create();
        AksesBarang::factory(20)->create();
    }
}
