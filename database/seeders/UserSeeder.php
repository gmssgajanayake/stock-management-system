<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;   // ✅ ADD THIS
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin1234'),
            ]
        );
        $admin->assignRole('admin');

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('manager1234'),
            ]
        );
        $manager->assignRole('manager');

        // Cashier
        $cashier = User::firstOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'name' => 'Cashier',
                'password' => bcrypt('cashier1234'),
            ]
        );
        $cashier->assignRole('cashier');

        // Inventory
        $inventory = User::firstOrCreate(
            ['email' => 'inventory@gmail.com'],
            [
                'name' => 'Inventory Admin',
                'password' => bcrypt('inventory1234'),
            ]
        );
        $inventory->assignRole('inventory');
    }
}
