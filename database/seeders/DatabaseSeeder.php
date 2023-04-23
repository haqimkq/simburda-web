<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AdminGudang;
use App\Models\User;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\Logistic;
use App\Models\Meminjam;
use App\Models\PreOrder;
use App\Models\Kendaraan;
use App\Models\menangani;
use App\Models\SuratJalan;
use App\Models\AksesBarang;
use Illuminate\Support\Str;
use App\Models\DeliveryOrder;
use App\Models\Gudang;
use App\Models\ProjectManager;
use App\Models\Purchasing;
use App\Models\Supervisor;
use Database\Factories\ProyekFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $allLogistic = Logistic::factory(10)->for(
            User::factory()->state(
                ['role'=>'LOGISTIC']
            )
        )->create();
        $allSupervisor = Supervisor::factory(10)->for(
            User::factory()->state(
                ['role'=>'SUPERVISOR']
            )
        )->create();
        $allPurchasing = Purchasing::factory(10)->for(
            User::factory()->state(
                ['role'=>'PURCHASING']
            )
        )->create();
        $admin1 = User::factory(1)->state(
            [
                'role'=>'ADMIN',
                'nama' => 'Erna B. Wijayanti, ST.MT.',
                'email' => 'ernawijayanti@gmail.com',
                'foto' => 'assets/users/Director_Erna B. Wijayanti, ST.MT.jpeg'
            ]
        )->create();
        $purchasing1 = Purchasing::factory(1)->for(
            User::factory()->state(
                [
                    'role'=>'PURCHASING',
                    'nama' => 'Meita Wulansuci S., SH',
                    'email' => 'meitawulansuci@gmail.com',
                    'foto' => 'assets/users/General Affair_Wulansuci S., SH.jpeg'
                ]
            )
        )->create();
        $adminGudang1 = AdminGudang::factory(1)->for(
            User::factory()->state(
                [
                    'role'=>'ADMIN_GUDANG',
                    'nama' => 'Ghani Pratama',
                    'email' => 'ghanipratama@gmail.com'
                ]
            )
        )->create();
        $logistic1 = Logistic::factory(1)->for(
            User::factory()->state(
                [
                    'role'=>'LOGISTIC',
                    'nama' => 'Andro',
                    'email' => 'andro@gmail.com'
                ]
            )
        )->create();
        $gudang = Gudang::factory()->state(new Sequence(
            [
                'nama' => 'Gudang Jakarta 1',
                'alamat' => '',
                'latitude' => '',
                'longitude' => '',
                'kota' => '',
                'provinsi' => '',
                'gambar' => ''
            ],
        ));
        $PM1 = ProjectManager::factory(1)->for(
            User::factory()->state(
                [
                    'role'=>'PROJECT_MANAGER',
                    'nama' => 'Novita Cahyanintyas, ST.',
                    'email' => 'novitacahya@gmail.com',
                    'foto' => 'assets/users/Project Manager_Novita Cahyanintyas, ST.jpeg'
                ]
            )
        )->create();
        $projectManagers = ProjectManager::factory(7)->for(
            User::factory()->state(
                ['role'=>'PROJECT_MANAGER']
            )
        )->create();
        $proyek1 = Proyek::factory()->state([
            'nama_proyek' => 'Perumahan Sakura Regency 3 Toyota Housing Indonesia Rumah Blok H29',
            'foto' => 'assets/proyek/Perumahan Sakura Regency 3 Toyota Housing Indonesia Rumah Blok H29.jpg',
            'client' => 'PT. Toyota Housing Indonesia',
            'alamat' => 'Jl. Cipete Raya No.Kel, Jatimulya, Kec. Tambun Sel., Kota Bks, Jawa Barat 17510',
            'provinsi' => 'Jawa Barat',
            'kota' => 'Bekasi',
            'latitude' => '-6.2813794',
            'longitude' => '107.0118061',
        ])->for($PM1)->create();

        
        $proyekPM1Selesai = Proyek::factory();

        $proyekList = Proyek::factory(9)->create()
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
                ))->for($PM1)->create();

        $gudang = Gudang::factory(3)->create()->state(new Sequence(
                    ['admin' => 'Y'],
                    ['admin' => 'N'],
                ));

        $adminGudang = AdminGudang::factory(10)->for(
            User::factory()->state(
                ['role'=>'ADMIN_GUDANG']
            )->for($gudang)
        )->create();

        SuratJalan::factory(20)->create();

        User::factory(10)->state([
            'role' => 'LOGISTIC'
        ])->hasLogistic(1)->has(
            SuratJalan::factory()
            ->count(20)->state([])
        );

        
        ProjectManager::factory(15)
        ->forUser([
            'role' => 'PROJECT_MANAGER'
        ]);
        Supervisor::factory(15)
        ->forUser([
            'role' => 'SUPERVISOR'
        ]);
        AdminGudang::factory(15)
        ->forUser([
            'role' => 'ADMIN_GUDANG'
        ]);
        Purchasing::factory()
        ->count(15)
        ->forUser([
            'role' => 'PURCHASING'
        ]);

        Proyek::factory()
        ->count(10)
        ->forProjectManager([
            'nama'
        ]);

        // User::factory(40)->create();
        // Barang::factory(100)->create();
        // for ($i=0; $i < 20; $i++) { 
        //     Kendaraan::factory(1)->create();
        // }
        // Logistic::factory(20)->create();
        // DeliveryOrder::factory(50)->create();
        // PreOrder::factory(100)->create();
        // Proyek::factory(20)->create();
        // Menangani::factory(20)->create();
        // SuratJalan::factory(20)->create();
        // Meminjam::factory(100)->create();
        // for ($i=0; $i < 100; $i++) {
        //     AksesBarang::factory(1)->create();
        // }
    }
}
