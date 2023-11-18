<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'Bank Indonesia',
            'Berdaya KPC',
            'Kaltim Tuntas',
            'Indonesia Cyber Education',
            'Mahakam',
            'Afirmasi Pertamina'
        ];

        $bi = Scholarship::create([
            'name' => 'Bank Indonesia',
            'c1_subcriteria_id' => 2,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 11,
            'c4_subcriteria_id' => 14,
        ]);

        $bk = Scholarship::create([
            'name' => 'Berdaya KPC',
            'c1_subcriteria_id' => 1,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 11,
            'c4_subcriteria_id' => 13,
        ]);

        $kt = Scholarship::create([
            'name' => 'Kaltim Tuntas',
            'c1_subcriteria_id' => 1,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 12,
            'c4_subcriteria_id' => 13,
        ]);

        $ice = Scholarship::create([
            'name' => 'Indonesia Cyber Education',
            'c1_subcriteria_id' => 2,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 12,
            'c4_subcriteria_id' => 13,
        ]);

        $mhk = Scholarship::create([
            'name' => 'Mahakam',
            'c1_subcriteria_id' => 1,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 12,
            'c4_subcriteria_id' => 14,
        ]);

        $afp = Scholarship::create([
            'name' => 'Afirmasi Pertamina',
            'c1_subcriteria_id' => 1,
            'c2_subcriteria_id' => 5,
            'c3_subcriteria_id' => 12,
            'c4_subcriteria_id' => 14,
        ]);
    }
}
