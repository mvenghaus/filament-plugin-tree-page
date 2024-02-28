<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TreeService
{
    private Collection $models;
    private Collection $excludeModels;

    public function __construct(
        private readonly string $modelClass,
        private readonly string $parentField,
        private readonly string $sortField
    ) {
        $this->excludeModels = collect();
        $this->loadModels();
    }

    public static function make(
        string $modelClass,
        string $parentField,
        string $sortField
    ) {
        return new static($modelClass, $parentField, $sortField);
    }

    public function buildTree(): Collection
    {
        $tree = collect();

        $this->walkModels($tree);

        return $tree;
    }

    public function exclude(?Model $model): static
    {
        if ($model === null) {
            return $this;
        }

        $this->excludeModels->push($model);

        return $this;
    }

    private function walkModels(Collection $results, int $parentKey = 0): void
    {
        foreach ($this->getModelsByParentKey($parentKey) as $model) {
            if ($this->excludeModels->contains($model)) {
                continue;
            }

            $results[$model->getKey()] = $this->getPathModels($model);

            if ($this->getModelsByParentKey($model->getKey())->count() > 0) {
                $this->walkModels($results, $model->getKey());
            }
        }
    }

    private function getPathModels(Model $model): Collection
    {
        $pathModelCollection = collect();
        do {
            $pathModelCollection->push($model);

            $model = $this->getModelByKey($model->getAttribute($this->parentField));
        } while ($model !== null);

        return $pathModelCollection->reverse();
    }

    private function getModelByKey(int $key): ?Model
    {
        return $this->models->get($key);
    }

    private function getModelsByParentKey(int $parentKey = 0): Collection
    {
        return $this->models
            ->filter(fn(Model $model) => $model->getAttribute($this->parentField) === $parentKey);
    }

    private function loadModels(): void
    {
        $this->models = $this->modelClass::query()
            ->orderBy($this->parentField)
            ->orderBy($this->sortField)
            ->get()
            ->mapWithKeys(fn(Model $model) => [$model->getKey() => $model]);
    }
}