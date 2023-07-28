<?php

namespace Database\Factories;

use App\Models\Menangani;
use App\Models\PengembalianPenggunaan;
use App\Models\Penggunaan;
use App\Models\PenggunaanGp;
use App\Models\SjPengirimanGp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penggunaan>
 */
class PenggunaanFactory extends Factory
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
        $proyek = $menangani->proyek;
        $user = $menangani->user;
        $proyek_created_at = Carbon::createFromTimestampMs($proyek->created_at);
        if($proyek->selesai==1){
            $status = 'SELESAI';
            $proyek_tgl_selesai = Carbon::createFromTimestampMs($proyek->tgl_selesai);
            $tgl_penggunaan = fake()->dateTimeBetween($proyek_created_at, $proyek_tgl_selesai);
            $tgl_berakhir = fake()->dateTimeBetween($tgl_penggunaan,$proyek_tgl_selesai);
        }else{
            $tgl_penggunaan = fake()->dateTimeBetween($proyek_created_at->format('Y-m-d H:i:s'), $proyek_created_at->format('Y-m-d H:i:s').' +2 months');
            $tgl_berakhir = fake()->dateTimeBetween($tgl_penggunaan->format('Y-m-d H:i:s'), $tgl_penggunaan->format('Y-m-d H:i:s').' +3 years');
            $now = Carbon::now();
            $start_date = Carbon::parse($tgl_penggunaan);
            $end_date = Carbon::parse($tgl_berakhir);
            if($now->between($start_date,$end_date)){
                $status = fake()->randomElement(['DIPINJAM','MENUNGGU_AKSES','MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN','SEDANG_DIKIRIM']);
            }else if($now->isAfter($end_date)){
                $status = 'SELESAI';
            }
        }
        $kode_penggunaan = Penggunaan::generateKodePenggunaan("GUDANG_PROYEK", $proyek->client, $user->nama, $proyek_created_at);
        return [
            'id' => $id,
            'menangani_id' => $menangani->id,
            'kode_penggunaan' => $kode_penggunaan,
            'tipe' => 'GUDANG_PROYEK',
            'created_at' => $tgl_penggunaan,
            'updated_at' => $tgl_penggunaan,
            'status' => $status,
        ];
    }
    public function initialData($menangani){
        $proyek = $menangani->proyek;
        $user = $menangani->user;
        $proyek_created_at = Carbon::createFromTimestampMs($proyek->created_at);
        $tgl_penggunaan = fake()->dateTimeBetween($proyek_created_at->format('Y-m-d H:i:s'), $proyek_created_at->format('Y-m-d H:i:s').' +2 months');
        $kode_penggunaan = Penggunaan::generateKodePenggunaan("GUDANG_PROYEK", $proyek->client, $user->nama,$proyek->created_at);
        return collect([
            'kode_penggunaan' => $kode_penggunaan,
            'tgl_penggunaan' => $tgl_penggunaan,
        ]);
    }
    public function menungguSuratJalanGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'MENUNGGU_SURAT_JALAN',
            ];
        })->has(PenggunaanGp::factory()->withPenggunaan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function menungguPengirimanGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'MENUNGGU_PENGIRIMAN',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->menungguPenggunaan())
            // ->has(SuratJalan::factory()->pengirimanGp()->menunggu()->has(SjPengirimanGp::factory()->menungguPenggunaan()))
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function sedangDikirimGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SEDANG_DIKIRIM',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->dalamPerjalananPenggunaan())
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function dipinjamGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'DIGUNAKAN',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpWithPengembalianMenungguSuratJalan(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        ->has(PengembalianPenggunaan::factory()->menungguSuratJalan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }

    public function selesaiGpWithPengembalianSedangDikembalikan(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        ->has(PengembalianPenggunaan::factory()->sedangDikembalikan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpWithPengembalianMenungguPengembalian(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        ->has(PengembalianPenggunaan::factory()->menungguPengembalian())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpWithPengembalianSelesai(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        ->has(PengembalianPenggunaan::factory()->selesai())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            ->has(SjPengirimanGp::factory()->selesaiPenggunaan())
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function menungguSuratJalanGpNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'MENUNGGU_SURAT_JALAN',
            ];
        })->has(PenggunaanGp::factory()->withPenggunaan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function menungguPengirimanGpNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'MENUNGGU_PENGIRIMAN',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
            // ->has(SuratJalan::factory()->pengirimanGp()->menunggu()->has(SjPengirimanGp::factory()->menungguPenggunaan()))
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function sedangDikirimGpNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SEDANG_DIKIRIM',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function dipinjamGpNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'DIGUNAKAN',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        ;
    }
    public function selesaiGpWithPengembalianMenungguSuratJalanNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        ->has(PengembalianPenggunaan::factory()->menungguSuratJalan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }

    public function selesaiGpWithPengembalianSedangDikembalikanNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        ->has(PengembalianPenggunaan::factory()->sedangDikembalikan())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpWithPengembalianMenungguPengembalianNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        ->has(PengembalianPenggunaan::factory()->menungguPengembalian())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
    public function selesaiGpWithPengembalianSelesaiNoSj(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_penggunaan' => $result['kode_penggunaan'],
                'tipe' => 'GUDANG_PROYEK',
                'created_at' => $result['tgl_penggunaan'],
                'updated_at' => $result['tgl_penggunaan'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PenggunaanGp::factory()->withPenggunaan()
        )
        ->has(PengembalianPenggunaan::factory()->selesai())
        // ->has(AksesBarang::factory()->accessGrantedWithPenggunaan())
        ;
    }
}
