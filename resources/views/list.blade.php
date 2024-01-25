@php
$items = \App\Models\PostCategory::query()->where('parent_id', 0)->get();
@endphp

<div>
    @include('tree-page::js.sortable-js')

    <x-filament::page class="filament-tree-page">
        <x-filament::grid class="gapx-4 py-2">
            <x-filament::grid.column>
                <div class="nested-sortable pl-2">
                    @include('tree-page::list-items', ['items' => $items])
                </div>
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