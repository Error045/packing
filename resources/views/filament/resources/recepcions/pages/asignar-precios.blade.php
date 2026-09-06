<x-filament-panels::page>
    <form wire:submit="guardar">
        {{ $this->form }}

        <div class="mt-6 flex gap-3">
            <x-filament::button type="submit" color="success">
                Guardar Precios
            </x-filament::button>

            <x-filament::button color="gray" tag="a" href="{{ $this->getResource()::getUrl('index') }}">
                Cancelar
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>