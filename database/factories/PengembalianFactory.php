<?php

namespace Database\Factories;

use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengembalianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $kode_pengembalian = 
        $status = fake()->randomElement(['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'peminjaman_id' => $peminjaman_detail->peminjaman_id,
        ];
    }
    public function withSuratJalanSelesai($peminjaman_id, $pengembalian_status, $admin_gudang_id, $client, $supervisor){
        return $this->state(function(array $attributes) use ($peminjaman_id, $pengembalian_status){
            return [
                'peminjaman_id' => $peminjaman_id,
                'status' => $pengembalian_status,
            ];
        })->has(
            SuratJalan::factory()->state([
                'admin_gudang_id' => $admin_gudang_id,
                'tipe' => 'PENGEMBALIAN',
                'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $client, $supervisor),
            ])->selesai()->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                return [
                    'surat_jalan_id' => $surat_jalan->id,
                    'pengembalian_id' => $pengembalian->id,
                ];
            }))
        );
    }

    public function withSuratJalanDalamPerjalanan($peminjaman_id, $pengembalian_status, $admin_gudang_id, $client, $supervisor){
        return $this->state(function(array $attributes) use ($peminjaman_id, $pengembalian_status){
            return [
                'peminjaman_id' => $peminjaman_id,
                'status' => $pengembalian_status,
            ];
        })->has(
            SuratJalan::factory()->state([
                'admin_gudang_id' => $admin_gudang_id,
                'tipe' => 'PENGEMBALIAN',
                'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $client, $supervisor),
            ])->dalamPerjalanan()->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                return [
                    'surat_jalan_id' => $surat_jalan->id,
                    'pengembalian_id' => $pengembalian->id,
                ];
            }))
        )->create();;
    }
    public function withSuratJalanMenunggu($peminjaman_id, $pengembalian_status, $admin_gudang_id, $client, $supervisor){
        return $this->state(function(array $attributes) use ($peminjaman_id, $pengembalian_status){
            return [
                'peminjaman_id' => $peminjaman_id,
                'status' => $pengembalian_status,
            ];
        })->has(
            SuratJalan::factory()->state([
                'admin_gudang_id' => $admin_gudang_id,
                'tipe' => 'PENGEMBALIAN',
                'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $client, $supervisor),
            ])->menunggu()->has(SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian, SuratJalan $surat_jalan) {
                return [
                    'surat_jalan_id' => $surat_jalan->id,
                    'pengembalian_id' => $pengembalian->id,
                ];
            }))
        )->create();;
    }
}
