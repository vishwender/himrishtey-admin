<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Site;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminRbacSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            [
                'name' => 'View Members',
                'slug' => 'view-members',
                'description' => 'View matrimonial members',
            ],
            [
                'name' => 'Create Members',
                'slug' => 'create-members',
                'description' => 'Create new members',
            ],
            [
                'name' => 'Edit Members',
                'slug' => 'edit-members',
                'description' => 'Edit member information',
            ],
            [
                'name' => 'Delete Members',
                'slug' => 'delete-members',
                'description' => 'Delete members',
            ],

            [
                'name' => 'View Photos',
                'slug' => 'view-photos',
                'description' => 'View member photos',
            ],
            [
                'name' => 'Approve Photos',
                'slug' => 'approve-photos',
                'description' => 'Approve member photos',
            ],
            [
                'name' => 'Reject Photos',
                'slug' => 'reject-photos',
                'description' => 'Reject member photos',
            ],

            [
                'name' => 'View Memberships',
                'slug' => 'view-memberships',
                'description' => 'View memberships',
            ],
            [
                'name' => 'Manage Memberships',
                'slug' => 'manage-memberships',
                'description' => 'Manage membership plans and subscriptions',
            ],

            [
                'name' => 'View Payments',
                'slug' => 'view-payments',
                'description' => 'View payment transactions',
            ],
            [
                'name' => 'Manage Payments',
                'slug' => 'manage-payments',
                'description' => 'Manage payments',
            ],

            [
                'name' => 'View Wallet',
                'slug' => 'view-wallet',
                'description' => 'View member wallet information',
            ],
            [
                'name' => 'Manage Wallet',
                'slug' => 'manage-wallet',
                'description' => 'Manage member wallet',
            ],

            [
                'name' => 'Manage Admins',
                'slug' => 'manage-admins',
                'description' => 'Create and manage administrators',
            ],
            [
                'name' => 'Manage Roles',
                'slug' => 'manage-roles',
                'description' => 'Create and manage roles',
            ],
            [
                'name' => 'Manage Sites',
                'slug' => 'manage-sites',
                'description' => 'Create and manage matrimonial sites',
            ],
            [
                'name' => 'Manage Settings',
                'slug' => 'manage-settings',
                'description' => 'Manage admin settings',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdminRole = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full access to all sites and admin functionality',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Give Super Admin all permissions
        |--------------------------------------------------------------------------
        */

        $superAdminRole->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Super Admin Account
        |--------------------------------------------------------------------------
        */

        $admin = Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'profile_id' => 1,
                'password' => Hash::make('ChangeMe@123'),
                'status' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Assign Super Admin Role
        |--------------------------------------------------------------------------
        */

        $admin->roles()->syncWithoutDetaching([
            $superAdminRole->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Give Super Admin Access To All Sites
        |--------------------------------------------------------------------------
        */

        $admin->sites()->sync(
            Site::where('status', true)->pluck('id')->toArray()
        );

        $this->command->info('Admin RBAC seeded successfully.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: ChangeMe@123');
    }
}
