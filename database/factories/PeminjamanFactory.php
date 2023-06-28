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
use App\Models\PeminjamanGp;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
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
        $supervisor = $menangani->supervisor;
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $supervisor->nama);
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
                // $status = fake()->randomElement(['DIPINJAM']);
                $status = fake()->randomElement(['DIPINJAM','MENUNGGU_AKSES','AKSES_DITOLAK','MENUNGGU_SURAT_JALAN','MENUNGGU_PENGIRIMAN','SEDANG_DIKIRIM']);
            }else if($now->isAfter($end_date)){
                $status = 'SELESAI';
            }
        }
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
    // public function configure()
    // {
    //     return $this->afterCreating(function (Peminjaman $peminjaman) {
    //         $now = Carbon::now();
    //         $start_date = Carbon::createFromTimestampMs($peminjaman->tgl_peminjaman);
    //         $end_date = Carbon::createFromTimestampMs($peminjaman->tgl_berakhir);
    //         $created_at = fake()->dateTimeBetween($start_date, $end_date);
    //         $menangani = Menangani::find($peminjaman->menangani_id);
    //         $gudang = Gudang::find($peminjaman->gudang_id);
    //         $admin_gudang = AdminGudang::where('gudang_id',$gudang->id)->get()->random();
    //         $proyek = $menangani->proyek;
    //         $supervisor = $menangani->supervisor;
    //         $project_manager = $menangani->proyek->projectManager;
    //         $status = $peminjaman->status;
    //         if($now->isAfter($end_date)){
    //             AksesBarang::factory()->state([
    //                 'disetujui_admin' => true,
    //                 'disetujui_pm' => true,
    //                 'admin_gudang_id' => $admin_gudang->user->id,
    //                 'project_manager_id' => $project_manager->id,
    //                 'peminjaman_id' => $peminjaman->id,
    //                 'updated_at' => $created_at,
    //                 'created_at' => $created_at,
    //             ])->create();
    //             SuratJalan::factory()->state([
    //                 'id' => fake()->uuid(),
    //                 'admin_gudang_id' => $admin_gudang->user->id,
    //                 'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
    //                 'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
    //                 'updated_at' => $created_at,
    //                 'created_at' => $created_at
    //             ])->selesai()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman , $created_at) {
    //                 return [
    //                     'surat_jalan_id' => $surat_jalan->id,
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ]; 
    //             }))->create();
    //             $pengembalian_status = fake()->randomElement(['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
    //             if($pengembalian_status == 'SEDANG_DIKEMBALIKAN'){
    //                 Pengembalian::factory()->state([
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'status' => $pengembalian_status,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at,
    //                 ])->has(
    //                     SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) use ($created_at) {
    //                         return [
    //                             'pengembalian_id' => $pengembalian->id,
    //                             'updated_at' => $created_at,    
    //                             'created_at' => $created_at,
    //                         ];
    //                     })->for(SuratJalan::factory()->state([
    //                         'id' => fake()->uuid(),
    //                         'admin_gudang_id' => $admin_gudang->user->id,
    //                         'tipe' => 'PENGEMBALIAN',
    //                         'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ])->dalamPerjalanan())
    //                 , 'sjPengembalian')->create();
    //             }else if($pengembalian_status == 'SELESAI'){
    //                 Pengembalian::factory()->state([
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'status' => $pengembalian_status,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->has(
    //                     SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) use ($created_at) {
    //                         return [
    //                             'pengembalian_id' => $pengembalian->id,
    //                             'updated_at' => $created_at,    
    //                             'created_at' => $created_at
    //                         ];
    //                     })->for(SuratJalan::factory()->state([
    //                         'id' => fake()->uuid(),
    //                         'admin_gudang_id' => $admin_gudang->user->id,
    //                         'tipe' => 'PENGEMBALIAN',
    //                         'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ])->selesai())
    //                 , 'sjPengembalian')->create();
    //             }
    //             else if($pengembalian_status == 'MENUNGGU_SURAT_JALAN'){
    //                 Pengembalian::factory()->state([
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'status' => $pengembalian_status,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->create();
    //             }else{
    //                 Pengembalian::factory()->state([
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'status' => $pengembalian_status,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->has(
    //                     SjPengembalian::factory()->state(function (array $attributes, Pengembalian $pengembalian) use ($created_at) {
    //                         return [
    //                             'pengembalian_id' => $pengembalian->id,
    //                             'updated_at' => $created_at,    
    //                             'created_at' => $created_at
    //                         ];
    //                     })->for(SuratJalan::factory()->state([
    //                         'id' => fake()->uuid(),
    //                         'admin_gudang_id' => $admin_gudang->user->id,
    //                         'tipe' => 'PENGEMBALIAN',
    //                         'kode_surat' => SuratJalan::generateKodeSurat("PENGEMBALIAN", $proyek->client, $supervisor->nama),
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ])->menunggu())
    //                 , 'sjPengembalian')->create();
    //             }
    //         }
    //         else if($now->between($start_date,$end_date)){
    //             if($status == 'DIPINJAM'){
    //                 AksesBarang::factory()->state([
    //                     'disetujui_admin' => true,
    //                     'disetujui_pm' => true,
    //                     'admin_gudang_id' => $admin_gudang->user->id,
    //                     'project_manager_id' => $project_manager->id,
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->create();
    //                 SuratJalan::factory()->state([
    //                     'id' => fake()->uuid(),
    //                     'admin_gudang_id' => $admin_gudang->user->id,
    //                     'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
    //                     'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->selesai()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman, $created_at) {
    //                     return [
    //                         'surat_jalan_id' => $surat_jalan->id,
    //                         'peminjaman_id' => $peminjaman->id,
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ];
    //                 }))->create();
    //             }
    //             else if($status == 'MENUNGGU_AKSES'){
    //                 AksesBarang::factory()->state([
    //                     'disetujui_admin' => NULL,
    //                     'disetujui_pm' => NULL,
    //                     'admin_gudang_id' => NULL,
    //                     'project_manager_id' => $project_manager->id,
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->create();
    //             }else if($status == 'AKSES_DITOLAK'){
    //                 $ditolak_pm = fake()->boolean();
    //                 AksesBarang::factory()->state([
    //                     'disetujui_admin' => false,
    //                     'disetujui_pm' => $ditolak_pm,
    //                     'keterangan_pm' => (!$ditolak_pm) ? fake()->text() : null,
    //                     'keterangan_admin' => fake()->text(),
    //                     'admin_gudang_id' => NULL,
    //                     'project_manager_id' => $project_manager->id,
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->create();
    //             }else{
    //                 AksesBarang::factory()->state([
    //                     'disetujui_admin' => true,
    //                     'disetujui_pm' => true,
    //                     'admin_gudang_id' => $admin_gudang->user->id,
    //                     'project_manager_id' => $project_manager->id,
    //                     'peminjaman_id' => $peminjaman->id,
    //                     'updated_at' => $created_at,
    //                     'created_at' => $created_at
    //                 ])->create();
    //                 if($status == 'MENUNGGU_PENGIRIMAN'){
    //                     SuratJalan::factory()->state([
    //                         'id' => fake()->uuid(),
    //                         'admin_gudang_id' => $admin_gudang->user->id,
    //                         'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
    //                         'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ])->menunggu()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman, $created_at) {
    //                         return [
    //                             'surat_jalan_id' => $surat_jalan->id,
    //                             'peminjaman_id' => $peminjaman->id,
    //                             'updated_at' => $created_at,    
    //                             'created_at' => $created_at
    //                         ];
    //                     }))->create();
    //                 }else if($status == 'SEDANG_DIKIRIM'){
    //                     SuratJalan::factory()->state([
    //                         'id' => fake()->uuid(),
    //                         'admin_gudang_id' => $admin_gudang->user->id,
    //                         'tipe' => 'PENGIRIMAN_GUDANG_PROYEK',
    //                         'kode_surat' => SuratJalan::generateKodeSurat("PENGIRIMAN_GUDANG_PROYEK", $proyek->client, $supervisor->nama),
    //                         'updated_at' => $created_at,
    //                         'created_at' => $created_at
    //                     ])->dalamPerjalanan()->has(SjPengirimanGp::factory()->state(function (array $attributes, SuratJalan $surat_jalan) use ($peminjaman, $created_at) {
    //                         return [
    //                             'surat_jalan_id' => $surat_jalan->id,
    //                             'peminjaman_id' => $peminjaman->id,
    //                             'updated_at' => $created_at,    
    //                             'created_at' => $created_at
    //                         ];
    //                     }))->create();
    //                 }
    //             }
    //         }
    //     });
    // }
    public function initialData($menangani){
        $proyek = $menangani->proyek;
        $supervisor = $menangani->supervisor;
        $kode_peminjaman = Peminjaman::generateKodePeminjaman("GUDANG_PROYEK", $proyek->client, $supervisor->nama);
        $proyek_created_at = Carbon::createFromTimestampMs($proyek->created_at);
        $tgl_peminjaman = fake()->dateTimeBetween($proyek_created_at->format('Y-m-d H:i:s'), $proyek_created_at->format('Y-m-d H:i:s').' +2 months');
        $tgl_berakhir = fake()->dateTimeBetween($tgl_peminjaman->format('Y-m-d H:i:s'), $tgl_peminjaman->format('Y-m-d H:i:s').' +2 months');
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
        ->has(AksesBarang::factory()->needAccessWithPeminjaman());
    }
    public function aksesDitolakGp(){
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
                'status' => 'AKSES_DITOLAK',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman())
        ->has(AksesBarang::factory()->accessNotGrantedWithPeminjaman());
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
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman());
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
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->menunggu()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman());
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
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->dalamPerjalanan()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman());
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
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->selesai()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman());
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
                'status' => 'DIPINJAM',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->selesai()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ->has(Pengembalian::factory()->menungguSuratJalan());
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
                'status' => 'DIPINJAM',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->selesai()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ->has(Pengembalian::factory()->sedangDikembalikan());
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
                'status' => 'DIPINJAM',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->selesai()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ->has(Pengembalian::factory()->menungguPengembalian());
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
                'status' => 'DIPINJAM',
            ];
        })->has(PeminjamanGp::factory()->withPeminjaman()->has(SjPengirimanGp::factory()->selesai()))
        ->has(AksesBarang::factory()->accessGrantedWithPeminjaman())
        ->has(Pengembalian::factory()->selesai());
    }
}

