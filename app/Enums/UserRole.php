<?php

namespace App\Enums;

/** 
 * UserRole – Role pengguna dalam sistem
 */
enum UserRole: string {
    case Admin = 'admin';
    case User  = 'user';
}
