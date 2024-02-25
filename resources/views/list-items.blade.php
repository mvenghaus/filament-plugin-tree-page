@foreach($items as $item)
    <div x-data="{ open: true }"
         data-item-id="{{ $item->id }}"
         class="rounded-lg border px-4 py-2 mb-2 w-full border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900"
    >
        <div class="flex gap-x-1">
            <div>
                <div class="cursor-move select-none	flex justify-center items-center w-6 h-6 rounded-lg border border-gray-300 dark:border-gray-600">
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
                        {{ ($this->treeItemEditAction->record($item))(['id' => $item->id]) }}
                        {{ ($this->treeItemDeleteAction->record($item))(['id' => $item->id]) }}
                    </div>
                </div>
            </div>
        </div>
        <div x-show="open"
             x-cloak
             class="x-cloak nested-sortable pt-6"
             data-item-parent-id="{{ $item->id }}"
        >
            @include('tree-page::list-items', ['items' => $this->getItems($item->id)])
        </div>
    </div>
@endforeach