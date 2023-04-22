<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\Gudang;
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
        $jenis = fake()->randomElement(['HABIS_PAKAI', 'TIDAK_HABIS_PAKAI']);
        $name = fake()->words(2, true);
        $output_file = NULL;
        if($jenis == 'TIDAK_HABIS_PAKAI'){
            $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($id);
            $output_file = 'assets/qr-code/Barang-' . $name . '.png';
            Storage::disk('public')->put($output_file, $image);
        }

        $lat = fake()->latitude(-6.2,-6.1);
        $lon = fake()->longitude(106.7,106.8);
        // $address = Location::getAddress($lat, $lon);
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        
        $gudang_id = Gudang::get()->random()->id;
        $table->uuid('id')->primary();
            $table->string('gambar');
            $table->string('nama');
            $table->string('merk')->nullable();
            $table->enum('jenis',['HABIS_PAKAI', 'TIDAK_HABIS_PAKAI']);
            $table->text('detail');
            $table->foreignUuid('gudang_id')->constrained('gudangs')->onUpdate('cascade')->onDelete('cascade');
            

        return [
            'id' => $id,
            'gudang_id' => $gudang_id,
            'qrcode' => $output_file,
            'nama' => $name,
            'merk' => $merk,
            'jenis' => $jenis,
            // 'gambar' => fake()->imageUrl(360, 360, 'barang', true, $jenis, true),
            'gambar' => $randomImage,
            // 'jumlah' => mt_rand(1,5),
            'alamat' => fake()->streetAddress(),
            'latitude' => $lat,
            'longitude' => $lon,
            'detail' => fake()->text(100),
            'nomor_seri' => 1,
            'excerpt' => fake()->sentence()
        ];
    }
}
