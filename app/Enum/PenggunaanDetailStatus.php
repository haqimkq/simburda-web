<?php
namespace App\Enum;


enum PenggunaanDetailStatus:string
{
    case MENUNGGU_AKSES = 'MENUNGGU_AKSES';
    case DIGUNAKAN = 'DIGUNAKAN';
    case TIDAK_DIGUNAKAN = 'TIDAK_DIGUNAKAN';
    case DIGUNAKAN_PROYEK_LAIN = 'DIGUNAKAN_PROYEK_LAIN';
    case DIKEMBALIKAN = 'DIKEMBALIKAN';
}