@php
$items = \App\Models\PostCategory::query()->where('parent_id', 0)->get();
@endphp

<div>
    @include('tree-page::js.sortable-js')

    <x-filament::page class="filament-tree-page">
        <x-filament::grid
                class="gapx-4 py-2"
        >
            <x-filament::grid.column>

                @foreach($items as $item)
                    <div class="nested-sortable pl-2">
                        <div class="rounded-lg border px-4 py-2 mb-2 w-full border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900">
                            <div class="flex">
                                <div class="cursor-move"><x-heroicon-o-arrows-pointing-out class="text-gray-400 w-4 h-6" /></div>
                                <div class="px-2">{{ $item->name }}</div>
                            </div>
                            <div class="nested-sortable pt-4">
                                @include('tree-page::list-group', ['items' => $item->children])
                            </div>
                        </div>
                    </div>
                @endforeach

            </x-filament::grid.column>
        </x-filament::grid>
    </x-filament::page>

    <script>
        document.querySelectorAll('.nested-sortable').forEach((el) => {
            Sortable.create(el, {
                handle: '.cursor-move',
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65
            });
        });
    </script>
</div>