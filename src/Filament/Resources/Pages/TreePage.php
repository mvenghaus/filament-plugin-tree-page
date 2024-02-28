<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Filament\Resources\Pages;

use App\Models\PostCategory;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mvenghaus\TreePage\Actions\TreeItemCreateAction;
use Mvenghaus\TreePage\Actions\TreeItemDeleteAction;
use Mvenghaus\TreePage\Actions\TreeItemEditAction;
use Mvenghaus\TreePage\Concerns\InteractsWithTreeActions;

class TreePage extends Page implements HasForms, HasActions
{
    use InteractsWithTreeActions;
    use InteractsWithForms;

    protected static string $view = 'tree-page::tree';

    protected static string $treeItemLabelField = 'name';
    protected static string $treeItemParentField = 'parent_id';
    protected static string $treeItemSortField = 'order';

    protected static bool $defaultOpenState = true;

    protected ?Collection $records = null;

    public array $openStates = [];

    public function getTreeItemLabelField(): string
    {
        return static::$treeItemLabelField;
    }

    public function getTreeItemParentField(): string
    {
        return static::$treeItemParentField;
    }

    public function getTreeItemSortField(): string
    {
        return static::$treeItemSortField;
    }

    public function getTreeItemLabel(Model $record): string
    {
        return (string) $record->getAttribute($this->getTreeItemLabelField());
    }

    public function getDefaultOpenState(): bool
    {
        return static::$defaultOpenState;
    }

    public function getRecords(): ?Collection
    {
        if ($this->records !== null) {
            return $this->records;
        }

        return $this->records = $this->getModel()::query()
            ->orderBy($this->getTreeItemSortField())
            ->get();
    }

    public function getItems(int $parentId = 0): Collection
    {
        return $this->getRecords()
            ->filter(fn(Model $record) => $record->getAttribute($this->getTreeItemParentField()) === $parentId);
    }

    public function updateTreeSort(array $updates): void
    {
        foreach ($updates as $update) {
            $postCategory = PostCategory::findOrFail($update['id']);

            $postCategory->update([
                $this->getTreeItemParentField() => $update['parentId'],
                $this->getTreeItemSortField() => $update['sort']
            ]);
        }

        Notification::make()
            ->title(__('tree-page::translations.messages.saved'))
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            ...($this->getResource()::hasPage('create') ? [TreeItemCreateAction::make()] : []),
        ];
    }

    protected function getTreeActions(): array
    {
        return [
            ...($this->getResource()::hasPage('edit') ? [TreeItemEditAction::make()] : []),
            ...($this->getResource()::canDeleteAny() ? [TreeItemDeleteAction::make()] : [])
        ];
    }
}