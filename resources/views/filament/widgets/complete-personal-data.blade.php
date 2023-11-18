<x-filament::widget>
    <x-filament::card class="bg-warning-200">
        <div class="flex gap-3 items-center">
            <x-heroicon-o-information-circle class="w-6 h-6 text-warning-600" />
            <p>
                {{ __('Please complete your personal data to continue.') }}
                <a href="{{ route(\App\Filament\Pages\PersonalData::getRouteName()) }}" class="font-medium text-warning-600 hover:text-warning-500">
                    {{ __('Complete now') }}
                </a>
            </p>
        </div>
    </x-filament::card>
</x-filament::widget>
