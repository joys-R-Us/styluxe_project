<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'rizza_admin@styluxe.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Manager User',
                'email' => 'fiona_manager@styluxe.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Staff User',
                'email' => 'cj_staff@styluxe.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Supplier One',
                'email' => 'fashionco_supplier@styluxe.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Supplier Two',
                'email' => 'vintageshop_supplier@styluxe.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Client One',
                'email' => 'client1@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}