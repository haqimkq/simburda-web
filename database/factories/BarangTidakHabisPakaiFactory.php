<?php

namespace Database\Factories;

use App\Models\Barang;
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
        $kondisi = fake()->randomElement(['BARU', 'BEKAS']);
        $keterangan = fake()->randomElement(['OK', 'Butuh Service']);
        $barang = Barang::where('jenis','TIDAK_HABIS_PAKAI')->get()->random();
        $id = fake()->uuid();
        $image = QrCode::size(1280)->format('png')->errorCorrection('H')->generate($id);
        $output_file = 'assets/qr-code/' . $barang->nama . '.png';
        Storage::disk('public')->put($output_file, $image);
        return [
            'id' => $id,
            'qrcode' => $output_file,
            'keterangan' => $keterangan,
            'kondisi' => $kondisi,
            'nomor_seri' => 1,
            'barang_id' => $barang->id,
        ];
    }
}
