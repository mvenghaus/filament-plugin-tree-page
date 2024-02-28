<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TreeItemService
{
    private Collection $items;
    private Collection $excludeItems;

    public function __construct(
        private readonly string $modelClass,
        private readonly string $parentField,
        private readonly string $sortField
    ) {
        $this->excludeItems = collect();
        $this->loadModels();
    }

    public static function make(
        string $modelClass,
        string $parentField,
        string $sortField
    ) {
        return new static($modelClass, $parentField, $sortField);
    }

    public function exclude(?Model $item): static
    {
        if ($item === null) {
            return $this;
        }

        $this->excludeItems->push($item);

        return $this;
    }

    public function getByParentKey(int $startParentKey = 0): Collection
    {
        $items = collect();

        $this->walkItems($items, $startParentKey);

        return $items;
    }

    public function getPathsByParentKey(int $startParentKey = 0): Collection
    {
        return $this->getByParentKey($startParentKey)
            ->map(fn(Model $item) => $this->getPathItems($item));
    }

    public function getPaths(): Collection
    {
        return $this->getPathsByParentKey();
    }

    private function walkItems(Collection $results, int $parentKey = 0): void
    {
        foreach ($this->getItemsByParentKey($parentKey) as $item) {
            if ($this->excludeItems->contains($item)) {
                continue;
            }

            $results[$item->getKey()] = $item;

            if ($this->getItemsByParentKey($item->getKey())->count() > 0) {
                $this->walkItems($results, $item->getKey());
            }
        }
    }

    private function getPathItems(Model $item): Collection
    {
        $pathModelCollection = collect();
        do {
            $pathModelCollection->push($item);

            $item = $this->getItemByKey($item->getAttribute($this->parentField));
        } while ($item !== null);

        return $pathModelCollection->reverse();
    }

    private function getItemByKey(int $key): ?Model
    {
        return $this->items->get($key);
    }

    private function getItemsByParentKey(int $parentKey = 0): Collection
    {
        return $this->items
            ->filter(fn(Model $item) => $item->getAttribute($this->parentField) === $parentKey);
    }

    private function loadModels(): void
    {
        $this->items = $this->modelClass::query()
            ->orderBy($this->parentField)
            ->orderBy($this->sortField)
            ->get()
            ->mapWithKeys(fn(Model $item) => [$item->getKey() => $item]);
    }
}