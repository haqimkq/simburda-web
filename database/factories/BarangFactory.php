<?php

namespace Database\Factories;

use App\Helpers\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $jenis = fake()->randomElement(['habis pakai', 'tidak habis pakai']);
        $name = fake()->words(2, true);
        $output_file = NULL;
        if($jenis == 'tidak habis pakai'){
            $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($id);
            $output_file = 'assets/qr-code/Barang-' . $name . '.png';
            Storage::disk('public')->put($output_file, $image);
        }
        $satuan = fake()->randomElement(['buah', 'meter', 'lembar', 'batang', 'kilogram']);

        $lat = fake()->latitude(-6.2,-6.1);
        $lon = fake()->longitude(106.7,106.8);
        // $address = Location::getAddress($lat, $lon);
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);

        return [
            'id' => $id,
            'qrcode' => $output_file,
            'nama' => $name,
            'jenis' => $jenis,
            // 'gambar' => fake()->imageUrl(360, 360, 'barang', true, $jenis, true),
            'gambar' => $randomImage,
            // 'jumlah' => mt_rand(1,5),
            'alamat' => fake()->streetAddress(),
            'latitude' => $lat,
            'longitude' => $lon,
            'detail' => fake()->text(100),
            'satuan' => $satuan,
            'nomor_seri' => 1,
            'excerpt' => fake()->sentence()
        ];
    }
}
