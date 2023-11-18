<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Filament\Widgets\CompletePersonalData;
use App\Filament\Widgets\ScholarshipRank;
use App\Filament\Widgets\ScholarshipStudentRank;
use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BasePage
{
    protected function getHeaderWidgetsColumns(): int|string|array
    {
        return 1;
    }

    protected function getColumns(): int|string|array
    {
        return 1;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }

    protected function getWidgets(): array
    {
        if (auth()->user()->hasRole(Role::Student->value)) {
            if (auth()->user()->hasCompletePersonalData()) {
                return [
                    ScholarshipRank::class,
                    ScholarshipStudentRank::class
                ];
            } else {
                return [
                    CompletePersonalData::class,
                ];
            }
        } else {
            return [
                ScholarshipStudentRank::class
            ];
        }
    }
}
