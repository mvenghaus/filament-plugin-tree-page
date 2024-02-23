@php
    $items = \App\Models\PostCategory::query()->where('parent_id', 0)->orderBy('order')->get();
@endphp

<div wire:ignore>
    @script
    @include('tree-page::js.sortable-js')
    @endscript

    <script>
        function treePage() {
            return {
                init() {
                    this.$root.querySelectorAll('.nested-sortable').forEach((el) => {
                        Sortable.create(el, {
                            handle: '.cursor-move',
                            group: 'nested',
                            animation: 150,
                            swapThreshold: 0.65,
                            onEnd: this.save.bind(this),
                        });
                    });
                },
                save(evt) {
                    const updates = [];
                    const groupElements = [evt.from];

                    if (evt.from !== evt.to) {
                        groupElements.push(evt.to);
                    }

                    groupElements.forEach((groupElement) => {
                        const parentId = parseInt(groupElement.dataset.itemParentId);

                        let sort = 0;
                        groupElement.querySelectorAll(':scope > [data-item-id]')
                            .forEach((itemElement) => updates.push({
                                    itemId: parseInt(itemElement.dataset.itemId),
                                    parentId: parentId,
                                    sort: ++sort
                                })
                            );
                    });

                    this.$wire.updateTreeSort(updates);
                }
            }
        }
    </script>

    <x-filament::page class="filament-tree-page" x-data="treePage()">
        <x-filament::grid class="gapx-4 py-2">
            <x-filament::grid.column>
                <div class="nested-sortable pl-2" data-item-parent-id="0">
                    @include('tree-page::list-items', ['items' => $items])
                </div>
            </x-filament::grid.column>
        </x-filament::grid>
    </x-filament::page>
</div>