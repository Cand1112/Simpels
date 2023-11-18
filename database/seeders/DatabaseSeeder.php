<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call(ProvinceSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(StudyProgramSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CriteriaSeeder::class);
        $this->call(SubcriteriaSeeder::class);
        $this->call(ScholarshipSeeder::class);
    }
}
