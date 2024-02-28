<?php

declare(strict_types=1);

namespace Mvenghaus\TreeListPage\Actions;

use Filament\Actions\Action;

class TreeItemCreateAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'treeItemCreate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('tree-list-page::translations.actions.create.label'))
            ->authorize(fn(): bool => $this->getLivewire()->getResource()::canCreate())
            ->icon('heroicon-m-plus')
            ->url(fn(Action $action): string => $action->getLivewire()->getResource()::getUrl('create'));

    }
}