<?php

namespace App\Enums;

enum RolesEnum: string
{
        //case NAMEINAPP = 'name-in-database';

    case SUPERADMIN = 'super-admin';
    case ADMIN = 'admin';

    //extra helper to allow for greater customization of displayed valuses, without disclosed the name/value data directly

    public function label(): string
    {
        return match ($this) {
            static::SUPERADMIN => 'Super-Admins',
            static::ADMIN => 'Admins',
        };
    }
}
