<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Super Admin Role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description'  => 'Full system access. Cannot be deleted.',
                'guard_name'   => 'admin',
                'is_system'    => true,
            ]
        );

        // 2. Create Permissions Matrix
        $modules = [
            'Products' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],
            'Vendors'  => ['view', 'create', 'edit', 'delete', 'approve', 'reject', 'suspend'],
            'Categories' => ['view', 'create', 'edit', 'delete'],
            'Brands'   => ['view', 'create', 'edit', 'delete'],
            'Orders'   => ['view', 'edit', 'cancel', 'refund'],
            'Inventory' => ['view', 'adjust'],
            'Reports'  => ['view', 'export'],
            'CMS'      => ['view', 'create', 'edit', 'delete'],
            'Banners'  => ['view', 'create', 'edit', 'delete'],
            'Settings' => ['view', 'edit'],
            'Roles'    => ['view', 'create', 'edit', 'delete'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = strtolower($module) . '.' . $action;
                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'display_name' => ucfirst($action) . ' ' . $module,
                        'module'       => $module,
                        'description'  => 'Allows user to ' . $action . ' ' . strtolower($module),
                        'guard_name'   => 'admin',
                    ]
                );
            }
        }

        // Assign all permissions to super_admin role
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->sync($allPermissions->pluck('id'));

        // 3. Create Default Super Admin Account
        $adminEmail = env('SUPER_ADMIN_EMAIL', 'admin@garudo.com');
        $adminPassword = env('SUPER_ADMIN_PASSWORD', 'Garudo@Admin2026#Secure!');

        $admin = Admin::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'           => 'Super Admin',
                'phone'          => '9876543210',
                'password'       => Hash::make($adminPassword),
                'is_super_admin' => true,
                'status'         => 'active',
            ]
        );

        $admin->roles()->sync([$superAdminRole->id]);
    }
}
