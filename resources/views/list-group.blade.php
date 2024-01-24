
@foreach($items as $item)
    <div class="rounded-lg border px-4 py-2 mb-2 w-full border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900">
        <div>{{ $item->name }}</div>
        <div class="nested-sortable pt-4">
            @include('tree-page::list-group', ['items' => $item->children])
        </div>
    </div>
@endforeach