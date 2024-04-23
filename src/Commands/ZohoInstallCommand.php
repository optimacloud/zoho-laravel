<?php


namespace Optimacloud\Zoho\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Optimacloud\Zoho\Services\ZohoTokenService;

class ZohoInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'zoho:install';

    /**
     * The console command description.
     */
    protected $description = 'Install Zoho resources';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->comment('Generate Empty Zoho OAuth files ...');

        /**
         * Due to the Zoho SDK only able to work with a local token we have to have a copy on the server
         * Even if using a remote disk
         */
        Storage::disk('local')->put(config('zoho.application_log_file_path'), '');
        Storage::disk('local')->put(config('zoho.token_persistence_path'), '');


        $this->info('Zoho scaffolding installed successfully.');
    }
}
