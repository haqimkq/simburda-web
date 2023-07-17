<?php

namespace Database\Factories;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Enum\SuratJalanTipe;
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
use App\Models\PeminjamanGp;
use App\Models\PeminjamanPp;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\SuratJalan;
use App\Models\TtdSjVerification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

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
        $proyek = $menangani->proyek;
        $user = $menangani->user;
        $proyek_created_at = Carbon::createFromTimestampMs($proyek->created_at);
        if($proyek->selesai==1){
            $status = 'SELESAI';
            $proyek_tgl_selesai = Carbon::createFromTimestampMs($proyek->tgl_selesai);
            $tgl_peminjaman = fake()->dateTimeBetween($proyek_created_at, $proyek_tgl_selesai);
            $tgl_berakhir = fake()->dateTimeBetween($tgl_peminjaman,$proyek_tgl_selesai);
        }else{
            $tgl_peminjaman = fake()->dateTimeBetween($proyek_created_at->format('Y-m-d H:i:s'), $proyek_created_at->format('Y-m-d H:i:s').' +2 months');
            $tgl_berakhir = fake()->dateTimeBetween($tgl_peminjaman->format('Y-m-d H:i:s'), $tgl_peminjaman->format('Y-m-d H:i:s').' +3 years');
            $now = Carbon::now();
            $start_date = Carbon::parse($tgl_peminjaman);
            $end_date = Carbon::parse($tgl_berakhir);
            if($now->between($start_date,$end_date)){
                $status = fake()->randomElement(['DIPINJAM','MENUNGGU_AKSES','MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN','SEDANG_DIKIRIM']);
            }else if($now->isAfter($end_date)){
                $status = 'SELESAI';
            }
        }
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $user->nama, $proyek_created_at);
        return [
            'id' => $id,
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
    public function initialData($menangani){
        $proyek = $menangani->proyek;
        $user = $menangani->user;
        $proyek_created_at = Carbon::createFromTimestampMs($proyek->created_at);
        $tgl_peminjaman = fake()->dateTimeBetween($proyek_created_at->format('Y-m-d H:i:s'), $proyek_created_at->format('Y-m-d H:i:s').' +2 months');
        $tgl_berakhir = fake()->dateTimeBetween($tgl_peminjaman->format('Y-m-d H:i:s'), $tgl_peminjaman->format('Y-m-d H:i:s').' +2 months');
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $user->nama,$proyek->created_at);
        return collect([
            'kode_peminjaman' => $kode_peminjaman,
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir,
        ]);
    }
    public function menungguAksesGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'MENUNGGU_AKSES',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman())
        // ->has(AksesBarang::factory()->needAccessWithPeminjaman())
        ;
    }
    public function menungguSuratJalanGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'MENUNGGU_SURAT_JALAN',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman())
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function menungguPengirimanGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'MENUNGGU_PENGIRIMAN',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->menunggu())
            // ->has(SuratJalan::factory()->pengirimanGp()->menunggu()->has(SjPengirimanGp::factory()->menunggu()))
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function sedangDikirimGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'SEDANG_DIKIRIM',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->dalamPerjalanan())
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function dipinjamGp(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 0)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'DIPINJAM',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->selesai())
        )
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function selesaiGpWithPengembalianMenungguSuratJalan(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->selesai())
        )
        ->has(Pengembalian::factory()->menungguSuratJalan())
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }

    public function selesaiGpWithPengembalianSedangDikembalikan(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->selesai())
        )
        ->has(Pengembalian::factory()->sedangDikembalikan())
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function selesaiGpWithPengembalianMenungguPengembalian(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->selesai())
        )
        ->has(Pengembalian::factory()->menungguPengembalian())
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
    public function selesaiGpWithPengembalianSelesai(){
        return $this->state(function (array $attributes){
            $menangani = Menangani::whereRelation('proyek', 'selesai', 1)->get()->random();
            $result = $this->initialData($menangani);
            return [
                'id' => fake()->uuid(),
                'menangani_id' => $menangani->id,
                'kode_peminjaman' => $result['kode_peminjaman'],
                'tipe' => 'GUDANG_PROYEK',
                'tgl_peminjaman' => $result['tgl_peminjaman'],
                'created_at' => $result['tgl_peminjaman'],
                'updated_at' => $result['tgl_peminjaman'],
                'tgl_berakhir' => $result['tgl_berakhir'],
                'status' => 'SELESAI',
            ];
        })
        ->has(PeminjamanGp::factory()->withPeminjaman()
            ->has(SjPengirimanGp::factory()->selesai())
        )
        ->has(Pengembalian::factory()->selesai())
        // ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ;
    }
}

