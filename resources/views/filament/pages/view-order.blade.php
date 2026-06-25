<x-filament-panels::page>
    <div class="bg-white p-6 rounded-xl shadow dark:bg-gray-900 dark:border-gray-800">

        <div class="mt-6">
            {{--  This correctly renders your defined infolist schema in Filament v4 --}}
            {{ $this->infolist }}
        </div>
    </div>
</x-filament-panels::page>
