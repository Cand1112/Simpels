<?php

namespace App\Filament\Widgets;

use App\Models\Scholarship;
use App\Models\Spk;
use App\Models\SpkStudentRank;
use App\Models\StudyProgram;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class ScholarshipStudentRank extends Widget implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.widgets.scholarship-student-rank';

    protected function getTableQuery(): Builder
    {
        return SpkStudentRank::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->label('Ranking')
                ->sortable()
                ->searchable(),
            TextColumn::make('student')
                ->label('Nama Mahasiswa')
                ->sortable()
                ->searchable(),
            TextColumn::make('registration_number')
                ->label('NIM')
                ->sortable()
                ->searchable(),
            TextColumn::make('study_program')
                ->label('Program Studi')
                ->sortable()
                ->searchable(),
            TextColumn::make('scholarship')
                ->label('Nama Beasiswa')
                ->sortable()
                ->searchable(),
            TextColumn::make('yi')
                ->label('Nilai Yi')
                ->sortable()
                ->searchable(),
        ];
    }


    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('scholarship')
                ->label('Nama Beasiswa')
                ->searchable()
                ->options(fn () => Scholarship::all()->pluck('name', 'name')->toArray()),
            SelectFilter::make('study_program')
                ->label('Program Studi')
                ->searchable()
                ->options(fn () => StudyProgram::all()->pluck('name', 'name')->toArray()),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }
}
