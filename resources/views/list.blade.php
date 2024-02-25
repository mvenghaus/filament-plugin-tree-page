<div>
    <script>
        function treePage() {
            return {
                sortableList: [],
                init() {
                    this.initSortable();
                },
                initSortable() {
                    console.log('init');
                    this.$root.querySelectorAll('.nested-sortable').forEach((el) => {
                        Sortable.create(el, {
                            handle: '.cursor-move',
                            group: 'nested',
                            animation: 150,
                            swapThreshold: 0.65,
                            onEnd: this.save.bind(this),
                        })
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

    <div>
        <x-filament::page class="filament-tree-page"
                          x-data="treePageList"
                          @end="save($event)"
        >
            <x-filament::grid class="gapx-4 py-2">
                <x-filament::grid.column>
                    <div x-data="{}"
                         x-sortable-nested
                         x-sortable-list="0"
                         x-sortable-group="default"
                         class="pl-2"
                    >
                        @include('tree-page::list-items', ['items' => $this->getItems()])
                    </div>
                </x-filament::grid.column>
            </x-filament::grid>
        </x-filament::page>
    </div>

    @script
    <script>
        Alpine.directive('sortable-nested', (el) => {
            let animation = parseInt(el.dataset?.sortableAnimationDuration)

            if (animation !== 0 && !animation) {
                animation = 300;
            }

            el.sortable = Sortable.create(el, {
                draggable: '[x-sortable-item]',
                group: el.getAttribute('x-sortable-group'),
                handle: '[x-sortable-handle]',
                dataIdAttr: 'x-sortable-item',
                animation: animation,
                ghostClass: 'fi-sortable-ghost',
            });
        });

        Alpine.data('treePageList', () => {
            return {
                save(evt) {
                    const updates = [];
                    const listElements = [evt.from];

                    if (evt.from !== evt.to) {
                        listElements.push(evt.to);
                    }

                    listElements.forEach((groupElement) => {
                        const parentId = parseInt(groupElement.getAttribute('x-sortable-list'));
                        let sort = 0;
                        groupElement.querySelectorAll(':scope > [x-sortable-item]')
                            .forEach((itemElement) => updates.push({
                                    id: parseInt(itemElement.getAttribute('x-sortable-item')),
                                    parentId: parentId,
                                    sort: sort++
                                })
                            );
                    });

                    this.$wire.updateTreeSort(updates);
                }
            }
        });

    </script>
    @endscript
</div>