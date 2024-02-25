<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Filament\Resources\Pages;

use App\Models\PostCategory;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mvenghaus\TreePage\Actions\TreeItemDeleteAction;
use Mvenghaus\TreePage\Actions\TreeItemEditAction;

class TreePage extends Page
{
    protected static string $view = 'tree-page::list';

    public function getTreeItemLabel(Model $record): string
    {
        return $record->name;
    }

    public function getItems(int $parentId = 0): Collection
    {
        return $this->getModel()::query()
            ->where('parent_id', $parentId)
            ->orderBy('order')
            ->get();
    }

    public function updateTreeSort(array $updates): void
    {
        foreach ($updates as $update) {
            $postCategory = PostCategory::findOrFail($update['itemId']);

            $postCategory->update(['parent_id' => $update['parentId'], 'order' => $update['sort']]);
        }
    }
    protected function getHeaderActions(): array
    {
        if ($this->getResource()::hasPage('create')) {
            return [
                Action::make('create')
                    ->url(fn(): string => $this->getResource()::getUrl('create'))
            ];
        }

        return [];
    }

    public function treeItemEditAction(): Action
    {
        return TreeItemEditAction::make('treeItemEdit')
            ->record(fn(array $arguments) => $this->getModel()::findOrFail($arguments['id'] ?? 0))
            ->authorize(fn(Model $record): bool => $this->getResource()::canEdit($record))
            ->url(fn(Model $record): string => $this->getResource()::getUrl('edit', ['record' => $record]));
    }

    public function treeItemDeleteAction(): Action
    {
        return TreeItemDeleteAction::make('treeItemDelete')
            ->record(fn(array $arguments) => $this->getModel()::findOrFail($arguments['id'] ?? 0))
            ->authorize(fn(Model $record): bool => $this->getResource()::canDelete($record))
            ->action(function (Model $record) {
                $this->failure();
            });

    }
}