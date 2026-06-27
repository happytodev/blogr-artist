<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6">
            {{ $this->form }}
        </div>

        <div class="flex justify-end pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
            <x-filament::button type="submit" color="primary">
                {{ __('Save') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
