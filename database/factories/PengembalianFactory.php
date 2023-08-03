<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
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
        $status = fake()->randomElement(['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        // $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        $peminjaman_detail = PeminjamanDetail::first();
        $kode_pengembalian = Pengembalian::generateKodePengembalian($peminjaman_detail->peminjaman->menangani->proyek->client, $peminjaman_detail->peminjaman->menangani->user->nama);
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'peminjaman_id' => $peminjaman_detail->peminjaman_id,
        ];
    }

    public function selesai(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'peminjaman_id' => $peminjaman->id,
                'status' => 'SELESAI',
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        })->has(
            SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) {
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
        , 'sjPengembalian');
    }
    public function menungguPengembalian(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'peminjaman_id' => $peminjaman->id,
                'status' => 'MENUNGGU_PENGEMBALIAN',
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        })->has(
            SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) {
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
        , 'sjPengembalian');
    }
    public function sedangDikembalikan(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'peminjaman_id' => $peminjaman->id,
                'status' => 'SEDANG_DIKEMBALIKAN',
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        })->has(
            SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) {
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
        , 'sjPengembalian');
    }
    public function menungguSuratJalan(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'peminjaman_id' => $peminjaman->id,
                'status' => 'MENUNGGU_SURAT_JALAN',
                'updated_at' => $peminjaman->created_at,
                'created_at' => $peminjaman->created_at
            ];
        });
    }
}
