<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Proyek;
use App\Models\SuratJalan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meminjam>
 */
class MeminjamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $satuan = fake()->randomElement(['buah', 'meter', 'lembar', 'batang', 'kilogram']);
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', 'now');
        $tgl_berakhir = fake()->dateTimeBetween('-1 weeks', '+2 weeks');
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);
        $barang_id = Barang::where('tersedia', 1)->get()->random()->id;
        $satuan = Barang::where('id', $barang_id)->first()->satuan;
        Barang::where('id', $barang_id)->update(['tersedia' => 0]);

        $dipinjam = false;
        if($now->between($start_date,$end_date))
            $dipinjam = true;

        if($barang_id==NULL) return NULL;
        return [
            'supervisor_id' => User::where('role', 'like', 'supervisor')->get()->random()->id,
            'barang_id' => $barang_id,
            'proyek_id' => Proyek::all()->random()->id,
            'surat_jalan_id' => SuratJalan::all()->random()->id,
            'dipinjam' => $dipinjam,
            // 'jumlah' => mt_rand(1,5),
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir,
            'satuan' => $satuan,
        ];
    }
}
