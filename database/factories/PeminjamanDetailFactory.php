<?php

namespace Database\Factories;

use App\Models\AksesBarang;
use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class PeminjamanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $peminjaman = Peminjaman::get()->random();
        $satuan = NULL;
        $jumlah = NULL;
        $status = NULL;
        $barang = BarangTidakHabisPakai::get()->random();
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'peminjaman_proyek_lain_id' => NULL,
            'status' => $status,
            'peminjaman_id' => $peminjaman->id,
        ];
    }
    public function resetData(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            // $peminjaman = Peminjaman::find($peminjaman->id);
            $barang = BarangTidakHabisPakai::whereRelation('barang','gudang_id',$peminjaman->peminjamanGp->gudang_id)->whereDoesntHave('peminjamanDetail', function (Builder $query) use ($peminjaman){
                $query->where('peminjaman_id', $peminjaman->id);
            })->get()->random();
            if($peminjaman->status == "DIPINJAM"){
                BarangTidakHabisPakai::where('id', $barang->id)->update(['peminjaman_id' => $peminjaman->id]);
                $status = "DIGUNAKAN";
            }else if($peminjaman->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
                BarangTidakHabisPakai::where('id', $barang->id)->update(['peminjaman_id' => NULL]);
            }else if($peminjaman->status == "MENUNGGU_AKSES" || $peminjaman->status == "MENUNGGU_SURAT_JALAN" || $peminjaman->status == "MENUNGGU_PENGIRIMAN" || $peminjaman->status == "SEDANG_DIKIRIM"){
                $status = "MENUNGGU_AKSES";
            }
            return [
                'barang_id' => $barang->id,
                'status' => $status,
                'peminjaman_id' => $peminjaman->id,
                'penanggung_jawab_id' => ($status!='MENUNGGU_AKSES') ? User::whereRelation('menanganiProyek','proyek_id',$peminjaman->menangani->proyek_id)->get()->random()->id : null,
            ];
        });
    }
    public function menungguAksesGp(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->needAccessWithPeminjamanDetail());
    }
    public function aksesDitolakGp(){
        return $this->state(function (array $attributes){
            return [
                
            ];
        })
        ->has(AksesBarang::factory()->accessNotGrantedWithPeminjamanDetail());
    }
    public function menungguSuratJalanGp(){
        return $this->state(function (array $attributes){
            return [
                
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function menungguPengirimanGp(){
        return $this->state(function (array $attributes){
            return [
                
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function sedangDikirimGp(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function dipinjamGp(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function selesaiGpWithPengembalianMenungguSuratJalan(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }

    public function selesaiGpWithPengembalianSedangDikembalikan(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function selesaiGpWithPengembalianMenungguPengembalian(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
    public function selesaiGpWithPengembalianSelesai(){
        return $this->state(function (array $attributes){
            return [
            ];
        })
        ->has(AksesBarang::factory()->accessGrantedWithPeminjamanDetail());
    }
}
