<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class TreeItemDeleteAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'treeItemDelete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->requiresConfirmation()
            ->authorize(
                fn(Model $record): bool => $this->getLivewire()->getResource()::canDelete($record)
            )
            ->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-trash')
            ->iconButton()
            ->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'))
            ->action(function () {
                $this->process(function (Model $record) {
                    $livewire = $this->getLivewire();

                    $ids = collect();
                    $idsRecursive = function (int $parentId) use (&$idsRecursive, $livewire, $ids) {
                        $ids->push($parentId);

                        $livewire->getItems($parentId)
                            ->map(fn(Model $record): int => $record->getAttribute($livewire->getTreeItemIdField()))
                            ->each(fn(int $id) => $idsRecursive($id));
                    };

                    $idsRecursive($record->getAttribute($livewire->getTreeItemIdField()));

                    $record::query()
                        ->whereIn($livewire->getTreeItemIdField(), $ids)
                        ->each(fn(Model $record) => $record->delete());
                });

                $this->success();
            });
    }


}