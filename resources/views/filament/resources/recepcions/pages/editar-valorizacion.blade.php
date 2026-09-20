<x-filament-panels::page>

    <form wire:submit="guardar">

        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-3">

            <x-filament::button type="submit" icon="heroicon-o-check">
                Guardar cambios
            </x-filament::button>

            <x-filament::button color="gray" tag="a" :href="App\Filament\Resources\Recepcions\RecepcionResource::getUrl('index')">
                Cancelar
            </x-filament::button>

        </div>

    </form>

</x-filament-panels::page>
