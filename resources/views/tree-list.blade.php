<div>
    <x-filament::page class="filament-tree-list-page"
                      x-data="treeListPage"
                      @end="save($event)"
    >
        @if($this->getItems()->count())
            <div class="filament-tree-list-page-actions">
                <x-filament::button @click="$dispatch('tree-list-page:expand-all')">
                    Expand all
                </x-filament::button>
                <x-filament::button @click="$dispatch('tree-list-page:collapse-all')">
                    Collapse all
                </x-filament::button>
            </div>

            <x-filament::grid class="gapx-4 py-2">
                <x-filament::grid.column>
                    <div x-data="{}"
                         x-sortable-nested
                         x-sortable-list="0"
                         x-sortable-group="default"
                         class="pl-2"
                    >
                        @include('tree-list-page::tree-list-items', ['items' => $this->getItems()])
                    </div>
                </x-filament::grid.column>
            </x-filament::grid>
        @else
            <div class="filament-tree-list-page-empty px-6 py-12">
                <div class="mx-auto grid max-w-lg justify-items-center text-center">
                    <div class="mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                        <x-heroicon-s-x-mark class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                    </div>
                    <h4 class="text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Keine Datensätze gefunden
                    </h4>
                </div>
            </div>
        @endif
    </x-filament::page>

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

        Alpine.data('treeListPage', () => {
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