<?php
namespace App\Enum;


enum PeminjamanDetailStatus:string
{
    case MENUNGGU_AKSES = 'MENUNGGU_AKSES';
    case DIGUNAKAN = 'DIGUNAKAN';
    case TIDAK_DIGUNAKAN = 'TIDAK_DIGUNAKAN';
    case DIPINJAM_PROYEK_LAIN = 'DIPINJAM_PROYEK_LAIN';
    case DIKEMBALIKAN = 'DIKEMBALIKAN';
}