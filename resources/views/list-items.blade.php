
@foreach($items as $item)
    <div data-item-id="{{ $item->id }}" class="rounded-lg border px-4 py-2 mb-2 w-full border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900">
        <div class="flex gap-x-1">
            <div>
                <div class="cursor-move select-none	flex justify-center items-center w-6 h-6 rounded-lg border border-gray-600 dark:border-white/50">
                    <x-heroicon-o-arrows-pointing-out class="text-gray-400 w-3 h-3" />
                </div>
            </div>
            <div onclick="
                    const el = this.parentNode.parentNode.querySelector('.nested-sortable');
                    if (el.style.display === 'none') {
                        el.style.display = 'block';
                    } else {
                        el.style.display = 'none';
                    }
                 "
            >
                <div class="cursor-pointer select-none flex justify-center items-center w-6 h-6 rounded-lg border border-gray-600 dark:border-white/50">
                    <x-heroicon-o-minus class="text-gray-400 w-3 h-3" />
                </div>
            </div>

            <div>
                <div class="px-2">{{ $item->name }} ({{ $item->id }})</div>
            </div>
        </div>
        <div class="nested-sortable pt-6" data-item-parent-id="{{ $item->id }}">
            @include('tree-page::list-items', ['items' => $item->children])
        </div>
    </div>
@endforeach