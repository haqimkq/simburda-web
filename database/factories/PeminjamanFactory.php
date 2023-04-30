<?php

namespace Database\Factories;

use App\Models\AdminGudang;
use App\Models\AksesBarang;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SuratJalan;
use App\Models\User;
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
        $gudang = Gudang::get()->random();
        $admin_gudang = AdminGudang::where('gudang_id',$gudang->id)->get()->random();
        $project_manager = User::where('id', $menangani->proyek->proyekManager->id)->get();
        $proyek_proyek_type = fake()->boolean(30);
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', 'now');
        $tgl_berakhir = fake()->dateTimeBetween('-1 weeks', '+2 weeks');
        
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);
        $status = NULL;

        if($now->isAfter($end_date)){
            $status = 'SELESAI';
            AksesBarang::factory()->state([
                'disetujui_admin' => true,
                'disetujui_pm' => true,
                'admin_gudang_id' => $admin_gudang->user->id,
                'project_manager_id' => $project_manager->id,
                'peminjaman_id' => $id,
            ])->create();
            $pengembalian_status = fake()->randomElement(['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
            if($pengembalian_status != 'MENUNGGU_PENGEMBALIAN'){
                Pengembalian::factory()->state([
                    'peminjaman_id' => $id,
                    'status' => $pengembalian_status,
                ])->has(
                    SuratJalan::factory()->state([
                        'admin_gudang_id' => $admin_gudang->id,
                        'tipe' => 'PENGEMBALIAN',
                    ])->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                        return [
                            'surat_jalan_id' => $surat_jalan->id,
                            'pengembalian_id' => $pengembalian->id,
                        ];
                    }))
                )->create();
            }else{
                Pengembalian::factory()->state([
                    'peminjaman_id' => $id,
                    'status' => $pengembalian_status
                ])->create();
            }
        }
        else if($now->between($start_date,$end_date)){
            $status = 'DIPINJAM';
            AksesBarang::factory()->state([
                'disetujui_admin' => true,
                'disetujui_pm' => true,
                'admin_gudang_id' => $admin_gudang->id,
                'project_manager_id' => $project_manager->id,
                'peminjaman_id' => $id,
            ])->create();
            SuratJalan::factory()->state([
                'admin_gudang_id' => $admin_gudang->id,
                'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
            ])->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($id) {
                return [
                    'surat_jalan_id' => $surat_jalan->id,
                    'peminjaman_id' => $id,
                ];
            }))->create();
        }else{
            $status = fake()->randomElement(['MENUNGGU_AKSES', 'MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM']);
            if($status == 'MENUNGGU_AKSES'){
                AksesBarang::factory()->state([
                    'disetujui_admin' => NULL,
                    'disetujui_pm' => NULL,
                    'admin_gudang_id' => NULL,
                    'project_manager_id' => $project_manager->id,
                    'peminjaman_id' => $id,
                ])->create();
            }else{
                AksesBarang::factory()->state([
                    'disetujui_admin' => true,
                    'disetujui_pm' => true,
                    'admin_gudang_id' => $admin_gudang->id,
                    'project_manager_id' => $project_manager->id,
                    'peminjaman_id' => $id,
                ])->create();
                if($status == 'SEDANG_DIKIRIM'){
                    SuratJalan::factory()->state([
                        'admin_gudang_id' => $admin_gudang->id,
                        'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
                    ])->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($id) {
                        return [
                            'id' => $attributes['id'],
                            'surat_jalan_id' => $surat_jalan->id,
                            'peminjaman_id' => $id,
                        ];
                    }))->create();
                }
            }
        }
        return [
            'id' => $id,
            'gudang_id' => $gudang->id,
            'menangani_id' => $menangani->id,
            'tipe' => 'GUDANG_PROYEK',
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir,
            'status' => $status,
        ];
    }
}
