<?php

namespace App\Helpers;

class RoleHelper
{
    public static function determineRoleFromEmail($email)
    {
        $patterns = [
            '/_admin@styluxe\.com$/i' => 'admin',
        ];

        foreach ($patterns as $pattern => $role) {
            if (preg_match($pattern, $email)) {
                return $role;
            }
        }

        return 'client'; // Default role
    }

    public static function getAllRoles()
    {
        return ['admin', 'client'];
    }

    public static function canAccessInventory($role)
    {
        return $role === 'admin';
    }

    public static function getRoleBadgeColor($role)
    {
        $colors = [
            'admin' => '#6C63FF',
            'client' => '#9CA3AF',
        ];

        return $colors[$role] ?? '#9CA3AF';
    }
}

?>