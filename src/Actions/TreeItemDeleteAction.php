<?php

declare(strict_types=1);

namespace Mvenghaus\TreeListPage\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Mvenghaus\TreePage\Services\TreeItemService;

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
            ->authorize(fn(Model $record): bool => $this->getLivewire()->getResource()::canDelete($record))
            ->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-trash')
            ->iconButton()
            ->successNotificationTitle(__('tree-page::translations.messaged.deleted'))
            ->action(function () {
                $this->process(function (Model $record) {
                    $livewire = $this->getLivewire();

                    $treeItemService = TreeItemService::make(
                        $livewire::$resource,
                        $livewire->getTreeItemParentField(),
                        $livewire->getTreeItemSortField()
                    );

                    $treeItemService->getByParentKey($record->getKey())
                        ->each(fn(Model $record) => $record->delete());

                    $record->delete();
                });

                $this->success();
            });
    }


}