<?php

namespace Database\Factories;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Models\AdminGudang;
use App\Models\AksesBarang;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SuratJalan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $menangani = Menangani::with(['proyek','supervisor','proyek.projectManager'])->get()->random();
        $gudang = Gudang::get()->random();
        $admin_gudang = AdminGudang::where('gudang_id',$gudang->id)->get()->random();
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', 'now');
        $tgl_berakhir = fake()->dateTimeBetween('-1 weeks', '+2 weeks');
        $proyek = $menangani->proyek;
        $supervisor = $menangani->supervisor;
        $project_manager = $menangani->proyek->projectManager;
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);
        $status = NULL;
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $supervisor->nama);

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
            if($pengembalian_status == 'SEDANG_DIKEMBALIKAN'){
                Pengembalian::factory()->withSuratJalanDalamPerjalanan($id, $pengembalian_status, $admin_gudang->id, $proyek->client, $supervisor->nama)->create();
                // Pengembalian::factory()->state([
                //     'peminjaman_id' => $id,
                //     'status' => $pengembalian_status,
                // ])->has(
                //     SuratJalan::factory()->state([
                //         'admin_gudang_id' => $admin_gudang->id,
                //         'tipe' => 'PENGEMBALIAN',
                //         'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
                //     ])->dalamPerjalanan()->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                //         return [
                //             'surat_jalan_id' => $surat_jalan->id,
                //             'pengembalian_id' => $pengembalian->id,
                //         ];
                //     }))
                // )->create();
            }else if($pengembalian_status == 'SELESAI'){
                Pengembalian::factory()->withSuratJalanSelesai($id, $pengembalian_status, $admin_gudang->id, $proyek->client, $supervisor->nama)->create();
                // Pengembalian::factory()->state([
                //     'peminjaman_id' => $id,
                //     'status' => $pengembalian_status,
                // ])->has(
                //     SuratJalan::factory()->state([
                //         'admin_gudang_id' => $admin_gudang->id,
                //         'tipe' => 'PENGEMBALIAN',
                //         'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
                //     ])->selesai()->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                //         return [
                //             'surat_jalan_id' => $surat_jalan->id,
                //             'pengembalian_id' => $pengembalian->id,
                //         ];
                //     }))
                // )->create();
            }
            else{
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
                'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
            ])->selesai()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($id) {
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
                        'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
                    ])->dalamPerjalanan()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($id) {
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
            'kode_peminjaman' => $kode_peminjaman,
            'tipe' => 'GUDANG_PROYEK',
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir,
            'status' => $status,
        ];
    }
}
