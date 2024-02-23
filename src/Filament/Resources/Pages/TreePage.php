<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Filament\Resources\Pages;

use App\Models\PostCategory;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\On;

class TreePage extends Page
{
    protected static string $view = 'tree-page::list';

    public function getTreeActions(): array
    {
        return [];
    }

    public function updateTreeSort(array $updates): void
    {
        foreach ($updates as $update) {
            $postCategory = PostCategory::findOrFail($update['itemId']);

            $postCategory->update(['parent_id' => $update['parentId'], 'order' => $update['sort']]);
        }
    }
}