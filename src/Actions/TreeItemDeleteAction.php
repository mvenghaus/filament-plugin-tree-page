<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class TreeItemDeleteAction extends Action
{
    use CanCustomizeProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->requiresConfirmation()
            ->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-m-trash')
            ->iconButton();
    }
}