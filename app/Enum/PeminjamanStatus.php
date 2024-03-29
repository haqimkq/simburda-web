<?php
namespace App\Enum;


enum PeminjamanStatus:string
{
    case MENUNGGU_AKSES = 'MENUNGGU_AKSES';
    case MENUNGGU_SURAT_JALAN = 'MENUNGGU_SURAT_JALAN';
    case MENUNGGU_PENGIRIMAN = 'MENUNGGU_PENGIRIMAN';
    case SEDANG_DIKIRIM = 'SEDANG_DIKIRIM';
    case DIPINJAM = 'DIPINJAM';
    case SELESAI = 'SELESAI';
}