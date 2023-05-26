<?php

namespace Database\Factories;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Models\AdminGudang;
use App\Models\AksesBarang;
use App\Models\Barang;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SuratJalan;
use App\Models\TtdSjVerification;
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
        $menangani = Menangani::get()->random();
        $gudang = Gudang::get()->random();
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', '+3 Days');
        $tgl_berakhir = fake()->dateTimeBetween($tgl_peminjaman, '+2 weeks');
        $proyek = $menangani->proyek;
        $supervisor = $menangani->supervisor;
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);
        $status = fake()->randomElement(['MENUNGGU_AKSES', 'MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM']);
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $supervisor->nama);

        if($now->isAfter($end_date)){
            $status = 'SELESAI';
        }
        else if($now->between($start_date,$end_date)){
            $status = 'DIPINJAM';
        }
        return [
            'id' => $id,
            'gudang_id' => $gudang->id,
            'menangani_id' => $menangani->id,
            'kode_peminjaman' => $kode_peminjaman,
            'tipe' => 'GUDANG_PROYEK',
            'tgl_peminjaman' => $tgl_peminjaman,
            'created_at' => $tgl_peminjaman,
            'updated_at' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir,
            'status' => $status,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Peminjaman $peminjaman) {
            $now = Carbon::now();
            $start_date = Carbon::parse($peminjaman->tgl_peminjaman);
            $end_date = Carbon::parse($peminjaman->tgl_berakhir);
            $created_at = fake()->dateTimeBetween($start_date, $end_date);
            // $created_at = Date::dateFormatter($custom_date);
            $menangani = Menangani::find($peminjaman->menangani_id);
            $gudang = Gudang::find($peminjaman->gudang_id);
            $admin_gudang = AdminGudang::where('gudang_id',$gudang->id)->get()->random();
            $proyek = $menangani->proyek;
            $supervisor = $menangani->supervisor;
            $project_manager = $menangani->proyek->projectManager;
            $status = $peminjaman->status;
            if($now->isAfter($end_date)){
                AksesBarang::factory()->state([
                    'disetujui_admin' => true,
                    'disetujui_pm' => true,
                    'admin_gudang_id' => $admin_gudang->user->id,
                    'project_manager_id' => $project_manager->id,
                    'peminjaman_id' => $peminjaman->id,
                    'created_at' => $created_at,
                ])->create();
                SuratJalan::factory()->state([
                    'id' => fake()->uuid(),
                    'admin_gudang_id' => $admin_gudang->user->id,
                    'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
                    'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
                    'created_at' => $created_at
                ])->selesai()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman , $created_at) {
                    return [
                        'surat_jalan_id' => $surat_jalan->id,
                        'peminjaman_id' => $peminjaman->id,
                        'created_at' => $created_at
                    ];
                }))->create();
                $pengembalian_status = fake()->randomElement(['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
                if($pengembalian_status == 'SEDANG_DIKEMBALIKAN'){
                    Pengembalian::factory()->state([
                        'peminjaman_id' => $peminjaman->id,
                        'status' => $pengembalian_status,
                        'created_at' => $created_at,
                    ])->has(
                        SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) use ($created_at) {
                            return [
                                'pengembalian_id' => $pengembalian->id,
                                'created_at' => $created_at,
                            ];
                        })->for(SuratJalan::factory()->state([
                            'id' => fake()->uuid(),
                            'admin_gudang_id' => $admin_gudang->user->id,
                            'tipe' => 'PENGEMBALIAN',
                            'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
                            'created_at' => $created_at
                        ])->dalamPerjalanan())
                    , 'sjPengembalian')->create();
                }else if($pengembalian_status == 'SELESAI'){
                    Pengembalian::factory()->state([
                        'peminjaman_id' => $peminjaman->id,
                        'status' => $pengembalian_status,
                        'created_at' => $created_at
                    ])->has(
                        SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) use ($created_at) {
                            return [
                                'pengembalian_id' => $pengembalian->id,
                                'created_at' => $created_at
                            ];
                        })->for(SuratJalan::factory()->state([
                            'id' => fake()->uuid(),
                            'admin_gudang_id' => $admin_gudang->user->id,
                            'tipe' => 'PENGEMBALIAN',
                            'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
                            'created_at' => $created_at
                        ])->selesai())
                    , 'sjPengembalian')->create();
                }
                else{
                    Pengembalian::factory()->state([
                        'peminjaman_id' => $peminjaman->id,
                        'status' => $pengembalian_status,
                        'created_at' => $created_at
                    ])->create();
                }
            }
            else if($now->between($start_date,$end_date)){
                AksesBarang::factory()->state([
                    'disetujui_admin' => true,
                    'disetujui_pm' => true,
                    'admin_gudang_id' => $admin_gudang->user->id,
                    'project_manager_id' => $project_manager->id,
                    'peminjaman_id' => $peminjaman->id,
                    'created_at' => $created_at
                ])->create();
                SuratJalan::factory()->state([
                    'id' => fake()->uuid(),
                    'admin_gudang_id' => $admin_gudang->user->id,
                    'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
                    'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
                    'created_at' => $created_at
                ])->selesai()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman, $created_at) {
                    return [
                        'surat_jalan_id' => $surat_jalan->id,
                        'peminjaman_id' => $peminjaman->id,
                        'created_at' => $created_at
                    ];
                }))->create();
            }else{
                if($status == 'MENUNGGU_AKSES'){
                    AksesBarang::factory()->state([
                        'disetujui_admin' => NULL,
                        'disetujui_pm' => NULL,
                        'admin_gudang_id' => NULL,
                        'project_manager_id' => $project_manager->id,
                        'peminjaman_id' => $peminjaman->id,
                        'created_at' => $created_at
                    ])->create();
                }else{
                    AksesBarang::factory()->state([
                        'disetujui_admin' => true,
                        'disetujui_pm' => true,
                        'admin_gudang_id' => $admin_gudang->user->id,
                        'project_manager_id' => $project_manager->id,
                        'peminjaman_id' => $peminjaman->id,
                        'created_at' => $created_at
                    ])->create();
                    if($status == 'SEDANG_DIKIRIM'){
                        SuratJalan::factory()->state([
                            'id' => fake()->uuid(),
                            'admin_gudang_id' => $admin_gudang->user->id,
                            'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
                            'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
                            'created_at' => $created_at
                        ])->dalamPerjalanan()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman, $created_at) {
                            return [
                                'surat_jalan_id' => $surat_jalan->id,
                                'peminjaman_id' => $peminjaman->id,
                                'created_at' => $created_at
                            ];
                        }))->create();
                    }
                }
            }
        });
    }
}

