<?php

namespace Optimacloud\Zoho\Commands;

use Optimacloud\Zoho\Services\ZohoTokenService;
use Optimacloud\Zoho\Zoho;
use com\zoho\crm\api\exception\SDKException;
use Illuminate\Console\Command;

class ZohoSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'zoho:grant {token? : generate grant token from https://accounts.zoho.com/developerconsole}';

    /**
     * The console command description.
     */
    protected $description = 'Setup zoho credentials in case you used Self-Client OAuth method';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws SDKException
     */
    public function handle()
    {
        $grantToken = $this->argument('token') ?? config('zoho.token');

        if (!$grantToken) {
            $this->error('The Grant Token is required.');
            $this->info('generate token by visiting : https://accounts.zoho.com/developerconsole');

            return Command::FAILURE;
        }


        Zoho::initialize($grantToken);

        $zohoTokenService = new ZohoTokenService();
        $zohoTokenService->updateRemoteToken();

        $this->info('Zoho CRM has been set up successfully.');

        return Command::SUCCESS;
    }
}
