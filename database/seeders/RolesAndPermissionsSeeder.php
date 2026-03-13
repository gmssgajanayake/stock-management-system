<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = ['manage users', 'manage products', 'manage customers', 'manage orders'];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all()); // sync in case new permissions were added

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions(['manage products', 'manage customers', 'manage orders']);

        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->syncPermissions(['manage orders', 'manage customers']);

        $inventory = Role::firstOrCreate(['name' => 'inventory']);
        $inventory->syncPermissions(['manage products']);
    }
}
