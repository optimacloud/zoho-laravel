<?php

namespace Optimacloud\Zoho;

use Optimacloud\Zoho\Commands\ZohoAuthentication;
use Optimacloud\Zoho\Commands\ZohoInstallCommand;
use Optimacloud\Zoho\Commands\ZohoSetupCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ZohoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('zoho-laravel')
            ->hasCommands(ZohoAuthentication::class, ZohoInstallCommand::class, ZohoSetupCommand::class)
            ->hasConfigFile('zoho')
            ->hasRoute('web')
            ->hasMigration('create_zohos_table');
    }
}
