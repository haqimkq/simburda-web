<?php
namespace App\Enum;


enum UserRole:string
{
    case PROJECT_MANAGER = 'PROJECT_MANAGER';
    case SUPERVISOR = 'SUPERVISOR';
    case PICKUP = 'PICKUP';
    case ADMIN_GUDANG = 'ADMIN_GUDANG';
    case LOGISTIC = 'LOGISTIC';
    case PURCHASING = 'PURCHASING';
    case ADMIN = 'ADMIN';
    case USER = 'USER';
    case SET_MANAGER = 'SET_MANAGER';
}