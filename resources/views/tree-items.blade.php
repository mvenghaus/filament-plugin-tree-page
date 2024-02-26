@foreach($items as $item)
    <div x-data="{ open: {{ !empty($this->openStates[$item->id]) ? 'true' : 'false' }} }"
         x-init="$watch('open', (value) => $wire.openStates[{{ $item->id}}] = value)"
         x-sortable-item="{{ $item->id }}"
         class="rounded-lg border px-4 py-2 mb-2 w-full border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900"
         @tree-page:expand-all.window="open = true"
         @tree-page:collapse-all.window="open = false"
    >
        <div class="flex gap-x-1">
            <div>
                <div x-sortable-handle
                     class="cursor-move select-none	flex justify-center items-center w-6 h-6 rounded-lg border border-gray-300 dark:border-gray-600"
                >
                    <x-heroicon-o-arrows-pointing-out class="text-black dark:text-gray-400 w-3 h-3"/>
                </div>
            </div>
            <div @click="open = !open">
                <div class="cursor-pointer select-none flex justify-center items-center w-6 h-6 rounded-lg border border-gray-300 dark:border-gray-600">
                    <x-heroicon-o-minus x-show="open" x-cloak class="text-black dark:text-gray-400 w-3 h-3"/>
                    <x-heroicon-o-plus x-show="!open" x-cloak class="text-black dark:text-gray-400 w-3 h-3"/>
                </div>
            </div>

            <div>
                <div class="px-2">{{ $this->getTreeItemLabel($item) }}</div>
            </div>

            <div class="flex-grow text-right">
                <div class="px-2">
                    <div class="flex justify-end gap-3">
                        @foreach($this->getCachedTreeActions() as $action)
                            {{ ($action->record($item))(['id' => $item->id]) }}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div x-show="open"
             x-cloak
             class="x-cloak pt-6"
        >
            <div x-data="{}"
                 x-sortable-nested
                 x-sortable-list="{{ $item->id }}"
                 x-sortable-group="default"
            >
                @include('tree-page::tree-items', ['items' => $this->getItems($item->id)])
            </div>
        </div>
    </div>
@endforeach