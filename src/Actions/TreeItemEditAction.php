<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class TreeItemEditAction extends Action
{
    use CanCustomizeProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->requiresConfirmation()
            ->icon(FilamentIcon::resolve('actions::edit-action') ?? 'heroicon-m-pencil')
            ->iconButton();
    }
}