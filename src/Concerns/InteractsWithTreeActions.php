<?php

declare(strict_types=1);

namespace Mvenghaus\TreeListPage\Concerns;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithTreeActions
{
    use InteractsWithActions;

    public ?Model $mountedRecord = null;

    /** @var array<Action> */
    protected array $cachedTreeActions = [];

    public function bootedInteractsWithTreeActions(): void
    {
        $this->cacheTreeActions();
    }

    protected function cacheTreeActions(): void
    {
        $actions = Action::configureUsing(
            $this->configureAction(...),
            fn(): array => $this->getTreeActions(),
        );

        foreach ($actions as $action) {
            $this->cacheAction($action);
            $this->cachedTreeActions[] = $action;
        }
    }

    /**
     * @return array<Action>
     */
    public function getCachedTreeActions(): array
    {
        return $this->cachedTreeActions;
    }

    /**
     * @return array<Action>
     */
    protected function getTreeActions(): array
    {
        return [];
    }

    public function mountAction(string $name, array $arguments = []): mixed
    {
        $this->mountedRecord = $this->getModel()::findOrFail($arguments['id']);

        foreach ($this->getCachedTreeActions() as $cachedTreeAction) {
            $cachedTreeAction->record($this->mountedRecord);
        }

        return parent::mountAction($name, $arguments);
    }

    public function callMountedAction(array $arguments = []): mixed
    {
        $this->getMountedAction()->record($this->mountedRecord);

        return parent::callMountedAction($arguments);
    }

    public function unmountAction(bool $shouldCancelParentActions = true): void
    {
        $this->mountedRecord = null;

        parent::unmountAction($shouldCancelParentActions);
    }
}
