<?php

namespace Database\Factories;

use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        $jenis = fake()->randomElement(['MOTOR', 'MOBIL','PICKUP', 'TRUCK', 'TRONTON', 'MINIBUS']);
        $logisticId = User::where('role', 'LOGISTIC')->get()->random()->id;
        $logisticNoKendaraan = Kendaraan::where('logistic_id', $logisticId)->doesntExist();
        $logistic_id = $logisticNoKendaraan ? $logisticId : NULL;
        return [
            'id' => fake()->uuid(),
            'logistic_id' => null,
            'gudang_id' => Gudang::first()->id,
            'jenis' => $jenis,
            'merk' => Fake()->word(),
            'plat_nomor' => $this->generateKodeKendaraan($jenis),
            'gambar' => $randomImage,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }

    public function mobil(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'MOBIL',
                'plat_nomor' => $this->generateKodeKendaraan("MOBIL"),
            ];
        });
    }

    public function minibus(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'MINIBUS',
                'plat_nomor' => $this->generateKodeKendaraan("MINIBUS"),
            ];
        });
    }

    public function truck(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'TRUCK',
                'plat_nomor' => $this->generateKodeKendaraan("TRUCK"),
            ];
        });
    }

    public function tronton(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'TRONTON',
                'plat_nomor' => $this->generateKodeKendaraan("TRONTON"),
            ];
        });
    }

    public function pickup(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'PICKUP',
                'plat_nomor' => $this->generateKodeKendaraan("PICKUP"),
            ];
        });
    }
    public function motor(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'MOTOR',
                'plat_nomor' => $this->generateKodeKendaraan("MOTOR"),
            ];
        });
    }

    public function generateKodeKendaraan($jenis){
        $daerah = fake()->randomElement(['B','C','E','G','K','N','P','S','T','U','V','W','Z']);
        $randLetter = strtoupper(fake()->randomLetter());
        $randLetter2 = strtoupper(fake()->randomLetter());
        $number = fake()->numberBetween(1000,1999);
        
        $kode_kendaraan = NULL;
        $plat_nomor = NULL;
        if($jenis == "MINIBUS"){
            $kode_kendaraan = "F";
        }else if($jenis == "TRUCK" || $jenis == "TRONTON"){
            $kode_kendaraan = "D";
            $number = fake()->numberBetween(8000,9999);
        }else if($jenis == "MOBIL" || $jenis == "PICKUP"){
            $kode_kendaraan = "A";
        }else if($jenis == "MOTOR"){
            $number = fake()->numberBetween(2000,6999);
        }
        $plat_nomor = "$daerah $number $kode_kendaraan$randLetter$randLetter2";
        return $plat_nomor;
    }
}
