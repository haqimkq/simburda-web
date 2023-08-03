<?php
namespace App\Enum;


enum PengembalianStatus:string
{
    case MENUNGGU_SURAT_JALAN = 'MENUNGGU_SURAT_JALAN';
    case MENUNGGU_PEGEMBALIAN = 'MENUNGGU_PEGEMBALIAN';
    case SEDANG_DIKEMBALIKAN = 'SEDANG_DIKEMBALIKAN';
    case SELESAI = 'SELESAI';
}