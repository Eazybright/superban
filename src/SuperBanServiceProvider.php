<?php

namespace Eazybright\SuperBan;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
// use Eazybright\SuperBan\Superban\Commands\SuperbanCommand;

class SuperBanServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('superban')
            ->hasConfigFile();
            // ->hasViews()
            // ->hasMigration('create_superban_table');
            // ->hasCommand(SuperbanCommand::class);
    }
}
