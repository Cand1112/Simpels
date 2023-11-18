<?php

namespace App\Filament\Widgets;

use App\Models\Spk;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScholarshipRank extends Widget implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.widgets.scholarship-rank';

    protected function getTableQuery(): Builder
    {
        return Spk::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('scholarship')
                ->label('Nama Beasiswa'),
            TextColumn::make('userRank')
                ->label('Ranking'),
            TextColumn::make('registrant')
                ->label('Jumlah Pendaftar'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
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
