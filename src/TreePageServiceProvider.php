<?php

declare(strict_types=1);

namespace Mvenghaus\TreePage;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TreePageServiceProvider extends PackageServiceProvider
{
    public static string $name = 'tree-page';

    public static string $viewNamespace = 'tree-page';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasTranslations();

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }
}
