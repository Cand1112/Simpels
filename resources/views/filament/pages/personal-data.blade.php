<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        <x-filament-support::button
            wire:target="save"
            type="submit"
            color="primary"
            class="mt-4">
            {{ __('Simpan Data') }}
        </x-filament-support::button>
    </form>
</x-filament::page>
