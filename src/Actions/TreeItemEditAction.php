<?php

declare(strict_types=1);

namespace Mvenghaus\TreeListPage\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class TreeItemEditAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'treeItemEdit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->authorize(fn(Model $record): bool => $this->getLivewire()->getResource()::canEdit($record))
            ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil')
            ->iconButton()
            ->url(
                fn(Model $record): string => $this->getLivewire()->getResource()::getUrl('edit', ['record' => $record])
            );
    }

}