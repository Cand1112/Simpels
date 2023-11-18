<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ipk = Criteria::create([
            'name' => 'IPK',
            'type' => 'benefit',
            'weight' => 0.1,
        ]);
        $ipk->subcriterias()->createMany([
            [
                'name' => '2.5 < IPK ≤ 2.75',
                'weight' => 1,
            ],
            [
                'name' => '2.75 < IPK ≤ 3',
                'weight' => 2,
            ],
            [
                'name' => '3 < IPK ≤ 3.5',
                'weight' => 3,
            ],
            [
                'name' => '3.5 < IPK ≤ 4',
                'weight' => 4,
            ],
        ]);

        $ukt = Criteria::create([
            'name' => 'UKT',
            'type' => 'cost',
            'weight' => 0.2,
        ]);
        $ukt->subcriterias()->createMany([
            [
                'name' => 'Rp. 500.000 < UKT ≤ Rp. 3.000.000',
                'weight' => 1,
            ],
            [
                'name' => 'Rp. 3.000.000 < UKT ≤ Rp. 6.000.000',
                'weight' => 2,
            ],
            [
                'name' => 'Rp. 6.000.000 < UKT ≤ Rp. 9.000.000',
                'weight' => 3,
            ],
            [
                'name' => 'Rp. 9.000.000 < UKT ≤ Rp. 12.000.000',
                'weight' => 4,
            ],
        ]);

        $active = Criteria::create([
            'name' => 'Mahasiswa Aktif',
            'type' => 'benefit',
            'weight' => 0.3,
        ]);
        $active->subcriterias()->createMany([
            [
                'name' => 'Semester 7 - Semester 8',
                'weight' => 1,
            ],
            [
                'name' => 'Semester 5 - Semester 6',
                'weight' => 2,
            ],
            [
                'name' => 'Semester 3 - Semester 4',
                'weight' => 3,
            ],
            [
                'name' => 'Semester 2',
                'weight' => 4,
            ],
        ]);

        $organization = Criteria::create([
            'name' => 'Pengalaman Organisasi',
            'type' => 'benefit',
            'weight' => 0.4,
        ]);
        $organization->subcriterias()->createMany([
            [
                'name' => 'Tidak Ada',
                'weight' => 1,
            ],
            [
                'name' => '1 < PO ≤ 3',
                'weight' => 2,
            ],
            [
                'name' => '3 < PO ≤ 6',
                'weight' => 3,
            ],
            [
                'name' => 'PO > 6',
                'weight' => 4,
            ],
        ]);
    }
}
