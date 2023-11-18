<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@itk.ac.id',
            'registration_number' => '00000000',
        ]);
        $superAdmin->assignRole(\App\Enums\Role::SuperAdmin->value);

        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@itk.ac.id',
            'registration_number' => '11111111',
        ]);
        $admin->assignRole(\App\Enums\Role::Admin->value);

        $user = \App\Models\User::factory()->create([
            'name' => 'Arya Candra',
            'email' => '11191012@student.itk.ac.id',
            'registration_number' => '11191012',
        ]);
        $user->assignRole(\App\Enums\Role::Student->value);

        \App\Models\User::factory()->count(50)->create()->each(function ($user) {
            $user->assignRole(\App\Enums\Role::Student->value);
        });
    }
}
