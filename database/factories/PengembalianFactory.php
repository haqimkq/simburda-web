<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\PengembalianPenggunaan;
use App\Models\Penggunaan;
use App\Models\PenggunaanDetail;
use App\Models\SjPengembalianPenggunaan;
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
        $status = fake()->randomElement(['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        // $penggunaan_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        $penggunaan_detail = PenggunaanDetail::first();
        $kode_pengembalian = Pengembalian::generateKodePengembalian($penggunaan_detail->peminjaman->menangani->proyek->client, $penggunaan_detail->peminjaman->menangani->supervisor->nama);
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'peminjaman_id' => $penggunaan_detail->peminjaman_id,
        ];
    }

    public function selesai(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'peminjaman_id' => $penggunaan->id,
                'status' => 'SELESAI',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->selesaiSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->peminjaman->created_at,    
                    'created_at' => $pengembalian->peminjaman->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->selesaiSj())
        , 'sjPengembalianPenggunaan');
    }
    public function menungguPengembalian(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'peminjaman_id' => $penggunaan->id,
                'status' => 'MENUNGGU_PENGEMBALIAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->menungguSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->peminjaman->created_at,    
                    'created_at' => $pengembalian->peminjaman->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->menungguSj())
        , 'sjPengembalianPenggunaan');
    }
    public function sedangDikembalikan(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'peminjaman_id' => $penggunaan->id,
                'status' => 'SEDANG_DIKEMBALIKAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->peminjaman->created_at,    
                    'created_at' => $pengembalian->peminjaman->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->dalamPerjalananSj())
        , 'sjPengembalianPenggunaan');
    }
    public function menungguSuratJalan(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'peminjaman_id' => $penggunaan->id,
                'status' => 'MENUNGGU_SURAT_JALAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        });
    }
}
