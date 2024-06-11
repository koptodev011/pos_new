<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->setupSuperAdmin();

    }

    protected function setupSuperAdmin()
    {
        $roleName = 'Super Admin';
        Role::create([
            'name' => $roleName
        ]);
        $superAdminUser = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@koptotech.com',
            'password' => bcrypt('Admin@123'),
            'phone' => '7057121459'
        ]);
        $superAdminUser->assignRole($roleName);

    }

}
