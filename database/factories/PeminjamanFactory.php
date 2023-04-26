<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Gudang;
use App\Models\Menangani;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $menangani = Menangani::get()->random();
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', 'now');
        $tgl_berakhir = fake()->dateTimeBetween('-1 weeks', '+2 weeks');
        
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);
        $status = fake()->randomElement(['MENUNGGU_AKSES','AKSES_DITOLAK', 'MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM', 'DIPINJAM', 'SELESAI']);


        if($now->between($start_date,$end_date))
            Barang::where('id', $barang_id)->update(['peminjaman_id' => $id]);
            // $dipinjam = true;
            $project_manager = User::where('id', $menangani->proyek->proyekManager->id)->get();
            $status = 'DIPINJAM';
            $admin_gudang = AdminGudang::get()->random();
            AksesBarang::factory()->state([
                'disetujui_admin' => true,
                'disetujui_pm' => true,
                'admin_gudang_id' => $admin_gudang->id,
                'admin_gudang_id' => $admin_gudang->id,
            ])->create();
            $sj_pengiriman = SuratJalan::all()->random()->id;
            $sj_pengembalian = NULL;
        $tipe = fake()->randomElement(['GUDANG_PROYEK','PROYEK_PROYEK']);
        return [
            'id' => $id,
            'gudang_id' => Gudang::factory(),
            'menangani_id' => ,
            'tipe' => $tipe,
            'status' => $status,
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir
        ];
    }
}
