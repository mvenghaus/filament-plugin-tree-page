<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;

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
            ->label(__('tree-page::translations.actions.create.label'))
            ->authorize(fn(): bool => $this->getLivewire()->getResource()::canCreate())
            ->icon(FilamentIcon::resolve('actions::create-action') ?? 'heroicon-m-plus')
            ->url(fn(Action $action): string => $action->getLivewire()->getResource()::getUrl('create'));

    }
}