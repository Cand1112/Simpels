<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodi = [
            "Fisika",
            "Matematika",
            "Teknik Mesin",
            "Teknik Elektro",
            "Teknik Kimia",
            "Teknik Material dan Metalurgi",
            "Teknik Sipil",
            "Teknik Prencanaan Wilayah dan Kota",
            "Teknik Perkapalan",
            "Sistem Informasi",
            "Teknik Informatika",
            "Teknik Industri",
            "Teknik Lingkungan",
            "Teknik Kelautan",
            "Bisnis Digital",
            "Teknik Arsitektur",
            "Kesehatan dan Keselamatan Kerja",
            "Aktuaria",
            "Desain Komunikasi Visual",
        ];
        foreach ($prodi as $p) {
            \App\Models\StudyProgram::create([
                'name' => $p,
            ]);
        }
    }
}
