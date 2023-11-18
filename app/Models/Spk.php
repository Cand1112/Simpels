<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Spk extends Model
{
    use \Sushi\Sushi;

    public function getRows(): array
    {
        $scholarship = \App\Models\Scholarship::all();

        $rankOnScholarship = collect();
        $scholarship->each(function ($scholarship) use ($rankOnScholarship) {
            $students = \App\Models\User::whereIsActive(true)
                ->whereHas('roles', function ($query) {
                    $query->where('name', \App\Enums\Role::Student->value);
                })
                ->where(function (Builder $query) use ($scholarship) {
                    if ($scholarship->subdistrict_id) {
                        $query->where('subdistrict_id', $scholarship->subdistrict_id);
                    } elseif ($scholarship->district_id) {
                        $query->where('district_id', $scholarship->district_id);
                    } elseif ($scholarship->province_id) {
                        $query->where('province_id', $scholarship->province_id);
                    }
                })
                ->where('c1_subcriteria_id', '>=', $scholarship->c1_subcriteria_id)
                ->where('c2_subcriteria_id', '>=', $scholarship->c2_subcriteria_id)
                ->where('c3_subcriteria_id', '<=', $scholarship->c3_subcriteria_id)
                ->where('c4_subcriteria_id', '>=', $scholarship->c4_subcriteria_id)
                ->get();

            $pembagi = [
                'c1' => 0,
                'c2' => 0,
                'c3' => 0,
                'c4' => 0,
            ];
            $students->each(function ($student) use ($scholarship, &$pembagi) {
                $pembagi['c1'] += pow($student->c1Subcriteria->weight, 2);
                $pembagi['c2'] += pow($student->c2Subcriteria->weight, 2);
                $pembagi['c3'] += pow($student->c3Subcriteria->weight, 2);
                $pembagi['c4'] += pow($student->c4Subcriteria->weight, 2);
            });
            $pembagi['c1'] = sqrt($pembagi['c1']);
            $pembagi['c2'] = sqrt($pembagi['c2']);
            $pembagi['c3'] = sqrt($pembagi['c3']);
            $pembagi['c4'] = sqrt($pembagi['c4']);

            $normalisasiMatrix = collect();
            $students->each(function ($student) use ($scholarship, $pembagi, $normalisasiMatrix) {
                $normalisasiMatrix->push([
                    'student' => $student,
                    'c1' => $student->c1Subcriteria->weight / $pembagi['c1'],
                    'c2' => $student->c2Subcriteria->weight / $pembagi['c2'],
                    'c3' => $student->c3Subcriteria->weight / $pembagi['c3'],
                    'c4' => $student->c4Subcriteria->weight / $pembagi['c4'],
                ]);
            });

            $optimasi = collect();
            $normalisasiMatrix->each(function ($normalisasi) use ($scholarship, $optimasi) {
                $optimasi->push([
                    'student' => $normalisasi['student'],
                    'c1' => $normalisasi['c1'] * $normalisasi['student']->c1Subcriteria->criteria->weight,
                    'c2' => $normalisasi['c2'] * $normalisasi['student']->c2Subcriteria->criteria->weight,
                    'c3' => $normalisasi['c3'] * $normalisasi['student']->c3Subcriteria->criteria->weight,
                    'c4' => $normalisasi['c4'] * $normalisasi['student']->c4Subcriteria->criteria->weight,
                ]);
            });

            $nilaiY = collect();
            $optimasi->each(function ($optimasi) use ($scholarship, $nilaiY) {
                $nilaiY->push([
                    'student' => $optimasi['student'],
                    'max' => $optimasi['c1']  + $optimasi['c3']  + $optimasi['c4'] ,
                    'min' => $optimasi['c2'] ,
                    'yi' => $optimasi['c1'] + $optimasi['c3']  + $optimasi['c4'] - $optimasi['c2'] ,
                ]);
            });

            if (in_array(auth()->user()->id, $students->pluck('id')->toArray())) {
                $rankOnScholarship->push([
                    'scholarship' => $scholarship,
                    'rank' => collect($nilaiY->sortByDesc('yi')->values()->all())
                ]);
            }
        });

        $data = collect();
        $rankOnScholarship->each(function ($scholarship) use ($data) {
            $data->push([
                'scholarship' => $scholarship['scholarship']->name,
                'userRank' => $scholarship['rank']->search(function ($student) {
                        return $student['student']->id == auth()->user()->id;
                    }) + 1,
                'registrant' => count($scholarship['rank'])
            ]);
        });

        return $data->toArray();
    }
}
