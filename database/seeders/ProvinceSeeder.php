<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json')->collect();
        foreach ($provinces as $province) {
            $p = \App\Models\Province::create([
                'id' => $province['id'],
                'name' => $province['name'],
            ]);
            $districts = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/' . $province['id'] . '.json')->collect();
            foreach ($districts as $district) {
                $d = \App\Models\District::create([
                    'id' => $district['id'],
                    'name' => $district['name'],
                    'province_id' => $province['id'],
                ]);
                $subdistricts = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/districts/' . $district['id'] . '.json')->collect();
                foreach ($subdistricts as $subdistrict) {
                    \App\Models\Subdistrict::create([
                        'id' => $subdistrict['id'],
                        'name' => $subdistrict['name'],
                        'district_id' => $district['id'],
                    ]);
                }
            }
        }
    }
}
