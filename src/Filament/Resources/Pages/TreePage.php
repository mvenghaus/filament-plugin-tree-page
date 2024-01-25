<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Filament\Resources\Pages;

use Filament\Resources\Pages\Page;

class TreePage extends Page
{
    protected static string $view = 'tree-page::list';

    public function getTreeActions(): array
    {
        return [];
    }
}