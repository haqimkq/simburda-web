<?php
namespace App\Enum;


enum SuratJalanStatus:string
{
    case MENUNGGU_KONFIRMASI_DRIVER = 'MENUNGGU_KONFIRMASI_DRIVER';
    case DRIVER_DALAM_PERJALANAN = 'DRIVER_DALAM_PERJALANAN';
    case SELESAI = 'SELESAI';
}