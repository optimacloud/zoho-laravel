<?php


namespace Optimacloud\Zoho\Commands;


use Illuminate\Console\Command;

class ZohoAuthentication extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'zoho:authentication';

    /**
     * The console command description.
     */
    protected $description = 'Generate OAuth url to complete the Authentication process.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $query_params = [
            'scope'         => config('zoho.oauth_scope'),
            'prompt'        => 'consent',
            'client_id'     => config('zoho.client_id'),
            'response_type' => 'code',
            'access_type'   => config('zoho.access_type'),
            'redirect_uri'  => config('zoho.redirect_uri'),
        ];

        $accounts_url  = config('zoho.accounts_url');
        $redirect_url = $accounts_url . '/oauth/v2/auth?' . http_build_query($query_params);

        $this->info('Copy the following URL, paste it in your browser, and hit return:');
        $this->line($redirect_url);
    }
}
