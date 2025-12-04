<?php

namespace App\Helpers;

class RoleHelper
{
    public static function determineRoleFromEmail($email)
    {
        return match (true) {
            str_ends_with($email, '_admin@styluxe.com') => 'admin',
            str_ends_with($email, '_staff@styluxe.com') => 'staff',
            str_ends_with($email, '_supplier@styluxe.com') => 'supplier',
            default => 'client',
        };
    }
}

?>