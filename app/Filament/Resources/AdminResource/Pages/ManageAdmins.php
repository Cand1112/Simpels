<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Enums\Role;
use App\Filament\Resources\AdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAdmins extends ManageRecords
{
    protected static string $resource = AdminResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (AdminResource $resource, $record) {
                    $record->assignRole(Role::Admin->value);
                }),
        ];
    }
}
