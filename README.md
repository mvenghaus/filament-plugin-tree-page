# Filament Plugin - Tree Page


```php

protected function configureAction(Action $action): void
{
    match (true) {
        $action instanceof TreeItemCreateAction => $action->label(__('tree-page::translations.actions.create.label')),
        default => null
    };
}

```