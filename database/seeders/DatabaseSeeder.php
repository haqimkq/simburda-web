<?php

namespace Database\Seeders;

use App\Helpers\IDGenerator;
use App\Models\LogisticFirebase;
use App\Models\Penggunaan;
use App\Models\PenggunaanDetail;
use App\Models\User;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\Logistic;
use App\Models\PreOrder;
use App\Models\Kendaraan;
use App\Models\Menangani;
use App\Models\BarangHabisPakai;
use App\Models\DeliveryOrder;
use App\Models\Gudang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\Perusahaan;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        LogisticFirebase::deleteAllData();
        $files = glob('C:\WebDev\SimburdaWeb\storage\app\public\assets\ttd-verification\*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }
        $gudang = Gudang::factory()->state(
            [
                'nama' => 'Gudang Jakarta 1',
                'alamat' => 'Jl. Pengadegan Selatan II No.1, RT.10/RW.4, Pengadegan, Kec. Pancoran, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12770',
                'latitude' => '-6.2501639',
                'longitude' => '106.8565822',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/gudang/Gudang Jakarta 1.jpg'
            ]
        )->create();
        Gudang::factory()->count(4)->state(new Sequence(
            [
                'nama' => 'Gudang Jaksel 1',
                'alamat' => 'Jl. Hang Lekiu IV, RT.6/RW.4, Gunung, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12120',
                'latitude' => '-6.2341379',
                'longitude' => '106.7938248',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/gudang/Gudang Jaksel 1.jpg'
            ],
            [
                'nama' => 'Gudang Jogja 1',
                'alamat' => 'Jl. Malioboro No.143, Sosromenduran, Gedong Tengen, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55271',
                'latitude' => '-7.7918055',
                'longitude' => '110.3650972',
                'kota' => 'Yogyakarta',
                'provinsi' => 'Daerah Istimewa Yogyakarta',
                'gambar' => 'assets/gudang/Gudang Jogja 1.jpg'
            ],
            [
                'nama' => 'Gudang Surabaya 1',
                'alamat' => 'Jl. Mayjend. Jonosewojo, Pradahkalikendal, Kec. Dukuhpakis, Kota SBY, Jawa Timur 60227',
                'latitude' => '-7.2881631',
                'longitude' => '112.6783636',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'gambar' => 'assets/gudang/Gudang Surabaya 1.jpg'
            ],
            [
                'nama' => 'Gudang Jakpus 1',
                'alamat' => 'Jl. Kramat Raya No.116, RT.2/RW.9, Kwitang, Kec. Senen, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10420',
                'latitude' => '6.1849776',
                'longitude' => '106.8436491',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'gambar' => 'assets/gudang/Gudang Jakpus 1.jpg'
            ],
        ))->create();
        User::factory()->state(
            [
                'id'=>'ab7e7da6-9333-35dd-8ae9-021c8a0232d4',
                'role'=>'ADMIN',
                'nama' => 'Erna B. Wijayanti, ST.MT.',
                'email' => 'ernawijayanti@gmail.com',
                'foto' => 'assets/users/Director_Erna B. Wijayanti, ST.MT.jpeg',
                'ttd' => 'assets/ttd/92673616-2c3a-3338-a435-2d79d880833e.png'
            ]
        )->create();

        User::factory()->state(
                [
                    'id'=>'68424cd1-8741-32b9-90a2-aae065dcc7b8',
                    'role'=>'ADMIN_GUDANG',
                    'nama' => 'Ghani Pratama',
                    'email' => 'ghanipratama@gmail.com',
                    'ttd' => 'assets/ttd/hjgawedyahwdh2837289371jh.png'
                ]
        )->create();

        User::factory()->state(
                [
                    'id'=>'cffe442b-8221-3902-9aee-a3006c5cf641',
                    'role'=>'LOGISTIC',
                    'nama' => 'Ahmad Lutfi',
                    'email' => 'ahmadlutfi@gmail.com',
                    'ttd' => 'assets/ttd/awdjawoueuy2803910382938djq3e.png'
                ]
        )->has(Logistic::factory()->state(function(array $attributes, User $user){
            return ['user_id' => $user->id];
        }))->create();

        User::factory()->state(
                [
                    'id'=>'576b1742-ec50-30a9-af16-05ba94eab0ce',
                    'role'=>'SUPERVISOR',
                    'nama' => 'Rama Wendyanto',
                    'email' => 'ramawendyanto@gmail.com',
                    'ttd' => 'assets/ttd/uawyeu2893jaskdh893qu23ajkw.png'
                ]
        )->create();

        User::factory()->state(
                [
                    'id'=>'67737154-4cc3-3545-9a7b-eeddd715d9f5',
                    'role'=>'PURCHASING',
                    'nama' => 'Meita Wulansuci S., SH',
                    'email' => 'meitawulansuci@gmail.com',
                    'foto' => 'assets/users/General Affair_Wulansuci S., SH.jpeg',
                    'ttd' => 'assets/ttd/83719273uawey02938he.png'
                ]
        )->create();

        User::factory(10)->state([
            'role' => 'SUPERVISOR'
        ])->create();
        
        User::factory(4)->state([
            'role' => 'LOGISTIC'
        ])->has(Logistic::factory()->state(function (array $attributes, User $user){
            return ['user_id' => $user->id];
        }))->create();

        User::factory(10)->state([
            'role' => 'PROJECT_MANAGER'
        ])->create();
        
        User::factory(10)->state([
            'role' => 'SITE_MANAGER'
        ])->create();

        User::factory(10)->state([
            'role' => 'PURCHASING',
            'ttd' => 'assets/ttd/83719273uawey02938he.png'
        ])->create();

        User::factory(10)->state([
            'role' => 'ADMIN_GUDANG'
        ])->create();

        $SM1 = User::factory()->state([
            'id'=>'02e6804b-9d38-3075-bbc3-69b8cc29da8c',
            'role'=>'SITE_MANAGER',
            'nama' => 'Novita Cahyanintyas, ST.',
            'email' => 'novitacahya@gmail.com',
            'foto' => 'assets/users/Project Manager_Novita Cahyanintyas, ST.jpeg',
            'ttd' => 'assets/ttd/no238193he012938.png'
        ])->create();

        Perusahaan::factory()->count(8)->state(new Sequence(
            [
                'nama' => 'PT Onasis Indonesia',
                'alamat' => 'Jl. KH Abdullah Syafei No.2, RT.12/RW.2, Tebet Tim., Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12840',
                'latitude' => '-6.175507',
                'longitude' => '106.7466998',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/PT Onasis Indonesia.jpg'
            ],
            [
                'nama' => 'WASKITA UTAMA - KSO',
                'alamat' => 'RT.6/RW.1, Manggarai, Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12850',
                'latitude' => '-6.2123089',
                'longitude' => '106.8519512',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/WASKITA UTAMA - KSO.jpg'
            ],
            [
                'nama' => 'PT Jurong Engineering Lestari',
                'alamat' => 'Jl. Gunung Sahari Raya Kav 18 Gedung Maspion Plaza, LT 10, RT.4/RW.1, Pademangan Bar., Kec. Pademangan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14420',
                'latitude' => '-6.1753341',
                'longitude' => '106.7466997',
                'kota' => 'Jakarta Utara',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/PT Jurong Engineering Lestari.jpg'
            ],
            [
                'nama' => 'PT. Timas Suplindo',
                'alamat' => 'Jl. Tanah Abang II No.81, RW.4, Petojo Sel., Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10160',
                'latitude' => '-6.17568',
                'longitude' => '106.7466998',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/PT. Timas Suplindo.jpg'
            ],
            [
                'nama' => 'PT. Acset Indonusa',
                'alamat' => 'Jl. Majapahit No.26, RT.14/RW.8, Petojo Sel., Kecamatan Gambir, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10160',
                'latitude' => '-6.2122005',
                'longitude' => '106.8210513',
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/PT. Acset Indonusa.jpg'
            ],
            [
                'nama' => 'PT. Gearindo Prakarsa',
                'alamat' => 'Jl. Radin Inten II No.46, RT.10/RW.5, Duren Sawit, Kec. Duren Sawit, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13440',
                'latitude' => '-6.2126438',
                'longitude' => '106.8658805',
                'kota' => 'Jakarta Timur',
                'provinsi' => 'DKI Jakarta',
                'gambar' => 'assets/perusahaan/PT. Gearindo Prakarsa.jpg'
            ],
            [
                'nama' => 'PT. Aneka Dharma Persada',
                'alamat' => '59JX+R63, Rejowinangun, Kotagede, Yogyakarta City, Special Region of Yogyakarta 55172',
                'latitude' => '-7.7971209',
                'longitude' => '110.336138',
                'kota' => 'Yogyakarta',
                'provinsi' => 'DIY Yogyakarta',
                'gambar' => 'assets/perusahaan/PT. Aneka Dharma Persada.jpg'
            ],
            [
                'nama' => 'PT. Archikon Wiratama',
                'alamat' => 'Ruko Sakura Regency, Ketintang, Jl. Ketintang Baru Sel. I No.14-16, Ketintang, Kec. Gayungan, Kota SBY, Jawa Timur 60234',
                'latitude' => '-7.323788',
                'longitude' => '112.7100665',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'gambar' => 'assets/perusahaan/PT. Archikon Wiratama.jpg'
            ],
        ))->create();

        Barang::factory()->count(23)->state(new Sequence(
                [
                    'merk' => 'KOBELCO SK 210',
                    'gambar' => 'assets/barang/TidakHabisPakai/Kobelco SK200.jpg',
                    'nama' => 'Excavator',
                ],
                [
                    'merk' => 'KOBELCO PC 130',
                    'gambar' => 'assets/barang/TidakHabisPakai/KOBELCO PC130.jpg',
                    'nama' => 'Excavator',
                ],
                [
                    'merk' => 'HITACHI PC ZAXIS 210',
                    'gambar' => 'assets/barang/TidakHabisPakai/HITACHI EXCAVATOR ZAXIS 210.jpg',
                    'nama' => 'Excavator',
                ],
                [
                    'merk' => 'HITACHI ZX 48U-5A',
                    'gambar' => 'assets/barang/TidakHabisPakai/HITACHI ZX48U-5A.jpg',
                    'nama' => 'Excavator',
                ],
                [
                    'merk' => 'HONDA EP2500CX',
                    'gambar' => 'assets/barang/TidakHabisPakai/Genset Honda EP2500CX.jpg',
                    'nama' => 'Genset',
                ],
                [
                    'merk' => 'Seoul',
                    'gambar' => 'assets/barang/TidakHabisPakai/Bar cutter portable (Seoul).jpg',
                    'nama' => 'Bar cutter portable',
                ],
                [
                    'merk' => 'Hilti Te700AVR',
                    'gambar' => 'assets/barang/TidakHabisPakai/Bobok besar AVR (Hilti Te700AVR).jpg',
                    'nama' => 'Bobok besar AVR',
                ],
                [
                    'merk' => 'Hilti Te500',
                    'gambar' => 'assets/barang/TidakHabisPakai/Bobok beton (Hilti Te500).jpg',
                    'nama' => 'Bobok beton',
                ],
                [
                    'merk' => 'Hilti Te70',
                    'gambar' => 'assets/barang/TidakHabisPakai/Bor Bobok beton (Hilti Te70).jpg',
                    'nama' => 'Bor Bobok beton',
                ],
                [
                    'merk' => 'Bosch GLL 5-50 X',
                    'gambar' => 'assets/barang/TidakHabisPakai/Level laser (Bosch GLL 5-50 X).jpg',
                    'nama' => 'Level laser',
                ],
                [
                    'merk' => 'Hitachi',
                    'gambar' => 'assets/barang/TidakHabisPakai/Serut kayu (Hitachi).jpg',
                    'nama' => 'Serut kayu',
                ],
                [
                    'merk' => 'Honda',
                    'gambar' => 'assets/barang/TidakHabisPakai/Steam (Honda).jpg',
                    'nama' => 'Steam',
                ],
                [
                    'merk' => 'Skillsaw',
                    'gambar' => 'assets/barang/TidakHabisPakai/Sirkel kayu (Skillsaw).jpg',
                    'nama' => 'Sirkel kayu',
                ],
                [
                    'merk' => 'MIKASA',
                    'gambar' => 'assets/barang/TidakHabisPakai/Aspal Cutter Mikasa.jpg',
                    'nama' => 'Aspalt Cutter',
                ],
                [
                    'merk' => 'TOP CON',
                    'gambar' => 'assets/barang/TidakHabisPakai/Waterpass Topcon.jpg',
                    'nama' => 'Waterpass',
                ],
                [
                    'merk' => 'MIKITA',
                    'gambar' => 'assets/barang/TidakHabisPakai/Jack Hammer Mikita.jpg',
                    'nama' => 'Jack Hammer',
                ],
                [
                    'merk' => 'DENYO D300 YANMAR',
                    'gambar' => 'assets/barang/TidakHabisPakai/Mesin Las Denyo D300 Yanmar.jpg',
                    'nama' => 'Mesin Las',
                ],
                [
                    'merk' => 'SAKAI',
                    'gambar' => 'assets/barang/TidakHabisPakai/wales slender sakai.jpg',
                    'nama' => 'Wales Silinder',
                ],
                [
                    'merk' => 'YANMAR SC5N',
                    'gambar' => 'assets/barang/TidakHabisPakai/air compressor yanmar sc5n.jpg',
                    'nama' => 'Compressor Mobil',
                ],
                [
                    'merk' => 'SAKAI',
                    'gambar' => 'assets/barang/TidakHabisPakai/Baby Roller Sakai.jpg',
                    'nama' => 'Baby Roller',
                ],
                [
                    'merk' => 'YANMAR',
                    'gambar' => 'assets/barang/TidakHabisPakai/Trowel Yanmar.jpg',
                    'nama' => 'Trowel',
                ],
                [
                    'merk' => 'CHINA',
                    'gambar' => 'assets/barang/TidakHabisPakai/Mesin Paving Cina.jpg',
                    'nama' => 'Mesin Paving',
                ],
                [
                    'merk' => 'DENYO 300 A',
                    'gambar' => 'assets/barang/TidakHabisPakai/Generator Las DENYO 300 A.jpg',
                    'nama' => 'Generator Las',
                ],
        ))->tidakHabisPakai()->create();

        Barang::factory(40)->state(['jenis' => 'HABIS_PAKAI'])->has(BarangHabisPakai::factory())->create();

        Proyek::factory()->state([
            'nama_proyek' => 'Perumahan Sakura Regency 3 Toyota Housing Indonesia Rumah Blok H29',
            'foto' => 'assets/proyek/Perumahan Sakura Regency 3 Toyota Housing Indonesia Rumah Blok H29.jpg',
            'client' => 'PT. Toyota Housing Indonesia',
            'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
            'provinsi' => 'Jawa Barat',
            'kota' => 'Bekasi',
            'latitude' => '-6.2813794',
            'longitude' => '107.0118061',
        ])->for($SM1, 'siteManager')->create();

        Proyek::factory(9)
        ->state(new Sequence(
            [
                'nama_proyek' => 'Perbaikan Kolom Struktur Hanggar Skuadron 45 Halim Perdana Kusuma',
                'foto' => 'assets/proyek/Perbaikan Kolom Struktur Hanggar Skuadron 45 Halim Perdana Kusuma.jpg',
                'client' => 'Sekretariat Negara',
                'alamat' => 'Jl. Rajawali Baru No.1, RT.5/RW.11, Halim Perdana Kusumah, Kec. Makasar, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13610',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Timur',
                'latitude' => '-6.261084',
                'longitude' => '106.888774',
            ],
            [
                'nama_proyek' => 'Pengecoran Lantai Pondasi Rumah Sakura Regency 3',
                'foto' => 'assets/proyek/Pengecoran Lantai Pondasi Rumah Sakura Regency 3.jpg',
                'client' => 'PT. Toyota Housing Indonesia',
                'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bekasi',
                'latitude' => '-6.2813794',
                'longitude' => '107.0118061',
            ],
            [
                'nama_proyek' => 'Pembuatan Workshop Office Facility Distrik JIYP',
                'foto' => 'assets/proyek/Pembuatan Workshop Office Facility Distrik JIYP.jpg',
                'client' => 'PT. Pamapersada Nusantara',
                'alamat' => 'Jl. Rawagelam I No.9, RW.9, Jatinegara, Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13930',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Timur',
                'latitude' => '-6.1979648',
                'longitude' => '106.8792105',
            ],
            [
                'nama_proyek' => 'Pembuatan Rumah Type Engawa Sakura Regency Toyota Housing Indonesia',
                'foto' => 'assets/proyek/Pembuatan Rumah Type Engawa Sakura Regency Toyota Housing Indonesia.jpg',
                'client' => 'PT. Toyota Housing Indonesia',
                'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bekasi',
                'latitude' => '-6.2813794',
                'longitude' => '107.0118061',
            ],
            [
                'nama_proyek' => 'Pembuatan Rumah Type H Sakura Regency Toyota Housing Indonesia',
                'foto' => 'assets/proyek/Pembuatan Rumah Type H Sakura Regency Toyota Housing Indonesia.jpg',
                'client' => 'PT. Toyota Housing Indonesia',
                'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bekasi',
                'latitude' => '-6.2813794',
                'longitude' => '107.0118061',
            ],
            [
                'nama_proyek' => 'Pembuatan Rumah Type Teitaku 1 Sakura Regency Toyota Housing Indonesia',
                'foto' => 'assets/proyek/Pembuatan Rumah Type Teitaku 1 Sakura Regency Toyota Housing Indonesia.jpg',
                'client' => 'PT. Toyota Housing Indonesia',
                'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bekasi',
                'latitude' => '-6.2813794',
                'longitude' => '107.0118061',
            ],
            [
                'nama_proyek' => 'Pembuatan Pondasi Rumah Sakura Regency Toyota Housing Indonesia',
                'foto' => 'assets/proyek/Pembuatan Pondasi Rumah Sakura Regency Toyota Housing Indonesia.jpg',
                'client' => 'PT. Toyota Housing Indonesia',
                'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bekasi',
                'latitude' => '-6.2813794',
                'longitude' => '107.0118061',
            ],
            [
                'nama_proyek' => 'Pembuatan Driver Room PIK Avenue Mall',
                'foto' => 'assets/proyek/Pembuatan Driver Room PIK Avenue Mall.jpg',
                'client' => 'PT. Multi Artha Pratama',
                'alamat' => 'Pantai Indah Kapuk St Boulevard, Kamal Muara, Penjaringan, North Jakarta City, Jakarta 14470',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Utara',
                'latitude' => '-6.1091684',
                'longitude' => '106.7404088',
            ],
            [
                'nama_proyek' => 'Pembuatan Kantin Karyawan PIK Avenue Mall',
                'foto' => 'assets/proyek/Pembuatan Kantin Karyawan PIK Avenue Mall.jpg',
                'client' => 'PT. Multi Artha Pratama',
                'alamat' => 'Pantai Indah Kapuk St Boulevard, Kamal Muara, Penjaringan, North Jakarta City, Jakarta 14470',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Utara',
                'latitude' => '-6.1091684',
                'longitude' => '106.7404088',
            ],
        ))
        // ->for($SM1, 'setManager')
        ->create();

        Kendaraan::factory(7)->state(new Sequence(
            [
                "gambar" => "assets/kendaraan/Revo Attractive Red.jpg",
                "merk" => "Revo Attractive Red",
            ],
            [
                "gambar" => "assets/kendaraan/MPM Vario 160.jpg",
                "merk" => "Honda Vario 160",
            ],
            [
                "gambar" => "assets/kendaraan/Sub Beat.jpg",
                "merk" => "Honda Beat",
            ],
            [
                "gambar" => "assets/kendaraan/Supra X 125 Golden Matte.jpg",
                "merk" => "Supra X 125 Golden Matte",
            ],
            [
                "gambar" => "assets/kendaraan/Yamaha Vino.jpg",
                "merk" => "Yamaha Vino",
            ],
            [
                "gambar" => "assets/kendaraan/GTR150 Sporty Spartan Red.jpg",
                "merk" => "GTR150 Sporty Spartan Red",
            ],
            [
                "gambar" => "assets/kendaraan/Honda pcx ehev.jpg",
                "merk" => "Honda PCX ehev",
            ],
        ))->motor()->create();
        
        Kendaraan::factory(4)->state(new Sequence(
            [
                "gambar" => "assets/kendaraan/Honda BRV.jpg",
                "merk" => "Honda BRV",
            ],
            [
                "gambar" => "assets/kendaraan/Honda Civic.jpg",
                "merk" => "Honda Civic",
            ],
            [
                "gambar" => "assets/kendaraan/Gran Max Minibus FH E4.jpg",
                "merk" => "Gran Max Minibus FH E4",
            ],
            [
                "gambar" => "assets/kendaraan/Toyota Yaris.jpg",
                "merk" => "Toyota Yaris",
            ],
        ))->mobil()->create();
        
        Kendaraan::factory(2)->state(new Sequence(
            [
                "gambar" => "assets/kendaraan/Gran Max Minibus FH E4.jpg",
                "merk" => "Gran Max Minibus FH E4",
            ],
            [
                "gambar" => "assets/kendaraan/APV New Luxury.jpg",
                "merk" => "APV New Luxury",
            ],
        ))->minibus()->create();
        
        Kendaraan::factory(4)->state(new Sequence(
            [
                'merk' => 'Daihatsu',
                'gambar' => 'assets/barang/TidakHabisPakai/Pickup Daihatsu.jpg',
            ],
            [
                'merk' => 'Gran Max Pick Up AC PS',
                'gambar' => 'assets/kendaraan/Gran Max Pick Up AC PS.jpg',
            ],
            [
                'merk' => 'Gran Max Pick Up PU AC',
                'gambar' => 'assets/kendaraan/Gran Max Pick Up PU AC.jpg',
            ],
            [
                'merk' => 'Suzuki New Carry Pick Up',
                'gambar' => 'assets/kendaraan/Suzuki New Carry Pick Up.jpg',
            ],
        ))->pickup()->create();
        
        Kendaraan::factory(5)->state(new Sequence(
            [
                "gambar" => "assets/kendaraan/Fighter X FM 65 FS Hi-Gear.jpg",
                "merk" => "Fighter X FM 65 FS Hi-Gear",
            ],
            [
                "gambar" => "assets/kendaraan/Canter FE 73.jpg",
                "merk" => "Canter FE 73",
            ],
            [
                'merk' => 'Isuzu Dump Truck',
                'gambar' => 'assets/barang/TidakHabisPakai/Dumpt Truck Isuzu.jpg',
            ],
            [
                'merk' => 'Toyota Dyna 125 HT',
                'gambar' => 'assets/barang/TidakHabisPakai/Dyna 125 ht toyota.jpg',
            ],
            [
                'merk' => 'Mitsubishi',
                'gambar' => 'assets/barang/TidakHabisPakai/Medium-Duty Truck Mitsubishi.jpg',
            ],
        ))->truck()->create();
        
        Kendaraan::factory(3)->state(new Sequence(
            [
                "gambar" => "assets/kendaraan/Fighter X FN 62 F HDR.jpg",
                "merk" => "Fighter X FN 62 F HDR",
            ],
            [
                "gambar" => "assets/kendaraan/Fighter X FN 62 F Tractor Head.jpg",
                "merk" => "Fighter X FN 62 F Tractor Head",
            ],
            [
                "gambar" => "assets/kendaraan/Tracktor-Head Truck Mitsubishi.jpg",
                "merk" => "Tracktor-Head Mitsubishi",
            ],
        ))->tronton()->create();

        Menangani::factory(50)->create();

        Peminjaman::factory(40)->menungguAksesGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->menungguAksesGp(), 'peminjamanDetail')
        ->create();
        
        Peminjaman::factory(40)->menungguAksesGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->aksesDitolakGp(), 'peminjamanDetail')
        ->create();
        
        Peminjaman::factory(40)->menungguSuratJalanGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->menungguSuratJalanGp(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->menungguPengirimanGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->menungguPengirimanGp(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->sedangDikirimGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->sedangDikirimGp(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->dipinjamGp()
        ->has(PeminjamanDetail::factory(8)->resetData()->dipinjamGp(), 'peminjamanDetail')
        ->create();
        
        Peminjaman::factory(40)->selesaiGpWithPengembalianMenungguSuratJalan()
        ->has(PeminjamanDetail::factory(8)->resetData()->selesaiGpWithPengembalianMenungguSuratJalan(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->selesaiGpWithPengembalianSedangDikembalikan()
        ->has(PeminjamanDetail::factory(8)->resetData()->selesaiGpWithPengembalianSedangDikembalikan(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->selesaiGpWithPengembalianMenungguPengembalian()
        ->has(PeminjamanDetail::factory(8)->resetData()->selesaiGpWithPengembalianMenungguPengembalian(), 'peminjamanDetail')
        ->create();

        Peminjaman::factory(40)->selesaiGpWithPengembalianSelesai()
        ->has(PeminjamanDetail::factory(8)->resetData()->selesaiGpWithPengembalianSelesai(), 'peminjamanDetail')
        ->create();

        Penggunaan::factory(40)->menungguSuratJalanGp()
            ->has(PenggunaanDetail::factory(8)->resetData(), 'penggunaanDetail')
            ->create();

        Penggunaan::factory(40)->menungguPengirimanGp()
        ->has(PenggunaanDetail::factory(8)->resetData(), 'penggunaanDetail')
        ->create();

        Penggunaan::factory(40)->sedangDikirimGp()
        ->has(PenggunaanDetail::factory(8)->resetData(), 'penggunaanDetail')
        ->create();

        Penggunaan::factory(40)->dipinjamGp()
        ->has(PenggunaanDetail::factory(8)->resetData(), 'penggunaanDetail')
        ->create();
        
        Penggunaan::factory(40)->selesaiGp()
        ->has(PenggunaanDetail::factory(8)->resetData(), 'penggunaanDetail')
        ->create();

        do{
            $pengembalian = Pengembalian::doesntHave('pengembalianDetail')->first();
            $peminjaman = ($pengembalian!=NULL) ? PeminjamanDetail::where('peminjaman_id', $pengembalian->peminjaman_id)->get() : NULL;
            if ($peminjaman!=NULL){
                foreach($peminjaman as $peminjamanDetail){
                    $kode_pengembalian = Pengembalian::generateKodePengembalian($peminjamanDetail->peminjaman->menangani->proyek->client, $peminjamanDetail->peminjaman->menangani->user->nama);
                    Pengembalian::where('id',$pengembalian->id)->update(['kode_pengembalian' => $kode_pengembalian]);
                    PengembalianDetail::factory()->state([
                        "barang_id" => $peminjamanDetail->barang_id,
                        "pengembalian_id" => $pengembalian->id,
                    ])->create();
                }
            }
        }while($pengembalian!=NULL);

        // do{
        //     $pengembalian = PengembalianPenggunaan::doesntHave('pengembalianPenggunaanDetail')->first();
        //     $penggunaan = ($pengembalian!=NULL) ? PenggunaanDetail::where('penggunaan_id', $pengembalian->penggunaan_id)->get() : NULL;
        //     if ($penggunaan!=NULL){
        //         foreach($penggunaan as $penggunaanDetail){
        //             $satuan = NULL;
        //             $jumlah = NULL;
        //             $kode_pengembalian = PengembalianPenggunaan::generateKodePengembalian($penggunaanDetail->penggunaan->menangani->proyek->client, $penggunaanDetail->penggunaan->menangani->user->nama);
        //             PengembalianPenggunaan::where('id',$pengembalian->id)->update(['kode_pengembalian' => $kode_pengembalian]);
        //             $barang = BarangHabisPakai::find($penggunaanDetail->barang_id);
        //             $satuan = $barang->satuan;
        //             $jumlah = fake()->numberBetween(1, $barang->jumlah);
        //             $jumlah_satuan = "$jumlah $satuan";
        //             PengembalianPenggunaanDetail::factory()->state([
        //                 "barang_id" => $penggunaanDetail->barang_id,
        //                 "pengembalian_bahan_id" => $pengembalian->id,
        //                 "jumlah_satuan" => $jumlah_satuan
        //             ])->create();
        //         }
        //     }
        // }while($pengembalian!=NULL);
        
        DeliveryOrder::factory(20)->has(PreOrder::factory()->count(10))->create();

        $logistics = Logistic::get();
        foreach($logistics as $logistic){
            $request = new Request([
                'user_id'   => $logistic->user_id,
                'latitude' => $logistic->latitude,
                'longitude' => $logistic->longitude,
                'bearing' => 0.0,
                'speed' => 0.0,
                'accuracy' => 0.0,
            ]);
            LogisticFirebase::setData($request);
        }
        IDGenerator::reorderAll();
    }
}
