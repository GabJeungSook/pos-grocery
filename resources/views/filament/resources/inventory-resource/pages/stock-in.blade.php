<x-filament-panels::page>
    {{$this->form}}

    <div style="display: flex;  justify-content: end; ">
        <div style="margin-right: 0.5rem; align-items: center; ">
            <x-filament::button type="submit" style=" padding-left: 1rem; padding-right: 1rem;" size="md" wire:click="save">Save</x-filament::button>
        </div>
        <div style="display: flex; align-items: center;">
            <button type="button" class="self-end rounded-md bg-white mt-10 px-2.5 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-gray-50">Close</button>
        </div>
    </div>
</x-filament-panels::page>
