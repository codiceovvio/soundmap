<?php

namespace CodiceOvvio\Soundmap;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CodiceOvvio\Soundmap\Commands\SoundmapCommand;

class SoundmapServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('soundmap')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_soundmap_table')
            ->hasCommand(SoundmapCommand::class);
    }
}
