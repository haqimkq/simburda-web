<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangTidakHabisPakai;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangTidakHabisPakaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nomor_seri = 1;
        $kondisi = fake()->randomElement(['BARU', 'BEKAS']);
        $keterangan = fake()->randomElement(['OK', 'Butuh Service']);
        // $barang = Barang::where('jenis', 'TIDAK_HABIS_PAKAI')->doesntHave('barangTidakHabisPakai')->get()->random();
        // $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($id);
        // $output_file = "assets/qr-code/$id.png";
        // Storage::disk('public')->put($output_file, $image);
        return [
            // 'qrcode' => $output_file,
            'keterangan' => $keterangan,
            'kondisi' => $kondisi,
            'nomor_seri' => $nomor_seri,
            'barang_id' => Barang::factory()->state(['jenis' => 'TIDAK_HABIS_PAKAI']),
            
        ];
    }
    public function notRandom()
    {
        return $this->state(function (array $attributes){
            // $barang = Barang::find($attributes['barang_id']);
            // $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($attributes['id']);
            // $output_file = "assets/qr-code/$barang->nama-$barang->merk-1.png";
            // Storage::disk('public')->put($output_file, $image);
            // return [
            //     'qrcode' => $output_file, 
            // ];
        });
    }
    public function random()
    {
        return $this->state(function (array $attributes){
            // $barang = Barang::where('jenis', 'TIDAK_HABIS_PAKAI')->doesntHave('barangTidakHabisPakai')->get()->random();
            // $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($attributes['id']);
            // $output_file = "assets/qr-code/$barang->nama-$barang->merk-1.png";
            // Storage::disk('public')->put($output_file, $image);
            // return [
            //     'barang_id' => $barang->id,
            //     'qrcode' => $output_file,
            // ];
        });
    }
}
