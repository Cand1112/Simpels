<?php

namespace App\Filament\Resources\ScholarshipResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\ScholarshipResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScholarships extends ListRecords
{
    protected static string $resource = ScholarshipResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => auth()->user()->hasAnyRole([Role::Admin->value, Role::SuperAdmin->value])),
        ];
    }
}
